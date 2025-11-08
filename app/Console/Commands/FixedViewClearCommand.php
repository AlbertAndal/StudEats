<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class FixedViewClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'view:clear';

    /**
     * The console command description.
     */
    protected $description = 'Clear all compiled view files (Laravel Cloud compatible)';

    /**
     * The filesystem instance.
     */
    protected $files;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = config('view.compiled');

        if (! $path) {
            $this->error('View compiled path is not set.');
            return 1;
        }

        if (! $this->files->exists($path)) {
            $this->info('View compiled directory does not exist, creating it...');
            $this->files->makeDirectory($path, 0755, true);
            $this->info('Compiled views directory created.');
            return 0;
        }

        try {
            $this->files->deleteDirectory($path);
            $this->files->makeDirectory($path, 0755, true);
            $this->info('Compiled views cleared successfully.');
        } catch (\Exception $e) {
            $this->warn('Failed to clear views: ' . $e->getMessage());
            // Ensure directory exists even if clearing failed
            if (! $this->files->exists($path)) {
                $this->files->makeDirectory($path, 0755, true);
            }
            $this->info('View directory ensured to exist.');
        }

        return 0;
    }
}