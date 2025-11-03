<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom 419 CSRF token mismatch handling
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            // Log CSRF failures for monitoring
            \Illuminate\Support\Facades\Log::warning('CSRF token mismatch detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'session_id' => $request->session()->getId(),
                'token_provided' => $request->input('_token') ? 'yes' : 'no',
                'timestamp' => now()->toISOString(),
            ]);

            // AJAX requests get JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Session expired. Please refresh the page and try again.',
                    'error_code' => 419,
                    'csrf_token' => csrf_token(),
                ], 419);
            }

            // Admin routes redirect to admin login
            if ($request->is('admin*')) {
                return redirect()->route('admin.login')
                    ->withErrors(['session' => 'Your session has expired. Please log in again.'])
                    ->with('error', 'Session expired for security reasons.');
            }

            // Show custom 419 error page
            return response()->view('errors.419', [], 419);
        });
    })->create();
