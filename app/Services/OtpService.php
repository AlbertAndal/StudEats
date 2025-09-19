<?php

namespace App\Services;

use App\Models\EmailVerificationOtp;
use App\Models\User;
use App\Notifications\EmailVerificationOtpNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    /**
     * OTP expiration time in seconds (5 minutes).
     */
    const OTP_EXPIRATION_SECONDS = 300;

    /**
     * Maximum OTP attempts per email per hour.
     */
    const MAX_ATTEMPTS_PER_HOUR = 5;

    /**
     * Rate limit key prefix.
     */
    const RATE_LIMIT_PREFIX = 'otp_verification';

    /**
     * Generate a new OTP for email verification.
     */
    public function generateOtp(string $email, ?Request $request = null): EmailVerificationOtp
    {
        // Clean up expired OTPs first
        $this->cleanupExpiredOtps();

        // Invalidate any existing valid OTPs for this email
        $this->invalidateExistingOtps($email);

        // Generate 6-digit OTP
        $otpCode = $this->generateSecureOtpCode();

        // Create OTP record
        $otp = EmailVerificationOtp::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addSeconds(self::OTP_EXPIRATION_SECONDS),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);

        Log::info('OTP generated', [
            'email' => $email,
            'ip' => $request?->ip(),
            'expires_at' => $otp->expires_at,
        ]);

        return $otp;
    }

    /**
     * Verify an OTP code for a given email.
     */
    public function verifyOtp(string $email, string $otpCode): bool
    {
        $otp = EmailVerificationOtp::forEmail($email)
            ->where('otp_code', $otpCode)
            ->valid()
            ->latest()
            ->first();

        if (! $otp) {
            Log::warning('OTP verification failed - invalid or expired', [
                'email' => $email,
                'provided_code' => $otpCode,
            ]);

            return false;
        }

        // Mark OTP as used
        $otp->markAsUsed();

        Log::info('OTP verified successfully', [
            'email' => $email,
            'otp_id' => $otp->id,
        ]);

        return true;
    }

    /**
     * Send OTP via email notification.
     */
    public function sendOtpEmail(string $email, string $otpCode): void
    {
        // Try to find existing user or create a temporary notification recipient
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->notify(new EmailVerificationOtpNotification($otpCode));
        } else {
            // For non-existing users during registration
            \Illuminate\Support\Facades\Notification::route('mail', $email)
                ->notify(new EmailVerificationOtpNotification($otpCode));
        }

        Log::info('OTP email sent', ['email' => $email]);
    }

    /**
     * Check if email is rate limited for OTP requests.
     */
    public function isRateLimited(string $email): bool
    {
        $key = $this->getRateLimitKey($email);

        return RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS_PER_HOUR);
    }

    /**
     * Increment rate limit attempts for email.
     */
    public function incrementRateLimit(string $email): void
    {
        $key = $this->getRateLimitKey($email);
        RateLimiter::hit($key, 3600); // 1 hour
    }

    /**
     * Get remaining rate limit attempts.
     */
    public function getRemainingAttempts(string $email): int
    {
        $key = $this->getRateLimitKey($email);

        return RateLimiter::remaining($key, self::MAX_ATTEMPTS_PER_HOUR);
    }

    /**
     * Get seconds until rate limit resets.
     */
    public function getRateLimitResetTime(string $email): int
    {
        $key = $this->getRateLimitKey($email);

        return RateLimiter::availableIn($key);
    }

    /**
     * Clean up expired OTPs from database.
     */
    public function cleanupExpiredOtps(): int
    {
        $deletedCount = EmailVerificationOtp::cleanupExpired();

        if ($deletedCount > 0) {
            Log::info('Cleaned up expired OTPs', ['count' => $deletedCount]);
        }

        return $deletedCount;
    }

    /**
     * Invalidate existing valid OTPs for an email.
     */
    private function invalidateExistingOtps(string $email): void
    {
        EmailVerificationOtp::forEmail($email)
            ->valid()
            ->update(['is_used' => true]);
    }

    /**
     * Generate a secure 6-digit OTP code.
     */
    private function generateSecureOtpCode(): string
    {
        // Use cryptographically secure random number generation
        $min = 100000; // 6-digit minimum
        $max = 999999; // 6-digit maximum

        return (string) random_int($min, $max);
    }

    /**
     * Get rate limit key for email.
     */
    private function getRateLimitKey(string $email): string
    {
        return self::RATE_LIMIT_PREFIX.':'.hash('sha256', $email);
    }

    /**
     * Generate and send OTP in one operation.
     */
    public function generateAndSendOtp(string $email, ?Request $request = null): EmailVerificationOtp
    {
        // Check rate limiting
        if ($this->isRateLimited($email)) {
            throw new \Exception(
                'Too many OTP requests. Please wait '.
                $this->getRateLimitResetTime($email).
                ' seconds before requesting again.'
            );
        }

        // Generate OTP
        $otp = $this->generateOtp($email, $request);

        // Send email
        $this->sendOtpEmail($email, $otp->otp_code);

        // Increment rate limit
        $this->incrementRateLimit($email);

        return $otp;
    }
}
