<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordOtp;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class MobileAuthController extends Controller
{
    // ----------------------------------------------------------------
    // POST /api/auth/register
    // ----------------------------------------------------------------
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()],
            'phone'             => ['required', 'string', 'max:20'],
            'role'              => ['required', 'in:student,visitor'],
            // Student-specific
            'parent_phone'      => ['required_if:role,student', 'nullable', 'string', 'max:20'],
            'year'              => ['required_if:role,student', 'nullable', 'integer', 'between:1,4'],
            'department'        => ['required_if:role,student', 'nullable', 'string', 'max:100'],
            'semester'          => ['required_if:role,student', 'nullable', 'integer', 'between:1,8'],
            'address'           => ['required_if:role,student', 'nullable', 'string', 'max:255'],
            'student_id_number' => ['required_if:role,student', 'nullable', 'string', 'max:50', 'unique:users'],
        ]);

        $userData = [
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => $validated['role'],
            'phone'             => $validated['phone'],
            'parent_phone'      => $validated['role'] === 'student' ? ($validated['parent_phone'] ?? null) : null,
            'year'              => $validated['role'] === 'student' ? ($validated['year'] ?? null) : null,
            'department'        => $validated['role'] === 'student' ? ($validated['department'] ?? null) : null,
            'semester'          => $validated['role'] === 'student' ? ($validated['semester'] ?? null) : null,
            'address'           => $validated['address'] ?? null,
            'student_id_number' => $validated['role'] === 'student' ? ($validated['student_id_number'] ?? null) : null,
        ];

        if ($validated['role'] === 'visitor') {
            $userData['student_approved'] = false;
            $userData['admin_approved']   = false;
            $userData['is_approved']      = false;
        } else {
            $userData['is_approved'] = false;
        }

        $user = User::create($userData);

        if ($user->role === 'visitor') {
            app(NotificationService::class)->notifyNewVisitorRegistration($user->id);
        }

        return response()->json([
            'message' => 'Registration successful. Please wait for admin approval before logging in.',
        ], 201);
    }

    // ----------------------------------------------------------------
    // POST /api/auth/login
    // ----------------------------------------------------------------
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials do not match our records.',
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        // Approval checks
        if ($user->role === 'visitor' && ! $user->admin_approved) {
            Auth::logout();
            return response()->json([
                'message' => 'Your account is pending admin approval.',
            ], 403);
        }

        if ($user->role === 'student' && ! $user->is_approved) {
            Auth::logout();
            return response()->json([
                'message' => 'Your account is pending admin approval.',
            ], 403);
        }

        if ($user->is_active === false) {
            Auth::logout();
            return response()->json([
                'message' => 'Your account has been deactivated. Please contact the administrator.',
            ], 403);
        }

        AuditLogger::log('login', 'Auth', $user, 'Mobile login');

        // Revoke old mobile tokens and issue a fresh one
        $user->tokens()->where('name', 'mobile')->delete();
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token'   => $token,
            'user'    => $this->userResource($user),
        ]);
    }

    // ----------------------------------------------------------------
    // POST /api/auth/logout   (requires auth:sanctum)
    // ----------------------------------------------------------------
    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        AuditLogger::log('logout', 'Auth', $user, 'Mobile logout');

        // Revoke only the current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    // ----------------------------------------------------------------
    // GET /api/auth/me   (requires auth:sanctum)
    // ----------------------------------------------------------------
    public function me(Request $request): JsonResponse
    {
        return response()->json($this->userResource($request->user()));
    }

    // ----------------------------------------------------------------
    // OTP PASSWORD RESET — Step 1: send OTP
    // POST /api/auth/password/send-otp
    // ----------------------------------------------------------------
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            // Return generic message to prevent email enumeration
            return response()->json(['message' => 'If that email exists, an OTP has been sent.']);
        }

        $otpRecord = PasswordOtp::generateOtp($user->id);

        try {
            Mail::to($user->email)->send(
                new \App\Mail\SendOtpMail($otpRecord->otp_code, $user->name)
            );
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send OTP. Please try again later.'], 500);
        }

        return response()->json(['message' => 'OTP sent to your email address.']);
    }

    // ----------------------------------------------------------------
    // OTP PASSWORD RESET — Step 2: verify OTP
    // POST /api/auth/password/verify-otp
    // ----------------------------------------------------------------
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! PasswordOtp::verifyOtp($user->id, $request->otp)) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 422);
        }

        // Issue a short-lived reset token
        $resetToken = $user->createToken('password-reset', ['password-reset'], now()->addMinutes(15))->plainTextToken;

        return response()->json([
            'message'      => 'OTP verified.',
            'reset_token'  => $resetToken,
        ]);
    }

    // ----------------------------------------------------------------
    // OTP PASSWORD RESET — Step 3: reset password
    // POST /api/auth/password/reset   (requires auth:sanctum with ability password-reset)
    // ----------------------------------------------------------------
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (! $user->currentAccessToken()->can('password-reset')) {
            return response()->json(['message' => 'Invalid or expired reset token.'], 403);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Clean up OTPs and the reset token
        PasswordOtp::where('user_id', $user->id)->delete();
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Password reset successfully. You can now log in.']);
    }

    // ----------------------------------------------------------------
    // PUT /api/auth/password   (requires auth:sanctum — change own password)
    // ----------------------------------------------------------------
    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password updated successfully.']);
    }

    // ----------------------------------------------------------------
    // Private helpers
    // ----------------------------------------------------------------
    private function userResource(User $user): array
    {
        return [
            'id'                 => $user->id,
            'name'               => $user->name,
            'email'              => $user->email,
            'role'               => $user->role,
            'phone'              => $user->phone,
            'department'         => $user->department,
            'year'               => $user->year,
            'semester'           => $user->semester,
            'student_id_number'  => $user->student_id_number,
            'is_approved'        => $user->is_approved,
            'is_active'          => $user->is_active,
            'profile_photo_path' => $user->profile_photo_path,
            'blood_group'        => $user->blood_group,
            'bed_id'             => $user->bed_id,
            'locale'             => $user->locale,
            'theme'              => $user->theme,
        ];
    }
}
