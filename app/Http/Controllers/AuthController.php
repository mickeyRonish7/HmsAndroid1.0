<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // Registration
    public function showRegisterForm()
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        session(['captcha_answer' => $num1 + $num2]);
        return view('auth.register', ['captcha_question' => "$num1 + $num2"]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', 'in:student,visitor'],
            // Student specific fields
            'parent_phone' => ['required_if:role,student', 'nullable', 'string', 'max:20'],
            'year' => ['required_if:role,student', 'nullable', 'integer', 'between:1,4'],
            'department' => ['required_if:role,student', 'nullable', 'string', 'max:100'],
            'semester' => ['required_if:role,student', 'nullable', 'integer', 'between:1,8'],
            'address' => ['required_if:role,student', 'nullable', 'string', 'max:255'],
            'student_id_number' => ['required_if:role,student', 'nullable', 'string', 'max:50', 'unique:users'],
            'captcha' => ['required', 'integer'],
        ]);

        if ($request->captcha != session('captcha_answer')) {
            return back()->withErrors(['captcha' => 'Incorrect CAPTCHA answer.'])->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'parent_phone' => $request->role === 'student' ? $request->parent_phone : null,
            'year' => $request->role === 'student' ? $request->year : null,
            'department' => $request->role === 'student' ? $request->department : null,
            'semester' => $request->role === 'student' ? $request->semester : null,
            'address' => $request->address,
            'student_id_number' => $request->role === 'student' ? $request->student_id_number : null,
        ];

        if ($request->role === 'visitor') {
            // Visitors need dual approval
            $userData['student_approved'] = false;
            $userData['admin_approved'] = false;
            $userData['is_approved'] = false;
        } else {
            // Students use traditional approval
            $userData['is_approved'] = false;
        }

        $user = User::create($userData);

        if ($user->role === 'visitor') {
            app(\App\Services\NotificationService::class)->notifyNewVisitorRegistration($user->id);
        }

        // Auto-login or redirect with message?
        // User requested: "register requste go to database and admin to aprove ... then student login"
        // So DO NOT auto-login.

        return redirect()->route('login')->with('success', 'Registration successful! Please wait for Admin approval before logging in.');
    }

    // Login
    public function showLoginForm()
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        session(['captcha_answer' => $num1 + $num2]);
        return view('auth.login', ['captcha_question' => "$num1 + $num2"]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'captcha' => ['required', 'integer'],
        ]);

        if ($request->captcha != session('captcha_answer')) {
            return back()->withErrors(['captcha' => 'Incorrect CAPTCHA answer.'])->withInput();
        }

        // Remove captcha from credentials before auth attempt
        unset($credentials['captcha']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check approval mechanism
            if ($user->role === 'visitor') {
                // Visitors need ONLY admin approval
                if (!$user->admin_approved) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Your account is pending approval from: admin.',
                    ]);
                }
            } elseif ($user->role === 'student' && !$user->is_approved) {
                // Students need admin approval
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval by the Administrator.',
                ]);
            }

            // Audit Log
            \App\Services\AuditLogger::log('login', 'Auth', $user, 'User logged in');

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            \App\Services\AuditLogger::log('logout', 'Auth', Auth::user(), 'User logged out');
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ============================================================
    // OTP-BASED PASSWORD RESET
    // ============================================================

    /**
     * Show the form to request OTP (Forgot Password)
     */
    public function showOtpRequestForm()
    {
        return view('auth.forgot-password-otp');
    }

    /**
     * Send OTP to user's email
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => "We can't find a user with that email address."]);
        }

        // Generate OTP
        $otpRecord = \App\Models\PasswordOtp::generateOtp($user->id);

        // Send OTP via email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(
                new \App\Mail\SendOtpMail($otpRecord->otp_code, $user->name)
            );

            // Store email in session for next step
            session(['otp_email' => $user->email]);

            return redirect()->route('password.otp.verify.form')
                           ->with('success', 'OTP has been sent to your email address.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again later.']);
        }
    }

    /**
     * Show the form to enter OTP
     */
    public function showOtpVerifyForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('password.otp.request')->withErrors(['email' => 'Session expired. Please start again.']);
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify OTP and show password reset form
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('password.otp.request')->withErrors(['otp' => 'Session expired. Please start again.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.otp.request')->withErrors(['otp' => 'Invalid session.']);
        }

        // Verify OTP
        if (!\App\Models\PasswordOtp::verifyOtp($user->id, $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        // OTP is valid, store verification in session
        session(['otp_verified' => true, 'otp_user_id' => $user->id]);

        return redirect()->route('password.otp.reset.form')->with('success', 'OTP verified! Please set your new password.');
    }

    /**
     * Show new password form after OTP verification
     */
    public function showNewPasswordForm()
    {
        if (!session('otp_verified') || !session('otp_user_id')) {
            return redirect()->route('password.otp.request')->withErrors(['email' => 'Please verify OTP first.']);
        }

        return view('auth.reset-password-otp');
    }

    /**
     * Reset password after OTP verification
     */
    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        if (!session('otp_verified') || !session('otp_user_id')) {
            return redirect()->route('password.otp.request')->withErrors(['email' => 'Session expired. Please start again.']);
        }

        $user = User::find(session('otp_user_id'));
        if (!$user) {
            return redirect()->route('password.otp.request')->withErrors(['email' => 'User not found.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete used OTP
        \App\Models\PasswordOtp::where('user_id', $user->id)->delete();

        // Clear session
        session()->forget(['otp_email', 'otp_verified', 'otp_user_id']);

        return redirect()->route('login')->with('success', 'Password reset successful! You can now log in with your new password.');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
