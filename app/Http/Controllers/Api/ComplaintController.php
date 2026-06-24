<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    // Valid categories — kept in sync with the web form
    private const CATEGORIES = ['plumbing', 'electricity', 'food', 'cleanliness', 'security', 'other'];

    // ----------------------------------------------------------------
    // Shared: format a single complaint for JSON output
    // ----------------------------------------------------------------
    private function complaintResource(Complaint $complaint): array
    {
        return [
            'id'           => $complaint->id,
            'category'     => $complaint->category,
            'description'  => $complaint->description,
            'status'       => $complaint->status,
            'admin_remark' => $complaint->admin_remark,
            'submitted_at' => $complaint->created_at?->toDateTimeString(),
            'updated_at'   => $complaint->updated_at?->toDateTimeString(),
        ];
    }

    // ----------------------------------------------------------------
    // POST /api/complaints   (requires auth:sanctum, students only)
    //
    // Student submits a new complaint.
    // Fires admin notification and audit log — identical to web flow.
    //
    // Request body:
    //   category    — required, one of CATEGORIES list above
    //   description — required, string, min 10 chars
    // ----------------------------------------------------------------
    public function store(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Access denied. Only students can submit complaints.',
            ], 403);
        }

        $validated = $request->validate([
            'category'    => ['required', 'string', 'in:' . implode(',', self::CATEGORIES)],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $complaint = Complaint::create([
            'student_id'  => $user->id,
            'category'    => $validated['category'],
            'description' => $validated['description'],
            'status'      => 'pending',
        ]);

        // Notify admin — same as web controller
        app(NotificationService::class)->notifyNewComplaint($complaint->id, $user->id);

        AuditLogger::log(
            'create_complaint',
            'Complaint',
            $complaint,
            'Student submitted a complaint via mobile API'
        );

        return response()->json([
            'status'    => 'success',
            'message'   => 'Complaint submitted successfully.',
            'complaint' => $this->complaintResource($complaint),
        ], 201);
    }

    // ----------------------------------------------------------------
    // GET /api/complaints/my   (requires auth:sanctum, students only)
    //
    // Returns the authenticated student's own complaints, newest first.
    //
    // Optional query params:
    //   ?status=pending|open|in_progress|resolved
    //   ?category=plumbing|electricity|food|cleanliness|security|other
    // ----------------------------------------------------------------
    public function myComplaints(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Access denied. This endpoint is for students only.',
            ], 403);
        }

        $query = Complaint::where('student_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $complaints = $query->latest()->get();

        // Summary counts (always from full unfiltered set)
        $all = Complaint::where('student_id', $user->id)->get();

        return response()->json([
            'status'  => 'success',
            'summary' => [
                'total'       => $all->count(),
                'pending'     => $all->where('status', 'pending')->count(),
                'open'        => $all->where('status', 'open')->count(),
                'in_progress' => $all->where('status', 'in_progress')->count(),
                'resolved'    => $all->where('status', 'resolved')->count(),
            ],
            'total'      => $complaints->count(),
            'complaints' => $complaints->map(fn ($c) => $this->complaintResource($c)),
        ]);
    }
}
