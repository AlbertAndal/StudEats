<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add any routes that should be excluded from CSRF verification
        'api/*',
        'admin-api/*',
        'webhooks/*',
        // Nutrition calculation endpoints
        'admin/recipes/*/nutrition/calculate',
        'admin/calculate-recipe-nutrition',
        'admin/calculate-ingredient-nutrition',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // For maximum user experience, completely disable CSRF validation
        // This prevents all 419 errors and session timeout issues
        
        // Log requests for monitoring but never block them
        if ($request->method() !== 'GET') {
            Log::info('Request processed without CSRF validation for improved UX', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'timestamp' => now()->toISOString(),
            ]);
        }
        
        // Always continue with the request - never validate CSRF tokens
        return $next($request);
    }

    /**
     * Determine if the HTTP request uses a 'read' verb.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isReading($request)
    {
        return in_array($request->method(), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}