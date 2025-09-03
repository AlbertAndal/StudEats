<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Meal;

class TestImageUploads extends Command
{
    protected $signature = 'test:images';
    protected $description = 'Test image upload functionality and troubleshoot issues';

    public function handle()
    {
        $this->info('🔍 Testing Image Upload System...');
        $this->newLine();

        // Test 1: Storage Configuration
        $this->info('1. Testing Storage Configuration:');
        $this->checkStorageConfig();
        $this->newLine();

        // Test 2: Storage Symlink
        $this->info('2. Testing Storage Symlink:');
        $this->checkStorageSymlink();
        $this->newLine();

        // Test 3: Directory Permissions
        $this->info('3. Testing Directory Permissions:');
        $this->checkDirectoryPermissions();
        $this->newLine();

        // Test 4: Existing Images
        $this->info('4. Testing Existing Images:');
        $this->checkExistingImages();
        $this->newLine();

        // Test 5: URL Generation
        $this->info('5. Testing URL Generation:');
        $this->checkUrlGeneration();
        $this->newLine();

        $this->info('✅ Image upload system test completed!');
    }

    private function checkStorageConfig()
    {
        $defaultDisk = config('filesystems.default');
        $publicDisk = config('filesystems.disks.public');
        $appUrl = config('app.url');
        
        $this->line("   Default disk: {$defaultDisk}");
        $this->line("   App URL: {$appUrl}");
        $this->line("   Public disk root: " . ($publicDisk['root'] ?? 'not configured'));
        $this->line("   Public disk URL: " . ($publicDisk['url'] ?? 'not configured'));
        
        if (isset($publicDisk['root']) && is_dir($publicDisk['root'])) {
            $this->info("   ✅ Public storage directory exists");
        } else {
            $this->error("   ❌ Public storage directory does not exist");
        }
    }

    private function checkStorageSymlink()
    {
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');
        
        if (is_link($linkPath) || is_dir($linkPath)) {
            $this->info("   ✅ Storage symlink exists: {$linkPath}");
            
            // Check if symlink points to correct target
            if (is_link($linkPath)) {
                $actualTarget = readlink($linkPath);
                $this->line("   Symlink points to: {$actualTarget}");
                $this->line("   Expected target: {$targetPath}");
            }
        } else {
            $this->error("   ❌ Storage symlink does not exist");
            $this->warn("   Run: php artisan storage:link");
        }
    }

    private function checkDirectoryPermissions()
    {
        $directories = [
            storage_path('app'),
            storage_path('app/public'),
            storage_path('app/public/meals'),
            public_path('storage'),
        ];

        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $permissions = substr(sprintf('%o', fileperms($dir)), -4);
                $writable = is_writable($dir) ? '✅' : '❌';
                $this->line("   {$writable} {$dir} (perms: {$permissions})");
            } else {
                $this->error("   ❌ Directory does not exist: {$dir}");
            }
        }
    }

    private function checkExistingImages()
    {
        $files = Storage::disk('public')->files('meals');
        $this->line("   Found " . count($files) . " files in meals directory:");
        
        foreach ($files as $file) {
            $exists = Storage::disk('public')->exists($file);
            $size = Storage::disk('public')->size($file);
            $url = asset('storage/' . $file);
            
            $status = $exists ? '✅' : '❌';
            $this->line("   {$status} {$file} ({$size} bytes)");
            $this->line("      URL: {$url}");
        }
    }

    private function checkUrlGeneration()
    {
        $meals = Meal::whereNotNull('image_path')->limit(3)->get();
        
        if ($meals->isEmpty()) {
            $this->warn("   No meals with images found");
            return;
        }

        foreach ($meals as $meal) {
            $this->line("   Meal: {$meal->name}");
            $this->line("   Image path: {$meal->image_path}");
            $this->line("   Generated URL: {$meal->image_url}");
            
            $fileExists = Storage::disk('public')->exists($meal->image_path);
            $status = $fileExists ? '✅' : '❌';
            $this->line("   File exists: {$status}");
            $this->newLine();
        }
    }
}