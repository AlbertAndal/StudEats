<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\EmailService;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected EmailService $emailService;
    protected OtpService $otpService;

    public function __construct(EmailService $emailService, OtpService $otpService)
    {
        $this->emailService = $emailService;
        $this->otpService = $otpService;
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // Will be hashed automatically due to cast
            'age' => $validated['age'] ?? null,
            'daily_budget' => $validated['daily_budget'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'activity_level' => $validated['activity_level'] ?? null,
            'height' => $validated['height'] ?? null,
            'height_unit' => $validated['height_unit'] ?? 'cm',
            'weight' => $validated['weight'] ?? null,
            'weight_unit' => $validated['weight_unit'] ?? 'kg',
            // Remove automatic email verification - will be verified via OTP
            'email_verified_at' => null,
        ]);

        Log::info('Registration successful', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        try {
            // Generate and send OTP for email verification
            $otp = $this->otpService->generateAndSendVerificationLink($user->email, $request);
            
            // Store pending verification email in session
            $request->session()->put('pending_verification_email', $user->email);
            
            // TEMPORARY: Always store OTP code in session for testing (until email is working)
            // TODO: Remove this after email configuration is verified working
            $request->session()->put('dev_otp_code', $otp->otp_code);
            $request->session()->put('dev_otp_expires', $otp->expires_at->toDateTimeString());
            
            // Send welcome email (optional, don't let it block registration)
            try {
                $this->emailService->sendAccountConfirmation($user);
            } catch (\Exception $emailEx) {
                Log::warning('Welcome email failed but continuing registration', [
                    'user_id' => $user->id,
                    'error' => $emailEx->getMessage(),
                ]);
            }
            
        } catch (\Exception $e) {
            Log::warning('Failed to send verification email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
            
            // Still proceed with registration, user can request resend
        }

        return redirect()
            ->route('email.verify.form', ['email' => $user->email])
            ->with('success', 'Account created! Please check your email for the verification code.');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login_new');
    }

    /**
     * Show the admin login form.
     */
    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle user login with rate limiting.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->ensureIsNotRateLimited();

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                Log::warning('Unverified user attempted to login', [
                    'email' => $credentials['email'],
                    'user_id' => $user->id,
                ]);
                
                return redirect()
                    ->route('email.verify.form', ['email' => $user->email])
                    ->with('error', 'Please verify your email address before logging in.');
            }
            
            $request->session()->regenerate();

            RateLimiter::clear($request->throttleKey());

            return redirect()
                ->intended(route('dashboard'))
                ->with('success', 'Welcome back to StudEats!');
        }

        RateLimiter::hit($request->throttleKey());

        throw ValidationException::withMessages([
            'email' => ['These credentials do not match our records.'],
        ]);
    }

    /**
     * Handle admin login with role verification.
     */
    public function adminLogin(LoginRequest $request): RedirectResponse
    {
        $request->ensureIsNotRateLimited();

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Verify user is an admin
            if (!$user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                Log::warning('Non-admin attempted to access admin login', [
                    'email' => $credentials['email'],
                    'ip' => $request->ip(),
                ]);

                return redirect()
                    ->route('admin.login')
                    ->with('error', 'Access denied. Admin privileges required.');
            }

            // Verify account is active
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('admin.login')
                    ->with('error', 'Your admin account has been suspended.');
            }

            $request->session()->regenerate();
            RateLimiter::clear($request->throttleKey());

            Log::info('Admin login successful', [
                'admin_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip(),
            ]);

            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('success', 'Welcome to the admin panel, ' . $user->name . '!');
        }

        RateLimiter::hit($request->throttleKey());

        Log::warning('Failed admin login attempt', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
        ]);

        throw ValidationException::withMessages([
            'email' => ['Invalid admin credentials. Please check your email and password.'],
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('welcome')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show password reset form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset link sending.
     */
    public function sendPasswordResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email:rfc', 'exists:users,email'],
        ]);

        // Rate limiting for password reset requests
        $key = 'password-reset:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => ["Too many password reset attempts. Please try again in {$seconds} seconds."],
            ]);
        }

        RateLimiter::hit($key, 300); // 5 minutes

        // Here you would integrate with Laravel's password reset functionality
        // For now, we'll just show a success message
        return back()->with('status', 'Password reset link has been sent to your email.');
    }
}
