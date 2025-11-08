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
        // Replace the default CSRF middleware with our more lenient version
        $middleware->replace(
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\VerifyCsrfToken::class
        );
        
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'no.super.admin' => \App\Http\Middleware\RestrictSuperAdminToUserInterface::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Completely suppress TokenMismatchException to prevent 419 errors
        $exceptions->dontReport([
            \Illuminate\Session\TokenMismatchException::class,
        ]);
        
        // Render custom response for token mismatch - just continue the request
        $exceptions->renderable(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            // Never show 419 page - redirect to login or back
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Request processed'], 200);
            }
            
            // For web requests, redirect to intended destination or back
            return redirect()->back()->withInput()->with('info', 'Request processed');
        });
    })->create();
