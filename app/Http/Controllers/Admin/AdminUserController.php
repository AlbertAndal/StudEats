<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'suspended') {
                $query->where('is_active', false);
            }
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'suspended' => User::where('is_active', false)->count(),
            'admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load('mealPlans.meal', 'adminLogs');

        $activityStats = [
            'meal_plans' => $user->mealPlans()->count(),
            'completed_plans' => $user->mealPlans()->where('is_completed', true)->count(),
            'last_login' => $user->updated_at, // You might want to track this separately
        ];

        return view('admin.users.show', compact('user', 'activityStats'));
    }

    public function suspend(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        if ($user->role === 'super_admin') {
            return back()->with('error', 'Super admin accounts cannot be suspended.');
        }

        $user->suspend($request->reason);

        AdminLog::createLog(
            Auth::id(),
            'user_suspended',
            "Suspended user: {$user->name} ({$user->email})",
            $user,
            ['reason' => $request->reason]
        );

        return back()->with('success', 'User has been suspended successfully.');
    }

    public function activate(User $user)
    {
        $user->activate();

        AdminLog::createLog(
            Auth::id(),
            'user_activated',
            "Activated user: {$user->name} ({$user->email})",
            $user
        );

        return back()->with('success', 'User has been activated successfully.');
    }

    public function resetPassword(User $user)
    {
        $newPassword = 'StudEats' . rand(1000, 9999);
        $user->update(['password' => Hash::make($newPassword)]);

        AdminLog::createLog(
            Auth::id(),
            'password_reset',
            "Reset password for user: {$user->name} ({$user->email})",
            $user
        );

        return back()->with('success', "Password reset successfully. New password: {$newPassword}");
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', Rule::in(['user', 'admin', 'super_admin'])],
        ]);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        AdminLog::createLog(
            Auth::id(),
            'role_updated',
            "Updated role for user: {$user->name} ({$user->email}) from {$oldRole} to {$request->role}",
            $user,
            ['old_role' => $oldRole, 'new_role' => $request->role]
        );

        return back()->with('success', 'User role updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'super_admin') {
            return back()->with('error', 'Super admin accounts cannot be deleted.');
        }

        AdminLog::createLog(
            Auth::id(),
            'user_deleted',
            "Deleted user: {$user->name} ({$user->email})",
            $user
        );

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
