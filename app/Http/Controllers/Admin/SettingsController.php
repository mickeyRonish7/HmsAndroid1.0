<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Show admin settings page
     */
    public function index()
    {
        $admin = Auth::user();
        return view('admin.settings.index', compact('admin'));
    }

    /**
     * Show edit settings form
     */
    public function edit()
    {
        $admin = Auth::user();
        return view('admin.settings.edit', compact('admin'));
    }

    /**
     * Update admin settings
     */
    public function update(Request $request)
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $admin->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'department' => ['nullable', 'string', 'max:255'],
            'profile_photo_path' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo_path')) {
            // Delete old photo if exists
            if ($admin->profile_photo_path) {
                Storage::disk('public')->delete($admin->profile_photo_path);
            }
            
            $path = $request->file('profile_photo_path')->store('profiles', 'public');
            $validated['profile_photo_path'] = $path;
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        // Log activity
        \App\Services\AuditLogger::log('admin_settings_update', 'Admin Settings', $admin, ['name' => $admin->name, 'email' => $admin->email]);

        return redirect()->route('admin.settings')->with('success', __('Settings updated successfully!'));
    }
}
