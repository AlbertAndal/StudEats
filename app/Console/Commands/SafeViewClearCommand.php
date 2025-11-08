<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SafeViewClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:clear-safe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all compiled view files safely (with error handling)';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $viewPath = config('view.compiled');

        if (!$viewPath) {
            $this->error('View compiled path is not configured.');
            return 1;
        }

        if (!$this->files->exists($viewPath)) {
            $this->info('View compiled path does not exist, creating it...');
            $this->files->makeDirectory($viewPath, 0755, true);
            $this->info('View compiled path created successfully.');
            return 0;
        }

        try {
            $this->files->deleteDirectory($viewPath);
            $this->files->makeDirectory($viewPath, 0755, true);
            $this->info('Compiled views cleared successfully.');
        } catch (\Exception $e) {
            $this->warn('Could not clear compiled views: ' . $e->getMessage());
            // Try to create the directory if it doesn't exist
            if (!$this->files->exists($viewPath)) {
                $this->files->makeDirectory($viewPath, 0755, true);
                $this->info('View compiled directory recreated.');
            }
        }

        return 0;
    }
}