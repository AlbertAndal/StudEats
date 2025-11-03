<?php

namespace App\Services;

use App\Models\EmailVerificationOtp;
use App\Models\User;
use App\Notifications\EmailVerificationOtpNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    const OTP_EXPIRATION_SECONDS = 300;
    const MAX_ATTEMPTS_PER_HOUR = 5;
    const RATE_LIMIT_PREFIX = 'email_verification';

    public function generateOtp(string $email, ?Request $request = null): EmailVerificationOtp
    {
        $this->cleanupExpiredOtps();
        $this->invalidateExistingOtps($email);

        $otpCode = $this->generateSecureOtpCode();
        $verificationToken = $this->generateVerificationToken();

        $otp = EmailVerificationOtp::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'verification_token' => $verificationToken,
            'expires_at' => Carbon::now()->addSeconds(self::OTP_EXPIRATION_SECONDS),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);

        Log::info('Verification code generated', [
            'email' => $email,
            'expires_at' => $otp->expires_at,
        ]);

        return $otp;
    }

    public function verifyToken(string $email, string $token): array
    {
        $tokenVariations = [
            $token,
            urldecode($token),
            trim($token),
            str_replace(' ', '+', $token),
        ];

        $otp = null;
        foreach ($tokenVariations as $variation) {
            $otp = EmailVerificationOtp::findByVerificationToken($email, $variation);
            if ($otp) break;
        }

        if (!$otp) {
            return ['success' => false, 'message' => 'Invalid verification link.'];
        }

        if ($otp->is_used) {
            $user = User::where('email', $email)->first();
            if ($user && !is_null($user->email_verified_at)) {
                return [
                    'success' => true,
                    'message' => 'Your email is already verified.',
                    'already_verified' => true,
                ];
            }
            return ['success' => false, 'message' => 'This verification link has already been used.'];
        }

        if ($otp->isExpired()) {
            return ['success' => false, 'message' => 'Your verification link has expired.'];
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($otp, $email) {
            $otp->update(['is_used' => true, 'used_at' => now()]);
            
            $user = User::where('email', $email)->first();
            if ($user && is_null($user->email_verified_at)) {
                $user->update(['email_verified_at' => now()]);
            }
        });

        return ['success' => true, 'message' => 'Email verified successfully.'];
    }

    public function sendVerificationEmail(string $email, string $otpCode, string $verificationToken): void
    {
        try {
            // Queue the email for background processing
            $verificationUrl = route('email.verify.token', [
                'token' => $verificationToken,
                'email' => $email
            ]);

            $emailHtml = $this->generateEmailHtml($otpCode, $verificationUrl, $email);

            // Use queue to send email asynchronously
            Mail::queue([], [], function ($message) use ($email, $otpCode, $emailHtml) {
                $message->to($email)
                    ->subject('StudEats - Your Verification Code: ' . $otpCode)
                    ->html($emailHtml);
            });

            Log::info('Verification email queued successfully', [
                'email' => $email,
                'otp_code' => $otpCode,
                'method' => 'queued',
                'queue' => config('queue.default'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue verification email', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Log the OTP code as fallback for development
            Log::info('OTP Code (email failed) - Use this to verify manually', [
                'email' => $email,
                'otp_code' => $otpCode,
                'verification_url' => route('email.verify.token', ['token' => $verificationToken, 'email' => $email]),
            ]);
            
            throw $e;
        }
    }

    /**
     * Generate HTML email content for OTP verification.
     */
    private function generateEmailHtml(string $otpCode, string $verificationUrl, string $email): string
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <title>StudEats Email Verification</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #059669, #10b981); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
                .otp-code { background: #059669; color: white; font-size: 32px; font-weight: bold; padding: 20px; text-align: center; border-radius: 8px; margin: 20px 0; letter-spacing: 5px; }
                .button { display: inline-block; background: #059669; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; font-size: 14px; color: #666; }
                .warning { background: #fef3c7; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #f59e0b; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>🍽️ StudEats</h1>
                <h2>Email Verification</h2>
            </div>
            <div class='content'>
                <p>Hello!</p>
                <p>Thank you for registering with <strong>StudEats</strong>. To complete your registration and start planning your meals, please verify your email address.</p>
                
                <h3>Your Verification Code:</h3>
                <div class='otp-code'>{$otpCode}</div>
                
                <p>Enter this 6-digit code on the verification page, or click the button below to verify automatically:</p>
                
                <p style='text-align: center;'>
                    <a href='{$verificationUrl}' class='button'>Verify Email Address</a>
                </p>
                
                <div class='warning'>
                    <strong>⚠️ Important:</strong>
                    <ul>
                        <li>This code expires in <strong>5 minutes</strong></li>
                        <li>Do not share this code with anyone</li>
                        <li>If you didn't create this account, please ignore this email</li>
                    </ul>
                </div>
                
                <p>Having trouble? Contact our support team or check your spam folder.</p>
                
                <p>Happy meal planning!<br>
                <strong>The StudEats Team</strong></p>
            </div>
            <div class='footer'>
                <p>This email was sent to {$email}</p>
                <p>© 2025 StudEats. All rights reserved.</p>
            </div>
        </body>
        </html>
        ";
    }

    public function isRateLimited(string $email): bool
    {
        $key = $this->getRateLimitKey($email);
        return RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS_PER_HOUR);
    }

    public function incrementRateLimit(string $email): void
    {
        $key = $this->getRateLimitKey($email);
        RateLimiter::hit($key, 3600);
    }

    public function getRemainingAttempts(string $email): int
    {
        $key = $this->getRateLimitKey($email);
        return RateLimiter::remaining($key, self::MAX_ATTEMPTS_PER_HOUR);
    }

    public function getRateLimitResetTime(string $email): int
    {
        $key = $this->getRateLimitKey($email);
        return RateLimiter::availableIn($key);
    }

    public function cleanupExpiredOtps(): int
    {
        $deletedCount = EmailVerificationOtp::cleanupExpired();
        if ($deletedCount > 0) {
            Log::info('Cleaned up expired verification codes', ['count' => $deletedCount]);
        }
        return $deletedCount;
    }

    private function invalidateExistingOtps(string $email): void
    {
        EmailVerificationOtp::forEmail($email)->valid()->update(['is_used' => true]);
    }

    private function generateSecureOtpCode(): string
    {
        return (string) random_int(100000, 999999);
    }

    private function generateVerificationToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    private function getRateLimitKey(string $email): string
    {
        return self::RATE_LIMIT_PREFIX.':'.hash('sha256', $email);
    }

    public function generateAndSendVerificationLink(string $email, ?Request $request = null): EmailVerificationOtp
    {
        if ($this->isRateLimited($email)) {
            throw new \Exception(
                'Too many verification requests. Please wait '.
                $this->getRateLimitResetTime($email).
                ' seconds before requesting again.'
            );
        }

        $otp = $this->generateOtp($email, $request);
        $this->sendVerificationEmail($email, $otp->otp_code, $otp->verification_token);
        $this->incrementRateLimit($email);

        return $otp;
    }
}