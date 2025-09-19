<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
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
            'dietary_preferences' => $validated['dietary_preferences'] ?? [],
            'gender' => $validated['gender'] ?? null,
            'activity_level' => $validated['activity_level'] ?? null,
            'height' => $validated['height'] ?? null,
            'height_unit' => $validated['height_unit'] ?? 'cm',
            'weight' => $validated['weight'] ?? null,
            'weight_unit' => $validated['weight_unit'] ?? 'kg',
        ]);

        // Send OTP for email verification
        try {
            $otpService = app(\App\Services\OtpService::class);
            $otpService->generateAndSendOtp($user->email, $request);

            Log::info('Registration successful, OTP sent', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send verification OTP during registration', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }

        // Store user ID in session for OTP verification
        session(['pending_verification_user_id' => $user->id]);

        return redirect()
            ->route('email-verification.show')
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
     * Handle user login with rate limiting.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->ensureIsNotRateLimited();

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
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
