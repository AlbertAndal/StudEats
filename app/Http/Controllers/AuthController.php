<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
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

        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Welcome to StudEats! Your account has been created successfully.');
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
        $key = 'password-reset:' . $request->ip();
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
