<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    // ----------------------------------------------------------------
    // Shared: format one visitor record for JSON output
    // ----------------------------------------------------------------
    private function visitorResource(Visitor $visitor): array
    {
        return [
            'id'           => $visitor->id,
            'visitor_name' => $visitor->visitor_name,
            'phone'        => $visitor->phone,
            'purpose'      => $visitor->purpose,
            'visit_date'   => $visitor->visit_date,
            'status'       => $visitor->status,
            'entry_time'   => $visitor->entry_time
                                ? Carbon::parse($visitor->entry_time)->format('Y-m-d H:i:s')
                                : null,
            'exit_time'    => $visitor->exit_time
                                ? Carbon::parse($visitor->exit_time)->format('Y-m-d H:i:s')
                                : null,
            'submitted_at' => $visitor->created_at?->toDateTimeString(),
            'updated_at'   => $visitor->updated_at?->toDateTimeString(),
        ];
    }

    // ----------------------------------------------------------------
    // POST /api/visitors   (requires auth:sanctum, students only)
    //
    // Student logs a new visitor request for a specific date.
    // Sets status = 'pending' — admin must approve before entry.
    // Notifies admin and logs the action.
    //
    // Request body:
    //   visitor_name  — required
    //   phone         — optional
    //   purpose       — required
    //   visit_date    — required, date, today or future
    // ----------------------------------------------------------------
    public function store(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Access denied. Only students can submit visitor requests.',
            ], 403);
        }

        $validated = $request->validate([
            'visitor_name' => ['required', 'string', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'purpose'      => ['required', 'string', 'min:5', 'max:500'],
            'visit_date'   => ['required', 'date', 'after_or_equal:today'],
        ]);

        $visitor = Visitor::create([
            'student_id'   => $user->id,
            'visitor_name' => $validated['visitor_name'],
            'phone'        => $validated['phone'] ?? null,
            'purpose'      => $validated['purpose'],
            'visit_date'   => $validated['visit_date'],
            'status'       => 'pending',
            // entry_time / exit_time remain null until admin approves + visitor physically arrives
        ]);

        // Notify admin about the new visitor request
        app(NotificationService::class)->notifyRole(
            'admin',
            'info',
            'New Visitor Request',
            "Student {$user->name} has submitted a visitor request for {$validated['visit_date']}. Visitor: {$validated['visitor_name']}.",
            ['visit_id' => $visitor->id, 'student_id' => $user->id],
            route('admin.visitors.requests')
        );

        AuditLogger::log(
            'create_visitor_request',
            'Visitor',
            $visitor,
            "Student submitted visitor request via mobile API for {$validated['visit_date']}"
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Visitor request submitted successfully. Awaiting admin approval.',
            'visitor' => $this->visitorResource($visitor),
        ], 201);
    }

    // ----------------------------------------------------------------
    // GET /api/visitors/my   (requires auth:sanctum, students only)
    //
    // Returns all visitor requests linked to the authenticated student,
    // newest first, with a summary by status.
    //
    // Optional query params:
    //   ?status=pending|approved|rejected
    //   ?visit_date=2026-07-01   — exact date filter
    // ----------------------------------------------------------------
    public function myVisitors(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Access denied. This endpoint is for students only.',
            ], 403);
        }

        $query = Visitor::where('student_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('visit_date')) {
            $query->whereDate('visit_date', $request->visit_date);
        }

        $visitors = $query->latest()->get();

        // Summary from full unfiltered set
        $all = Visitor::where('student_id', $user->id)->get();

        return response()->json([
            'status'  => 'success',
            'summary' => [
                'total'    => $all->count(),
                'pending'  => $all->where('status', 'pending')->count(),
                'approved' => $all->where('status', 'approved')->count(),
                'rejected' => $all->where('status', 'rejected')->count(),
            ],
            'total'    => $visitors->count(),
            'visitors' => $visitors->map(fn ($v) => $this->visitorResource($v)),
        ]);
    }
}
