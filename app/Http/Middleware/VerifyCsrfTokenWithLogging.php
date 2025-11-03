<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfTokenWithLogging
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Let Laravel's built-in CSRF middleware handle the verification
            return app(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)->handle($request, $next);
        } catch (TokenMismatchException $e) {
            // Log CSRF failures for monitoring
            Log::warning('CSRF token mismatch detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'session_id' => $request->session()->getId(),
                'token_provided' => $request->input('_token') ? 'yes' : 'no',
                'expected_token' => csrf_token(),
                'timestamp' => now()->toISOString(),
            ]);

            // Check if it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Session expired. Please refresh the page and try again.',
                    'error_code' => 419,
                    'csrf_token' => csrf_token(), // Provide new token
                ], 419);
            }

            // For admin routes, redirect to admin login
            if ($request->is('admin*')) {
                return redirect()->route('admin.login')
                    ->withErrors(['session' => 'Your session has expired. Please log in again.'])
                    ->with('error', 'Session expired for security reasons.');
            }

            // For regular routes, show 419 error page
            return response()->view('errors.419', [], 419);
        }
    }
}