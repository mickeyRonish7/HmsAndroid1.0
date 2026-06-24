<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // ----------------------------------------------------------------
    // Shared: format one attendance record for JSON output
    // ----------------------------------------------------------------
    private function attendanceResource(Attendance $record): array
    {
        return [
            'id'         => $record->id,
            'date'       => $record->date,
            'status'     => $record->status,
            'time_in'    => $record->time_in
                                ? Carbon::parse($record->time_in)->format('h:i A')
                                : null,
            'time_out'   => $record->time_out
                                ? Carbon::parse($record->time_out)->format('h:i A')
                                : null,
            'time_in_raw'  => $record->time_in,
            'time_out_raw' => $record->time_out,
            'created_at'   => $record->created_at?->toDateTimeString(),
        ];
    }

    // ----------------------------------------------------------------
    // GET /api/attendance/my   (requires auth:sanctum)
    //
    // Returns the authenticated student's attendance records with a
    // monthly summary block.
    //
    // Optional query params:
    //   ?month=2026-06     — filter by year-month (default: current month)
    //   ?status=present|absent|late
    // ----------------------------------------------------------------
    public function myAttendance(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Access denied. This endpoint is for students only.',
            ], 403);
        }

        // Parse month param — default to current month
        $month = null;
        if ($request->filled('month')) {
            try {
                $month = Carbon::createFromFormat('Y-m', $request->month);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid month format. Use YYYY-MM (e.g. 2026-06).',
                ], 422);
            }
        } else {
            $month = Carbon::now();
        }

        $query = Attendance::where('student_id', $user->id)
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->orderBy('date', 'desc')->get();

        // Monthly summary using the unfiltered month data
        $allThisMonth = Attendance::where('student_id', $user->id)
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->get();

        $presentDays = $allThisMonth->where('status', 'present')->count();
        $absentDays  = $allThisMonth->where('status', 'absent')->count();
        $lateDays    = $allThisMonth->where('status', 'late')->count();
        $totalDays   = $allThisMonth->count();

        return response()->json([
            'status'  => 'success',
            'month'   => $month->format('Y-m'),
            'summary' => [
                'total_days'   => $totalDays,
                'present_days' => $presentDays,
                'absent_days'  => $absentDays,
                'late_days'    => $lateDays,
                'attendance_percentage' => $totalDays > 0
                    ? round(($presentDays / $totalDays) * 100, 1)
                    : 0,
            ],
            'total'   => $records->count(),
            'records' => $records->map(fn ($r) => $this->attendanceResource($r)),
        ]);
    }

    // ----------------------------------------------------------------
    // POST /api/attendance/checkin   (requires auth:sanctum)
    //
    // Records the student's check-in time for today.
    // Creates a new attendance record if none exists for today,
    // or updates time_in if already created (e.g. by admin).
    // Prevents duplicate check-in within the same day.
    // ----------------------------------------------------------------
    public function checkIn(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Access denied. This endpoint is for students only.',
            ], 403);
        }

        $today = Carbon::today()->toDateString();
        $now   = Carbon::now();

        // Find existing record for today or create a fresh one
        $attendance = Attendance::firstOrCreate(
            ['student_id' => $user->id, 'date' => $today],
            ['status' => 'present']
        );

        // Prevent duplicate check-in
        if ($attendance->time_in !== null) {
            return response()->json([
                'status'      => 'error',
                'message'     => 'You have already checked in today.',
                'checked_in_at' => Carbon::parse($attendance->time_in)->format('h:i A'),
                'attendance'  => $this->attendanceResource($attendance),
            ], 409);
        }

        // Determine late status — after 10:00 AM is considered late
        $cutoff = Carbon::today()->setTime(10, 0, 0);
        if ($now->greaterThan($cutoff) && $attendance->status === 'present') {
            $attendance->status = 'late';
        }

        $attendance->time_in = $now->format('H:i:s');
        $attendance->save();

        AuditLogger::log('checkin', 'Attendance', $user, "Mobile check-in at {$now->format('H:i:s')}");

        return response()->json([
            'status'     => 'success',
            'message'    => 'Check-in recorded successfully.',
            'attendance' => $this->attendanceResource($attendance),
        ], 201);
    }

    // ----------------------------------------------------------------
    // POST /api/attendance/checkout   (requires auth:sanctum)
    //
    // Records the student's check-out time for today.
    // Requires a check-in to exist first.
    // Prevents duplicate check-out within the same day.
    // ----------------------------------------------------------------
    public function checkOut(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Access denied. This endpoint is for students only.',
            ], 403);
        }

        $today = Carbon::today()->toDateString();
        $now   = Carbon::now();

        $attendance = Attendance::where('student_id', $user->id)
            ->where('date', $today)
            ->first();

        // Must check in before checking out
        if (! $attendance || $attendance->time_in === null) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You must check in before checking out.',
            ], 422);
        }

        // Prevent duplicate check-out
        if ($attendance->time_out !== null) {
            return response()->json([
                'status'         => 'error',
                'message'        => 'You have already checked out today.',
                'checked_out_at' => Carbon::parse($attendance->time_out)->format('h:i A'),
                'attendance'     => $this->attendanceResource($attendance),
            ], 409);
        }

        $attendance->time_out = $now->format('H:i:s');
        $attendance->save();

        AuditLogger::log('checkout', 'Attendance', $user, "Mobile check-out at {$now->format('H:i:s')}");

        // Calculate duration
        $timeIn       = Carbon::parse($attendance->time_in);
        $timeOut      = Carbon::parse($attendance->time_out);
        $durationMins = (int) $timeIn->diffInMinutes($timeOut);
        $hours        = intdiv($durationMins, 60);
        $minutes      = $durationMins % 60;

        return response()->json([
            'status'     => 'success',
            'message'    => 'Check-out recorded successfully.',
            'duration'   => "{$hours}h {$minutes}m",
            'attendance' => $this->attendanceResource($attendance),
        ]);
    }
}
