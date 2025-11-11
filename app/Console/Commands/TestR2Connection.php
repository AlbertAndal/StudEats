<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Exception;

class TestR2Connection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:r2-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Cloudflare R2 storage connection and functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Testing Cloudflare R2 Connection...');
        $this->info('=====================================');
        
        try {
            // Test 1: Create disk instance
            $this->info('1. Creating S3 disk instance...');
            $disk = Storage::disk('s3');
            $this->info('✅ S3 disk created successfully');
            
            // Test 2: Test file upload
            $this->info('2. Testing file upload...');
            $testContent = "R2 Test file created at " . now()->toDateTimeString();
            $testFile = 'tests/r2-connection-test-' . time() . '.txt';
            
            $uploaded = $disk->put($testFile, $testContent);
            
            if ($uploaded) {
                $this->info("✅ File uploaded successfully: {$testFile}");
                
                // Test 3: Check file existence
                $this->info('3. Checking file existence...');
                $exists = $disk->exists($testFile);
                $this->info($exists ? '✅ File exists' : '❌ File not found');
                
                if ($exists) {
                    // Test 4: Generate URL
                    $this->info('4. Testing URL generation...');
                    try {
                        $url = $disk->url($testFile);
                        $this->info("✅ URL generated: {$url}");
                    } catch (Exception $e) {
                        $this->error("⚠️ URL generation failed: " . $e->getMessage());
                    }
                    
                    // Test 5: Retrieve content
                    $this->info('5. Testing file retrieval...');
                    try {
                        $content = $disk->get($testFile);
                        $this->info("✅ Content retrieved: " . substr($content, 0, 50) . "...");
                    } catch (Exception $e) {
                        $this->error("⚠️ File retrieval failed: " . $e->getMessage());
                    }
                    
                    // Test 6: File size
                    $this->info('6. Testing file metadata...');
                    try {
                        $size = $disk->size($testFile);
                        $this->info("✅ File size: {$size} bytes");
                    } catch (Exception $e) {
                        $this->error("⚠️ Size check failed: " . $e->getMessage());
                    }
                    
                    // Clean up
                    $this->info('7. Cleaning up test file...');
                    $deleted = $disk->delete($testFile);
                    $this->info($deleted ? '✅ Test file deleted' : '⚠️ Failed to delete test file');
                }
                
                $this->info('');
                $this->info('🎉 R2 Connection Test COMPLETED SUCCESSFULLY!');
                $this->info('Your Cloudflare R2 storage is working correctly.');
                
            } else {
                $this->error('❌ File upload failed');
                return 1;
            }
            
        } catch (Exception $e) {
            $this->error('❌ R2 Connection Test FAILED!');
            $this->error('Error: ' . $e->getMessage());
            $this->error('Class: ' . get_class($e));
            
            if ($e->getPrevious()) {
                $this->error('Previous error: ' . $e->getPrevious()->getMessage());
            }
            
            return 1;
        }
        
        return 0;
    }
}
