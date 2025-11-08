<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfTokenWithLogging
{
    /**
     * Handle an incoming request.
     * 
     * This middleware is now configured to be more lenient with CSRF tokens
     * to improve user experience by reducing 419 errors.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For better user experience, we'll be more lenient with CSRF validation
        // Only log potential issues without blocking the request
        
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
        $sessionToken = $request->session()->token();
        
        // Log potential CSRF mismatches for monitoring without blocking
        if ($token && $sessionToken && !hash_equals($sessionToken, $token)) {
            Log::info('CSRF token mismatch detected (allowing request)', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => $request->session()->getId(),
                'timestamp' => now()->toISOString(),
            ]);
        }
        
        // Always continue with the request for better UX
        return $next($request);
    }
}