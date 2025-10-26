<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationOtp;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class EmailVerificationController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show the OTP verification form.
     */
    public function showVerification(Request $request)
    {
        $email = $request->session()->get('pending_verification_email') ?? 
                $request->query('email') ?? 
                (Auth::check() ? Auth::user()->email : null);

        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'No email address found for verification.');
        }

        // Get rate limit information for display
        $remainingAttempts = $this->otpService->getRemainingAttempts($email);
        $rateLimitResetTime = $this->otpService->isRateLimited($email) 
            ? $this->otpService->getRateLimitResetTime($email) 
            : 0;

        return view('auth.verify-otp', [
            'email' => $email,
            'remainingAttempts' => $remainingAttempts,
            'rateLimitResetTime' => $rateLimitResetTime,
            'maxAttempts' => OtpService::MAX_ATTEMPTS_PER_HOUR,
        ]);
    }

    /**
     * Verify the submitted OTP code.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ], [
            'otp_code.size' => 'The verification code must be exactly 6 digits.',
            'otp_code.regex' => 'The verification code must contain only numbers.',
        ]);

        $email = $request->input('email');
        $otpCode = $request->input('otp_code');

        // Rate limiting for verification attempts
        $verificationKey = 'verify-otp:' . hash('sha256', $email);
        if (RateLimiter::tooManyAttempts($verificationKey, 10)) { // 10 attempts per hour
            $seconds = RateLimiter::availableIn($verificationKey);
            throw ValidationException::withMessages([
                'otp_code' => ["Too many verification attempts. Please try again in " . ceil($seconds / 60) . " minutes."],
            ]);
        }

        // Check if user is rate limited for new OTP requests
        if ($this->otpService->isRateLimited($email)) {
            $resetTime = $this->otpService->getRateLimitResetTime($email);
            return back()->withErrors([
                'otp_code' => 'Too many verification requests. Please wait ' . ceil($resetTime / 60) . ' minutes before trying again.'
            ])->withInput();
        }

        try {
            // For direct OTP verification, we need to find the OTP record by code
            // This is a simplified approach - in production you might want to use a different method
            $otp = EmailVerificationOtp::where('email', $email)
                ->where('otp_code', $otpCode)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->first();

            if (!$otp) {
                RateLimiter::hit($verificationKey, 3600); // 1 hour
                
                return back()->withErrors([
                    'otp_code' => 'Invalid or expired verification code. Please check your email for the latest code.'
                ])->withInput();
            }

            // Use transaction for atomicity
            \Illuminate\Support\Facades\DB::transaction(function () use ($otp, $email) {
                // Mark OTP as used
                $otp->update([
                    'is_used' => true,
                    'used_at' => now(),
                ]);

                // Verify user's email
                $user = \App\Models\User::where('email', $email)->first();
                if ($user && is_null($user->email_verified_at)) {
                    $user->update(['email_verified_at' => now()]);
                }
            });

            // Clear rate limiting on successful verification
            RateLimiter::clear($verificationKey);

            // Clear pending verification from session
            $request->session()->forget('pending_verification_email');

            Log::info('Email verified successfully via OTP', [
                'email' => $email,
                'otp_id' => $otp->id,
            ]);

            // Redirect to dashboard if user is logged in, otherwise to login
            if (Auth::check()) {
                return redirect()->route('dashboard')
                    ->with('success', 'Your email has been verified successfully!');
            } else {
                return redirect()->route('login')
                    ->with('success', 'Your email has been verified! Please log in to continue.');
            }

        } catch (\Exception $e) {
            Log::error('Error verifying OTP', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'otp_code' => 'An error occurred during verification. Please try again.'
            ])->withInput();
        }
    }

    /**
     * Resend OTP verification code.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $request->input('email');

        try {
            // Generate and send new OTP
            $this->otpService->generateAndSendVerificationLink($email, $request);

            Log::info('OTP resent successfully', ['email' => $email]);

            return back()->with('success', 'A new verification code has been sent to your email.');

        } catch (\Exception $e) {
            Log::error('Error resending OTP', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            if (str_contains($e->getMessage(), 'Too many verification requests')) {
                return back()->withErrors([
                    'email' => $e->getMessage()
                ]);
            }

            return back()->withErrors([
                'email' => 'Unable to send verification code. Please try again later.'
            ]);
        }
    }

    /**
     * Verify email via magic link (existing functionality).
     */
    public function verifyEmail(Request $request, string $token): RedirectResponse
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'Invalid verification link.');
        }

        $result = $this->otpService->verifyToken($email, $token);

        if ($result['success']) {
            // Handle already verified case
            if (isset($result['already_verified']) && $result['already_verified']) {
                return redirect()->route('dashboard')
                    ->with('info', $result['message']);
            }

            // Clear pending verification from session
            $request->session()->forget('pending_verification_email');

            // Log the user in if they're not already authenticated
            if (!Auth::check()) {
                $user = \App\Models\User::where('email', $email)->first();
                if ($user) {
                    Auth::login($user);
                }
            }

            return redirect()->route('dashboard')
                ->with('success', $result['message']);
        } else {
            return redirect()->route('email.verify.form')
                ->with('email', $email)
                ->with('error', $result['message']);
        }
    }
}