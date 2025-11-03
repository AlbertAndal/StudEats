<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please log in with admin credentials.');
        }

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Access denied. Admin privileges required.');
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Your admin account has been suspended.');
        }

        return $next($request);
    }
}
