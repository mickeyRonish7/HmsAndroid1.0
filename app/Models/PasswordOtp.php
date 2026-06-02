<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Generate a new 6-digit OTP for a user
     */
    public static function generateOtp($userId)
    {
        // Delete any existing OTPs for this user
        self::where('user_id', $userId)->delete();

        // Generate random 6-digit code
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create new OTP with 10-minute expiration
        return self::create([
            'user_id' => $userId,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
    }

    /**
     * Verify OTP code for a user
     */
    public static function verifyOtp($userId, $otpCode)
    {
        $otp = self::where('user_id', $userId)
                    ->where('otp_code', $otpCode)
                    ->first();

        if (!$otp) {
            return false;
        }

        if ($otp->isExpired()) {
            return false;
        }

        return true;
    }
}
