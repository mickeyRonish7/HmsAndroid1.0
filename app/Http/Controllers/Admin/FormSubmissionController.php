<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FormSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FormSubmission::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by registration form
        if ($request->has('form_id') && $request->form_id !== '') {
            $query->where('registration_form_id', $request->form_id);
        }

        // Search by student name or email
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        $submissions = $query->with('registrationForm')
                            ->latest()
                            ->paginate(15);

        return view('admin.form-submissions.index', compact('submissions'));
    }

    /**
     * Display the specified resource.
     */
    public function show(FormSubmission $formSubmission)
    {
        return view('admin.form-submissions.show', compact('formSubmission'));
    }

    /**
     * Update the status of form submission.
     */
    public function updateStatus(Request $request, FormSubmission $formSubmission)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $formSubmission->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]);

        return redirect()->back()->with('success', 'Form submission status updated successfully!');
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(FormSubmission $formSubmission)
    {
        $formSubmission->delete();
        return redirect()->route('admin.form-submissions.index')->with('success', 'Form submission deleted successfully!');
    }
}
