<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\FixedViewClearCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Register the safe view clear command for Laravel Cloud deployment
Artisan::command('view:clear-safe', function () {
    $viewPath = config('view.compiled');
    
    if (!$viewPath) {
        $this->error('View compiled path is not configured.');
        return 1;
    }
    
    if (!file_exists($viewPath)) {
        $this->info('View compiled path does not exist, creating it...');
        mkdir($viewPath, 0755, true);
        $this->info('View compiled path created successfully.');
        return 0;
    }
    
    try {
        $files = new \Illuminate\Filesystem\Filesystem();
        $files->deleteDirectory($viewPath);
        $files->makeDirectory($viewPath, 0755, true);
        $this->info('Compiled views cleared successfully.');
    } catch (\Exception $e) {
        $this->warn('Could not clear compiled views: ' . $e->getMessage());
        if (!file_exists($viewPath)) {
            mkdir($viewPath, 0755, true);
            $this->info('View compiled directory recreated.');
        }
    }
    
    return 0;
})->purpose('Clear all compiled view files safely (with error handling)');
