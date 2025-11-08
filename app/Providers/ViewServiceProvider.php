<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Ensure view compiled directory exists
        $this->ensureViewDirectoryExists();
    }

    /**
     * Ensure the view compiled directory exists
     */
    private function ensureViewDirectoryExists(): void
    {
        $viewPath = config('view.compiled');
        
        if ($viewPath && !File::exists($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
        }
        
        // Also ensure other framework directories exist
        $this->ensureFrameworkDirectoriesExist();
    }

    /**
     * Ensure all Laravel framework directories exist
     */
    private function ensureFrameworkDirectoriesExist(): void
    {
        $directories = [
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
        }
    }
}