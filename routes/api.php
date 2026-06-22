<?php

use App\Http\Controllers\Api\MobileAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile API Routes (Sanctum token-based auth)
|--------------------------------------------------------------------------
|
| All routes here are prefixed with /api (set in bootstrap/app.php).
| Protected routes require the Bearer token returned by POST /api/auth/login.
|
| Authentication flow for the mobile app:
|   1. POST /api/auth/login          → receive token
|   2. Send  Authorization: Bearer {token} on every authenticated request
|   3. POST /api/auth/logout         → revoke token
|
| Password reset flow:
|   1. POST /api/auth/password/send-otp   → OTP emailed to user
|   2. POST /api/auth/password/verify-otp → verify OTP, receive reset_token
|   3. POST /api/auth/password/reset      → supply reset_token + new password
|
*/

// ── Public endpoints (no token required) ─────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register',                [MobileAuthController::class, 'register']);
    Route::post('login',                   [MobileAuthController::class, 'login']);
    Route::post('password/send-otp',       [MobileAuthController::class, 'sendOtp']);
    Route::post('password/verify-otp',     [MobileAuthController::class, 'verifyOtp']);
});

// ── Protected endpoints (token required) ─────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout',             [MobileAuthController::class, 'logout']);
    Route::get('auth/me',                  [MobileAuthController::class, 'me']);
    Route::put('auth/password',            [MobileAuthController::class, 'updatePassword']);

    // Password reset — requires the special reset_token from verify-otp step
    Route::post('auth/password/reset',     [MobileAuthController::class, 'resetPassword']);
});
