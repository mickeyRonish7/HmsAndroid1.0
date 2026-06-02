<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    
    // OTP-Based Password Reset Routes
    Route::get('forgot-password', [AuthController::class, 'showOtpRequestForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendOtp'])->name('password.otp.send');
    Route::get('verify-otp', [AuthController::class, 'showOtpVerifyForm'])->name('password.otp.verify.form');
    Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('password.otp.verify');
    Route::get('reset-password', [AuthController::class, 'showNewPasswordForm'])->name('password.otp.reset.form');
    Route::post('reset-password', [AuthController::class, 'resetPasswordWithOtp'])->name('password.otp.reset');
    });

Route::post('logout', [AuthController::class, 'logout'])->name('logout');
