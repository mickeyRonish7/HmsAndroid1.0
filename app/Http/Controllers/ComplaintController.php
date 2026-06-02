<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComplaintRequest;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // Admin & Student: List complaints (Role specific)
    public function index()
    {
        if (Auth::user()->role === 'student') {
            $complaints = Complaint::where('student_id', Auth::id())->latest()->get();
            // Assuming student views are organized, if not, we can reuse or create specific view.
            if (view()->exists('student.complaints.index')) {
                return view('student.complaints.index', compact('complaints'));
            }
            // Fallback if no specific index implementation for student (though route implies there is)
             return view('student.complaints.index', compact('complaints')); // Force it, user might need to create it.
        }

        $complaints = Complaint::with('student')->latest()->get();
        return view('admin.complaints.index', compact('complaints'));
    }

    // Student: Show create form
    public function create()
    {
        return view('student.complaints.create');
    }

    // Student: Store complaint
    public function store(StoreComplaintRequest $request)
    {
        // Validation and role check are handled in StoreComplaintRequest
        
        $complaint = Complaint::create([
            'student_id' => Auth::id(),
            'category' => $request->category,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        app(\App\Services\NotificationService::class)->notifyNewComplaint($complaint->id, Auth::id());

        \App\Services\AuditLogger::log('create_complaint', 'Complaint', $complaint, 'Student submitted a complaint');

        return redirect()->route('student.dashboard')->with('success', 'Complaint submitted successfully.');
    }

    // Admin: Update status
    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved,rejected',
            'admin_remark' => 'nullable|string',
        ]);

        $complaint->update($request->only('status', 'admin_remark'));

        app(\App\Services\NotificationService::class)->notifyComplaintStatus(
            $complaint->student_id,
            $complaint->id,
            $request->status
        );

        \App\Services\AuditLogger::log('update_complaint', 'Complaint', $complaint, ['status' => $request->status, 'remark' => $request->admin_remark]);

        return back()->with('success', 'Complaint updated successfully.');
    }
}
