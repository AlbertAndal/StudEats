<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionMonitorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log session information for debugging
        $this->logSessionInfo($request);
        
        // Check for potential session issues
        $this->checkSessionHealth($request);
        
        // Add session debugging headers in non-production
        $response = $next($request);
        
        if (env('APP_ENV', 'production') !== 'production') {
            $this->addDebugHeaders($response, $request);
        }
        
        return $response;
    }

    /**
     * Log session information for debugging
     */
    private function logSessionInfo(Request $request): void
    {
        // Only log in development environment
        if (env('APP_ENV', 'production') !== 'local') {
            return;
        }

        $sessionData = [
            'session_id' => Session::getId(),
            'session_name' => config('session.cookie'),
            'has_session_cookie' => $request->hasCookie(config('session.cookie')),
            'csrf_token_present' => !empty(csrf_token()),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ];

        Log::debug('Session Monitor', $sessionData);
    }

    /**
     * Check session health and warn about potential issues
     */
    private function checkSessionHealth(Request $request): void
    {
        // Check if session cookie is missing for authenticated routes
        $sessionCookieName = config('session.cookie');
        if (!$request->hasCookie($sessionCookieName) && $request->expectsJson() === false) {
            Log::warning('Session cookie missing for web request', [
                'url' => $request->fullUrl(),
                'expected_cookie' => $sessionCookieName,
                'available_cookies' => array_keys($request->cookies->all()),
            ]);
        }

        // Check for mismatched session configuration
        if (Session::getId() && !Session::isValidId(Session::getId())) {
            Log::warning('Invalid session ID detected', [
                'session_id' => Session::getId(),
                'url' => $request->fullUrl(),
            ]);
        }

        // Check CSRF token presence for POST requests
        if ($request->isMethod('POST') && !$request->hasHeader('X-CSRF-TOKEN') && !$request->has('_token')) {
            Log::warning('CSRF token missing from POST request', [
                'url' => $request->fullUrl(),
                'has_csrf_header' => $request->hasHeader('X-CSRF-TOKEN'),
                'has_csrf_input' => $request->has('_token'),
            ]);
        }
    }

    /**
     * Add debug headers for development
     */
    private function addDebugHeaders(Response $response, Request $request): void
    {
        $response->headers->set('X-Debug-Session-ID', Session::getId());
        $response->headers->set('X-Debug-CSRF-Token', csrf_token());
        $response->headers->set('X-Debug-Session-Driver', config('session.driver'));
        $response->headers->set('X-Debug-Session-Lifetime', config('session.lifetime'));
        
        // Add cache control headers to prevent caching of sensitive pages
        if (str_contains($request->path(), 'admin') || str_contains($request->path(), 'login')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }
    }
}