<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictSuperAdminToUserInterface
{
    /**
     * Handle an incoming request.
     *
     * Prevent super admin users from accessing the user interface.
     * They should only access the admin panel.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Super admin accounts can only access the admin panel.');
        }

        return $next($request);
    }
}
