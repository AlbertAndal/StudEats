<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,super_admin'],
        ]);

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

        // Log the admin creation (if there's an authenticated user)
        if (Auth::check()) {
            AdminLog::createLog(
                Auth::id(),
                'admin_created',
                "Created new admin account: {$admin->name} ({$admin->email}) with role: {$admin->role}",
                $admin,
                [
                    'created_admin_id' => $admin->id,
                    'role' => $admin->role,
                    'created_by' => Auth::user()->name,
                ]
            );
        }

        return redirect()->route('admin.register.standalone')
            ->with('success', "Admin account created successfully! {$admin->name} can now log in with email: {$admin->email}");
    }
}