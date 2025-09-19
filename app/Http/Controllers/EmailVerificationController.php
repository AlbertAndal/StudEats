<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\EmailVerificationOtp;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    public function __construct(
        private readonly OtpService $otpService
    ) {}

    /**
     * Show the email verification form.
     */
    public function show()
    {
        // Check if user has pending verification
        $userId = session('pending_verification_user_id');
        if (! $userId) {
            return redirect()->route('login')
                ->with('error', 'No pending email verification found.');
        }

        $user = User::find($userId);
        if (! $user) {
            session()->forget('pending_verification_user_id');

            return redirect()->route('login')
                ->with('error', 'Invalid verification session.');
        }

        // If already verified, redirect to dashboard
        if (! is_null($user->email_verified_at)) {
            session()->forget('pending_verification_user_id');
            Auth::login($user);

            return redirect()->route('dashboard')
                ->with('success', 'Email already verified. Welcome to StudEats!');
        }

        // For development: get the latest OTP for debugging
        $latestOtp = null;
        if (config('app.env') === 'local') {
            $latestOtp = EmailVerificationOtp::where('email', $user->email)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();
        }

        return view('auth.verify-email', compact('user', 'latestOtp'));
    }

    /**
     * Complete verification and log in user.
     */
    public function complete(Request $request)
    {
        $userId = session('pending_verification_user_id');
        if (! $userId) {
            return response()->json([
                'success' => false,
                'message' => 'No pending verification session.',
                'redirect' => route('login'),
            ], 400);
        }

        $user = User::find($userId);
        if (! $user || is_null($user->email_verified_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Email verification required.',
            ], 400);
        }

        // Clear verification session and log in user
        session()->forget('pending_verification_user_id');
        Auth::login($user);

        // Send welcome email now that they're verified
        try {
            app(\App\Services\EmailService::class)->sendAccountConfirmation($user);
        } catch (\Exception $e) {
            Log::warning('Failed to send welcome email after verification', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully! Welcome to StudEats!',
            'redirect' => route('dashboard'),
        ]);
    }

    /**
     * Send OTP for email verification.
     */
    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        try {
            $email = $request->validated()['email'];

            // Check if email is rate limited
            if ($this->otpService->isRateLimited($email)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many OTP requests. Please wait before requesting again.',
                    'retry_after' => $this->otpService->getRateLimitResetTime($email),
                ], 429);
            }

            // Generate and send OTP
            $otp = $this->otpService->generateAndSendOtp($email, $request);

            Log::info('OTP sent successfully', [
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your email address.',
                'expires_in_seconds' => \App\Services\OtpService::OTP_EXPIRATION_SECONDS,
                'remaining_attempts' => $this->otpService->getRemainingAttempts($email),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send OTP', [
                'email' => $request->input('email'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code. Please try again.',
            ], 500);
        }
    }

    /**
     * Verify OTP code.
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $email = $validated['email'];
            $otpCode = $validated['otp_code'];

            // Start database transaction for atomic verification
            return DB::transaction(function () use ($email, $otpCode, $request) {
                // Find the OTP record first to check its state
                $otpRecord = EmailVerificationOtp::forEmail($email)
                    ->where('otp_code', $otpCode)
                    ->latest()
                    ->first();

                if (!$otpRecord) {
                    Log::warning('OTP verification failed - code not found', [
                        'email' => $email,
                        'provided_code' => $otpCode,
                        'ip' => $request->ip(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid verification code.',
                    ], 422);
                }

                // Check if already used
                if ($otpRecord->is_used) {
                    Log::warning('OTP verification failed - already used', [
                        'email' => $email,
                        'provided_code' => $otpCode,
                        'otp_id' => $otpRecord->id,
                        'ip' => $request->ip(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'This verification code has already been used. Please request a new one.',
                    ], 422);
                }

                // Check if expired
                if ($otpRecord->isExpired()) {
                    Log::warning('OTP verification failed - expired', [
                        'email' => $email,
                        'provided_code' => $otpCode,
                        'otp_id' => $otpRecord->id,
                        'expired_at' => $otpRecord->expires_at,
                        'ip' => $request->ip(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Your verification code has expired. Please request a new one.',
                    ], 422);
                }

                // Mark OTP as used
                $otpRecord->markAsUsed();

                // Update user email verification atomically
                $user = User::where('email', $email)->first();
                if ($user && is_null($user->email_verified_at)) {
                    $user->update(['email_verified_at' => now()]);

                    Log::info('User email verified successfully', [
                        'user_id' => $user->id,
                        'email' => $email,
                        'otp_id' => $otpRecord->id,
                    ]);
                } elseif ($user && $user->email_verified_at) {
                    Log::info('User email already verified', [
                        'user_id' => $user->id,
                        'email' => $email,
                        'verified_at' => $user->email_verified_at,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Email verified successfully.',
                    'user_exists' => $user !== null,
                    'redirect' => $user ? route('email-verification.complete') : null,
                ]);
            });

        } catch (\Exception $e) {
            Log::error('Failed to verify OTP', [
                'email' => $request->input('email'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify code. Please try again.',
            ], 500);
        }
    }

    /**
     * Resend OTP code.
     */
    public function resendOtp(SendOtpRequest $request): JsonResponse
    {
        // Same as sendOtp but with different messaging
        try {
            $email = $request->validated()['email'];

            // Check if email is rate limited
            if ($this->otpService->isRateLimited($email)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many requests. Please wait before requesting again.',
                    'retry_after' => $this->otpService->getRateLimitResetTime($email),
                ], 429);
            }

            // Generate and send new OTP
            $otp = $this->otpService->generateAndSendOtp($email, $request);

            Log::info('OTP resent successfully', [
                'email' => $email,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'New verification code sent to your email address.',
                'expires_in_seconds' => \App\Services\OtpService::OTP_EXPIRATION_SECONDS,
                'remaining_attempts' => $this->otpService->getRemainingAttempts($email),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to resend OTP', [
                'email' => $request->input('email'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend verification code. Please try again.',
            ], 500);
        }
    }

    /**
     * Get OTP status for an email (for debugging/admin purposes).
     */
    public function getOtpStatus(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');

        try {
            $user = User::where('email', $email)->first();

            return response()->json([
                'email' => $email,
                'user_exists' => $user !== null,
                'email_verified' => $user && ! is_null($user->email_verified_at),
                'is_rate_limited' => $this->otpService->isRateLimited($email),
                'remaining_attempts' => $this->otpService->getRemainingAttempts($email),
                'rate_limit_reset_in' => $this->otpService->getRateLimitResetTime($email),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get OTP status', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get status.',
            ], 500);
        }
    }
}
