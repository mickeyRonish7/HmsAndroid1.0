<?php

namespace App\Http\Controllers;

use App\Models\RegistrationForm;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class PublicFormController extends Controller
{
    public function showForm()
    {
        $form = RegistrationForm::active()->first();
        
        if (!$form) {
            // Get the latest form to display details even if closed
            $latestForm = RegistrationForm::latest()->first();
            return view('public.no-form-available', compact('latestForm'));
        }

        return view('public.registration-form', compact('form'));
    }

    public function submitForm(Request $request)
    {
        $form = RegistrationForm::active()->first();
        
        if (!$form) {
            return back()->withErrors(['error' => 'No active registration form available.']);
        }

        $validated = $request->validate([
            'student_number' => 'required|string|max:255',
            'student_id_no' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'semester' => 'required|string|max:50',
            'department' => 'required|string|max:255',
            'id_card_photo' => 'required|image|max:2048',
            'application_photos.*' => 'required|image|max:2048'
        ]);

        // Handle ID card photo upload
        if ($request->hasFile('id_card_photo')) {
            $validated['id_card_photo'] = $request->file('id_card_photo')
                                                  ->store('form-submissions/id-cards', 'public');
        }

        // Handle application photos upload
        $applicationPhotos = [];
        if ($request->hasFile('application_photos')) {
            foreach ($request->file('application_photos') as $photo) {
                $applicationPhotos[] = $photo->store('form-submissions/applications', 'public');
            }
        }
        $validated['application_photos'] = $applicationPhotos;

        $validated['registration_form_id'] = $form->id;
        $validated['status'] = 'pending';

        FormSubmission::create($validated);

        return redirect()->route('public.registration.success')
                        ->with('success', 'Your registration has been submitted successfully!');
    }

    public function success()
    {
        return view('public.registration-success');
    }
}
