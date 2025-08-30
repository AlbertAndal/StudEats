<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set timezone for the application
        if (auth()->check()) {
            $userTimezone = auth()->user()->timezone ?? config('app.timezone');
            config(['app.timezone' => $userTimezone]);
        }
        
        // (Week start left default; adjust in code where needed.)
    }
}
