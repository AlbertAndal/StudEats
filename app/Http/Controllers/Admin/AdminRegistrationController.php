<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AdminRegistrationController extends Controller
{
    /**
     * Show the standalone admin registration form.
     */
    public function showStandaloneRegistrationForm()
    {
        return view('admin.register-standalone');
    }

    /**
     * Handle standalone admin account registration.
     */
    public function standaloneRegister(Request $request)
    {
        // Rate limiting: 5 attempts per hour per super admin
        $rateLimitKey = 'admin-registration:' . Auth::id();
        
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            throw ValidationException::withMessages([
                'email' => "Too many admin registration attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,super_admin'],
        ]);

        // Additional security: Only super_admin can create super_admin accounts
        if ($request->role === 'super_admin' && !Auth::user()->isSuperAdmin()) {
            throw ValidationException::withMessages([
                'role' => 'Only super admins can create super admin accounts.',
            ]);
        }

        // Increment rate limiter
        RateLimiter::hit($rateLimitKey, 3600); // 1 hour decay

        // Create the new admin account
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Auto-verify admin accounts
            'role' => $request->role,
            'is_active' => true,
            'timezone' => 'Asia/Manila',
        ]);

        // Log the admin creation
        AdminLog::createLog(
            Auth::id(),
            'admin_created',
            "Created new admin account: {$admin->name} ({$admin->email}) with role: {$admin->role}",
            $admin,
            [
                'created_admin_id' => $admin->id,
                'role' => $admin->role,
                'created_by' => Auth::user()->name,
                'created_by_id' => Auth::id(),
            ]
        );

        // Clear rate limiter on success
        RateLimiter::clear($rateLimitKey);

        return redirect()->route('admin.register.standalone')
            ->with('success', "Admin account created successfully! {$admin->name} can now log in with email: {$admin->email}");
    }
}