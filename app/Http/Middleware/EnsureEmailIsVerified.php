<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user is not authenticated, let auth middleware handle it
        if (! $user) {
            return $next($request);
        }

        // If user's email is not verified
        if (is_null($user->email_verified_at)) {
            // Store user ID for verification flow
            session(['pending_verification_user_id' => $user->id]);

            // Log out the user
            Auth::logout();

            // Redirect to email verification page
            return redirect()->route('email-verification.show')
                ->with('error', 'Please verify your email address to continue.');
        }

        return $next($request);
    }
}
