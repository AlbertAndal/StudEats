<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies for Laravel Cloud
        $middleware->trustProxies(at: '*');
        
        // Replace the default CSRF middleware with our more lenient version
        $middleware->replace(
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\VerifyCsrfToken::class
        );
        
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'no.super.admin' => \App\Http\Middleware\RestrictSuperAdminToUserInterface::class,
            'session.monitor' => \App\Http\Middleware\SessionMonitorMiddleware::class,
        ]);
        
        // Add session monitoring to web routes in development
        if (env('APP_ENV', 'production') === 'local') {
            $middleware->appendToGroup('web', \App\Http\Middleware\SessionMonitorMiddleware::class);
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle CSRF token mismatch exceptions with a user-friendly response
        $exceptions->respond(function (Response $response) {
            if ($response->getStatusCode() === 419) {
                return back()->with([
                    'error' => 'The page expired due to inactivity. Please try again.',
                    'csrf_error' => true
                ])->withInput();
            }

            return $response;
        });
    })->create();
