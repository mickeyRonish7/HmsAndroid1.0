<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationForm;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class RegistrationFormController extends Controller
{
    public function index()
    {
        $forms = RegistrationForm::withCount('submissions')->latest()->paginate(10);
        return view('admin.registration-forms.index', compact('forms'));
    }

    public function create()
    {
        return view('admin.registration-forms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'notice_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120'
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Handle notice photo upload
        if ($request->hasFile('notice_photo')) {
            $validated['notice_photo'] = $request->file('notice_photo')
                                                  ->store('forms/notices', 'public');
        }

        RegistrationForm::create($validated);

        return redirect()->route('admin.registration-forms.index')
                        ->with('success', 'Registration form created successfully!');
    }

    public function edit(RegistrationForm $registrationForm)
    {
        return view('admin.registration-forms.edit', compact('registrationForm'));
    }

    public function update(Request $request, RegistrationForm $registrationForm)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'notice_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120'
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Handle notice photo upload
        if ($request->hasFile('notice_photo')) {
            // Delete old photo if it exists
            if ($registrationForm->notice_photo && \Storage::disk('public')->exists($registrationForm->notice_photo)) {
                \Storage::disk('public')->delete($registrationForm->notice_photo);
            }
            
            $validated['notice_photo'] = $request->file('notice_photo')
                                                  ->store('forms/notices', 'public');
        }

        $registrationForm->update($validated);

        return redirect()->route('admin.registration-forms.index')
                        ->with('success', 'Registration form updated successfully!');
    }

    public function toggle(RegistrationForm $registrationForm)
    {
        $registrationForm->update(['is_active' => !$registrationForm->is_active]);
        
        $status = $registrationForm->is_active ? 'enabled' : 'disabled';
        return back()->with('success', "Registration form {$status} successfully!");
    }

    public function destroy(RegistrationForm $registrationForm)
    {
        // Delete notice photo if it exists
        if ($registrationForm->notice_photo && \Storage::disk('public')->exists($registrationForm->notice_photo)) {
            \Storage::disk('public')->delete($registrationForm->notice_photo);
        }
        
        $registrationForm->delete();
        return back()->with('success', 'Registration form deleted.');
    }

    public function submissions(RegistrationForm $registrationForm)
    {
        $submissions = $registrationForm->submissions()
                                       ->with('processedByUser')
                                       ->latest()
                                       ->paginate(20);
        
        return view('admin.registration-forms.submissions', compact('registrationForm', 'submissions'));
    }

    public function showSubmission(FormSubmission $submission)
    {
        $submission->load('registrationForm', 'processedByUser');
        return view('admin.registration-forms.show-submission', compact('submission'));
    }

    public function approveSubmission(FormSubmission $submission)
    {
        $submission->update([
            'status' => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]);

        return back()->with('success', 'Submission approved successfully!');
    }

    public function rejectSubmission(Request $request, FormSubmission $submission)
    {
        $submission->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]);

        return back()->with('success', 'Submission rejected.');
    }

    public function deleteSubmission(FormSubmission $submission)
    {
        // Delete uploaded files
        if ($submission->id_card_photo && \Storage::disk('public')->exists($submission->id_card_photo)) {
            \Storage::disk('public')->delete($submission->id_card_photo);
        }

        if ($submission->application_photos) {
            foreach ($submission->application_photos as $photo) {
                if (\Storage::disk('public')->exists($photo)) {
                    \Storage::disk('public')->delete($photo);
                }
            }
        }

        $submission->delete();
        return back()->with('success', 'Submission deleted.');
    }
}
