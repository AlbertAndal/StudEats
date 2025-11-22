<?php

namespace App\Providers;

use App\Listeners\RecordUserLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
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
        // Register login event listener
        Event::listen(Login::class, RecordUserLogin::class);
        
        // Set timezone for the application
        if (auth()->check()) {
            $userTimezone = auth()->user()->timezone ?? config('app.timezone');
            config(['app.timezone' => $userTimezone]);
        }
        
        // (Week start left default; adjust in code where needed.)
    }
}
