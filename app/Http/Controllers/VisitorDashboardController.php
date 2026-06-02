<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\User;
use App\Http\Requests\StoreVisitRequest;
use Illuminate\Support\Facades\Auth;

class VisitorDashboardController extends Controller
{
    public function dashboard()
    {
        // Get visits requested by this phone number or user
        // Since we have Auth, we can use the user's phone or name?
        // But the 'visitors' table stores 'phone' and 'visitor_name'. 
        // Ideally we should have 'user_id' in visitors table if they are registered.
        // But for now, let's filter by phone number of the logged-in visitor.
        
        $user = Auth::user();
        $visits = Visitor::where('phone', $user->phone)->latest()->get();
        $students = User::where('role', 'student')->get(); // For the dropdown

        return view('visitor.dashboard', compact('visits', 'students'));
    }

    public function requestVisit(StoreVisitRequest $request)
    {
        // Validation and role check are handled in StoreVisitRequest
        
        $user = Auth::user();

        $visit = Visitor::create([
            'user_id' => $user->id, // Link to the registered visitor account
            'student_id' => $request->student_id,
            'visitor_name' => $user->name,
            'phone' => $user->phone,
            'purpose' => $request->purpose,
            'entry_time' => null, 
            'status' => 'pending',
            'visit_date' => $request->date,
        ]);

        // Notification to student removed - admin-only approval now in place
        // app(\App\Services\NotificationService::class)->notifyNewVisitRequest($visit->id, $request->student_id);

        return back()->with('success', 'Visit request submitted successfully. Admin approval pending.');
    }

    public function visitorPass()
    {
        $user = Auth::user();
        $visits = Visitor::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('student')
            ->latest()
            ->get();

        return view('visitor.pass', compact('visits'));
    }

    public function showPass($id)
    {
        $visit = Visitor::findOrFail($id);
        
        // Ensure only the visitor can view their own pass
        if ($visit->user_id !== Auth::id() || $visit->status !== 'approved') {
            abort(403, 'Unauthorized');
        }

        $visit->load(['student', 'user']);

        return view('visitor.show-pass', compact('visit'));
    }

    public function downloadPass($id)
    {
        $visit = Visitor::findOrFail($id);
        
        // Ensure only the visitor can download their own pass
        if ($visit->user_id !== Auth::id() || $visit->status !== 'approved') {
            abort(403, 'Unauthorized');
        }

        $visit->load(['student', 'user']);

        $pdf = \PDF::loadView('visitor.pass-pdf', compact('visit'));
        return $pdf->download('visitor-pass-' . $visit->id . '.pdf');
    }

    public function verifyPass($passId)
    {
        // For accessing pass verification via QR scan
        // Extract ID from pass_id format: PASS-00001
        $id = (int) substr($passId, 5);
        $visit = Visitor::findOrFail($id);

        $visit->load(['student', 'user']);

        return view('visitor.verify-pass', compact('visit'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('visitor.profile', compact('user'));
    }

    public function cardProfile()
    {
        return view('visitor.card-profile');
    }

    public function uploadProfilePhoto(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
            ], [
                'profile_photo.required' => 'Please select a photo to upload',
                'profile_photo.image' => 'The file must be an image',
                'profile_photo.mimes' => 'Only JPEG, PNG, JPG, and GIF formats are allowed',
                'profile_photo.max' => 'File size must not exceed 5MB'
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Delete old photo if exists
            if ($user->profile_photo_path) {
                $oldPhotoPath = storage_path('app/public/' . $user->profile_photo_path);
                if (file_exists($oldPhotoPath)) {
                    @unlink($oldPhotoPath);
                }
            }

            // Ensure storage directory exists
            $storageDir = storage_path('app/public/profile-photos');
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            // Upload new photo using store() - Let Laravel handle filename
            $file = $request->file('profile_photo');
            $path = $file->store('profile-photos', 'public');

            if (!$path) {
                throw new \Exception('Failed to store file');
            }

            // Log the action
            \Log::info('Profile photo upload - Before update', [
                'user_id' => $user->id,
                'path' => $path,
                'user_data' => $user->toArray()
            ]);

            // Update user - refresh from DB first
            $user->refresh();
            $user->profile_photo_path = $path;
            $saveResult = $user->save();

            if (!$saveResult) {
                throw new \Exception('Failed to save user profile photo path to database');
            }

            // Log the success
            \Log::info('Profile photo upload - After update', [
                'user_id' => $user->id,
                'path' => $user->profile_photo_path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile photo uploaded successfully!',
                'path' => $path
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . ($e->errors()['profile_photo'][0] ?? 'Invalid file')
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Profile photo upload error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'file' => $request->file('profile_photo') ? $request->file('profile_photo')->getClientOriginalName() : 'none',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error uploading photo: ' . $e->getMessage()
            ], 400);
        }
    }
}
