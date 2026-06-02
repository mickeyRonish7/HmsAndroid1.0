# OTP-Based Password Reset - Setup & Configuration Guide

## 📋 Overview
This document contains all the information needed to set up and use the OTP-based password reset feature in your Smart HMS Laravel application.

## ✅ Files Created

### 1. Database
- **Migration**: `database/migrations/2026_01_06_000001_create_password_otps_table.php`
- **Model**: `app/Models/PasswordOtp.php`

### 2. Mail System
- **Mail Class**: `app/Mail/SendOtpMail.php`
- **Email Template**: `resources/views/emails/otp.blade.php`

### 3. Controllers
- **Updated**: `app/Http/Controllers/AuthController.php` (added OTP methods)

### 4. Routes
- **Updated**: `routes/auth.php` (replaced old password reset routes)

### 5. Views
- `resources/views/auth/forgot-password-otp.blade.php` - Request OTP form
- `resources/views/auth/verify-otp.blade.php` - Enter OTP form
- `resources/views/auth/reset-password-otp.blade.php` - New password form

## 🔧 Configuration Steps

### Step 1: Run Database Migration
```bash
php artisan migrate
```

This will create the `password_otps` table with the following structure:
- `id` - Primary key
- `user_id` - Foreign key to users table
- `otp_code` - 6-digit OTP code
- `expires_at` - Expiration timestamp (10 minutes from creation)
- `created_at`, `updated_at` - Timestamps

### Step 2: Configure Email Settings
Update your `.env` file with your email configuration:

#### Using Gmail (Recommended for Testing)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Smart HMS"
```

**Important for Gmail:**
1. Enable 2-Factor Authentication on your Google account
2. Generate an App Password: https://myaccount.google.com/apppasswords
3. Use the App Password (not your regular Gmail password) in `MAIL_PASSWORD`

#### Using Mailtrap (Recommended for Development)
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@smarthms.local
MAIL_FROM_NAME="Smart HMS"
```

**To get Mailtrap credentials:**
1. Sign up at https://mailtrap.io
2. Go to Email Testing → Inboxes
3. Copy the SMTP credentials

#### Using Other SMTP Providers
You can use any SMTP provider (SendGrid, Mailgun, Amazon SES, etc.). Just update the credentials accordingly.

### Step 3: Test Email Configuration
```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email from Smart HMS', function($message) {
    $message->to('test@example.com')->subject('Test Email');
});
```

If it returns without errors, your email is configured correctly!

## 🚀 How It Works

### User Flow:
1. **User clicks "Forgot Password"** on login page
2. **Enters email address** on forgot password form
3. **Receives 6-digit OTP** via email (valid for 10 minutes)
4. **Enters OTP** on verification form
5. **Sets new password** after OTP verification
6. **Redirects to login** with new password

### Technical Flow:
1. `showOtpRequestForm()` - Shows email form
2. `sendOtp()` - Validates email, generates OTP, sends email
3. `showOtpVerifyForm()` - Shows OTP input form
4. `verifyOtp()` - Validates OTP and expiration
5. `showNewPasswordForm()` - Shows password reset form
6. `resetPasswordWithOtp()` - Updates password, clears OTP

### Security Features:
- ✅ OTP expires after 10 minutes
- ✅ Only one active OTP per user (old ones deleted)
- ✅ OTP validation checks user_id and code
- ✅ Session-based flow prevents direct access
- ✅ Password hashing with Laravel's Hash facade
- ✅ Database cascading delete when user is removed

## 🎨 Features Included

### Forgot Password Form
- Clean, modern UI matching your app design
- Email validation
- Success/error message display
- "How it works" information box
- Back to login link

### OTP Verification Form
- 6 individual input boxes for OTP digits
- Auto-focus and auto-advance between inputs
- Paste support (paste 6-digit code anywhere)
- 10-minute countdown timer
- Visual expiry warning
- Resend OTP link

### Password Reset Form
- Password strength indicator (Weak/Fair/Good/Strong)
- Toggle password visibility
- Password requirements display
- Confirm password field
- Real-time strength checking

## 📧 Email Template Features
- Professional responsive design
- Prominent OTP display
- Security warnings
- Expiry notice
- Brand-consistent styling
- Mobile-friendly

## 🧪 Testing the Feature

### Test Steps:
1. Navigate to your login page: `http://your-app-url/login`
2. Click "Forgot password?" link
3. Enter a valid user email from your database
4. Check your email inbox (or Mailtrap) for the OTP
5. Enter the 6-digit OTP on the verification page
6. Set a new password (must be at least 8 characters)
7. Login with the new password

### Test Scenarios:
- ✅ Valid email → OTP sent successfully
- ✅ Invalid email → Error message displayed
- ✅ Correct OTP → Verification successful
- ✅ Incorrect OTP → Error message
- ✅ Expired OTP (after 10 min) → Error message
- ✅ Password mismatch → Validation error
- ✅ Weak password → Strength indicator shows "Weak"

## 🔍 Troubleshooting

### Email Not Sending?
1. Check `.env` configuration
2. Clear config cache: `php artisan config:clear`
3. Check mail logs: `storage/logs/laravel.log`
4. Test with Mailtrap instead of Gmail
5. Ensure firewall allows SMTP port (587 or 465)

### OTP Not Validating?
1. Check database - is OTP created in `password_otps` table?
2. Verify time is not expired
3. Check session configuration
4. Clear browser cache and cookies

### Session Expired Errors?
1. Check session driver in `.env`: `SESSION_DRIVER=file`
2. Ensure `storage/framework/sessions` is writable
3. Clear session: `php artisan session:clear`

### Migration Errors?
1. Check if table already exists: `php artisan migrate:status`
2. Rollback if needed: `php artisan migrate:rollback --step=1`
3. Re-run: `php artisan migrate`

## 📝 Customization Options

### Change OTP Expiry Time
Edit `app/Models/PasswordOtp.php`, line 46:
```php
'expires_at' => Carbon::now()->addMinutes(10), // Change 10 to your desired minutes
```

Also update timer in `resources/views/auth/verify-otp.blade.php`, line 140:
```javascript
let timeLeft = 600; // Change 600 to (minutes * 60)
```

### Change OTP Length
Edit `app/Models/PasswordOtp.php`, line 43:
```php
$otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT); // Change 6 and 999999
```

Update database migration to change column size.

### Customize Email Design
Edit `resources/views/emails/otp.blade.php` to match your brand colors and styling.

## 🛡️ Security Recommendations

1. **Use HTTPS** in production to protect OTP in transit
2. **Rate limit** forgot password requests (prevent abuse)
3. **Log** password reset attempts for security auditing
4. **Consider** adding CAPTCHA on forgot password form
5. **Monitor** for suspicious patterns (multiple failed OTP attempts)

## 📊 Database Cleanup

Old, expired OTPs are automatically deleted when:
- A new OTP is generated for the same user
- User successfully resets password

For additional cleanup, you can create a scheduled task:
```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        \App\Models\PasswordOtp::where('expires_at', '<', now())->delete();
    })->daily();
}
```

## 📞 Support

If you encounter any issues:
1. Check the troubleshooting section above
2. Review Laravel logs: `storage/logs/laravel.log`
3. Verify all configuration steps were completed
4. Test email sending separately

---

**Created for Smart HMS - Hostel Management System**
*Last updated: 2026-01-06*
