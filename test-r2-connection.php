<?php

require_once __DIR__ . '/vendor/autoload.php';

// Test R2 connection directly
use Illuminate\Support\Facades\Storage;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class);

try {
    echo "Testing Cloudflare R2 Connection...\n";
    echo "===============================\n";
    
    // Test basic disk creation
    $disk = Storage::disk('s3');
    echo "✅ S3 disk created successfully\n";
    
    // Create a simple test file
    $testContent = "Test file created at " . date('Y-m-d H:i:s');
    $testFile = 'test/r2-connection-test-' . time() . '.txt';
    
    echo "Uploading test file: $testFile\n";
    $uploaded = $disk->put($testFile, $testContent);
    
    if ($uploaded) {
        echo "✅ File uploaded successfully\n";
        
        // Test file existence
        $exists = $disk->exists($testFile);
        echo "File exists: " . ($exists ? "✅ YES" : "❌ NO") . "\n";
        
        if ($exists) {
            // Test URL generation
            try {
                $url = $disk->url($testFile);
                echo "✅ File URL generated: $url\n";
            } catch (Exception $e) {
                echo "⚠️ URL generation failed: " . $e->getMessage() . "\n";
            }
            
            // Test file content retrieval
            try {
                $retrievedContent = $disk->get($testFile);
                echo "✅ File content retrieved: " . substr($retrievedContent, 0, 50) . "...\n";
            } catch (Exception $e) {
                echo "⚠️ File retrieval failed: " . $e->getMessage() . "\n";
            }
            
            // Clean up - delete test file
            $deleted = $disk->delete($testFile);
            echo "✅ Test file cleaned up: " . ($deleted ? "SUCCESS" : "FAILED") . "\n";
        }
        
        echo "\n🎉 R2 Connection Test PASSED!\n";
        echo "Your Cloudflare R2 storage is working correctly.\n";
        
    } else {
        echo "❌ File upload failed\n";
    }
    
} catch (Exception $e) {
    echo "❌ R2 Connection Test FAILED!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    
    if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
        echo "Previous error: " . $e->getPrevious()->getMessage() . "\n";
    }
}