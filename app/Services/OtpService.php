<?php

namespace App\Services;

use App\Models\Usuario;
use App\Mail\OtpResetPasswordMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpService
{
    /**
     * Generate and send OTP for password reset.
     *
     * @param string $email
     * @return void
     */
    public function sendOtp(string $email): void
    {
        $usuario = Usuario::where('email', $email)->first();

        if (!$usuario) {
            // No reveal if user exists or not, but for this task we validate it in Request.
            // If request validation passes, user exists.
            return;
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store in Cache for 10 minutes
        $key = 'otp_reset_' . $email;
        Cache::store('file')->put($key, $otp, now()->addMinutes(10));

        // Send Email
        Mail::to($email)->send(new OtpResetPasswordMail($usuario, $otp));
    }

    /**
     * Verify OTP and reset password.
     *
     * @param string $email
     * @param string $otp
     * @return bool
     */
    public function verifyAndResetPassword(string $email, string $otp): bool
    {
        $key = 'otp_reset_' . $email;
        $cachedOtp = Cache::store('file')->get($key);

        if (!$cachedOtp || $cachedOtp !== $otp) {
            return false;
        }

        // OTP is valid
        $usuario = Usuario::where('email', $email)->first();
        
        if ($usuario) {
            $usuario->pass = Hash::make('password123');
            $usuario->save();
            
            // Invalidate existing tokens if needed (optional but good practice)
            $usuario->tokens()->delete();
        }

        // Remove OTP from Cache
        Cache::store('file')->forget($key);

        return true;
    }
}
