<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Fee;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user();
        $notices = \App\Models\Notice::whereIn('audience', ['all', 'students'])->latest()->take(5)->get();
        // Calculate due fees
        $dueFees = $student->fees()->where('status', 'pending')->sum('amount');
        // Get recent activity logs
        $recentActivities = \App\Models\AuditLog::where('user_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();
        
        return view('student.dashboard', compact('student', 'notices', 'dueFees', 'recentActivities'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $data = $request->only(['phone', 'address']);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function room()
    {
        $room = Auth::user()->bed->room ?? null;
        $bed = Auth::user()->bed ?? null;
        return view('student.room', compact('room', 'bed'));
    }

    public function fees()
    {
        $fees = Auth::user()->fees()->with('payments')->latest()->get();
        return view('student.fees', compact('fees'));
    }

    /**
     * Display pending visit requests for the student
     */
    // REMOVED: Student visitor approval (admin-only now)


    /**
     * Approve a specific visit request
     */
    // REMOVED: Student approve visit (admin-only now)

    /**
     * Reject a specific visit request
     */
    // REMOVED: Student reject visit (admin-only now)

    /**
     * Delete a visit request history record
     */
    // REMOVED: Student delete visit (admin-only now)

    /**
     * View activity logs
     */
    public function activityLogs()
    {
        $logs = \App\Models\AuditLog::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
        return view('student.activity-logs', compact('logs'));
    }

    /**
     * Clear student's own activity logs
     */
    public function clearActivityLogs()
    {
        \App\Models\AuditLog::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Your activity logs have been cleared.');
    }

    /**
     * List visit requests for this student
     */
    public function listVisitRequests()
    {
        $visitRequests = \App\Models\Visitor::where('student_id', Auth::id())
            ->where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $processedRequests = \App\Models\Visitor::where('student_id', Auth::id())
            ->whereIn('status', ['approved', 'rejected'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('student.visit-requests.index', compact('visitRequests', 'processedRequests'));
    }

    /**
     * Accept a visitor request and notify admin
     */
    public function acceptVisitRequest($id)
    {
        $visit = \App\Models\Visitor::where('student_id', Auth::id())->findOrFail($id);
        $visit->update(['status' => 'approved']);

        // Notify admin about student's acceptance
        app(\App\Services\NotificationService::class)->notifyAdminVisitorApprovedByStudent($visit->id, Auth::id());

        return back()->with('success', 'Visit request accepted. Admin has been notified.');
    }

    /**
     * Reject a visitor request
     */
    public function rejectVisitRequest($id)
    {
        $visit = \App\Models\Visitor::where('student_id', Auth::id())->findOrFail($id);
        $visit->update(['status' => 'rejected']);

        return back()->with('success', 'Visit request rejected.');
    }
}
