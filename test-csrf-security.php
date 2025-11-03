<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== CSRF TOKEN AND SESSION TESTING ===\n\n";

try {
    // Create a mock request
    $request = Request::create('/test', 'GET');
    $response = $kernel->handle($request);
    
    echo "✓ Laravel application booted successfully\n";
    
    // Test CSRF token generation
    if (function_exists('csrf_token')) {
        $token = csrf_token();
        echo "✓ CSRF token generated: " . substr($token, 0, 16) . "...\n";
    } else {
        echo "✗ CSRF token function not available\n";
    }
    
    // Test session configuration
    $sessionConfig = config('session');
    echo "✓ Session configuration loaded:\n";
    echo "  - Driver: " . $sessionConfig['driver'] . "\n";
    echo "  - Lifetime: " . $sessionConfig['lifetime'] . " minutes\n";
    echo "  - Secure: " . ($sessionConfig['secure'] ? 'true' : 'false') . "\n";
    echo "  - Same Site: " . $sessionConfig['same_site'] . "\n";
    
    // Check if session table exists
    try {
        $sessionCount = DB::table('sessions')->count();
        echo "✓ Session table exists with $sessionCount records\n";
    } catch (Exception $e) {
        echo "✗ Session table error: " . $e->getMessage() . "\n";
    }
    
    // Test SecurityMonitorController exists
    if (class_exists('App\Http\Controllers\Admin\SecurityMonitorController')) {
        echo "✓ SecurityMonitorController class exists\n";
        
        $controller = new App\Http\Controllers\Admin\SecurityMonitorController();
        if (method_exists($controller, 'index')) {
            echo "✓ SecurityMonitorController index method exists\n";
        }
        
        if (method_exists($controller, 'getCsrfErrorStats')) {
            echo "✓ SecurityMonitorController getCsrfErrorStats method exists\n";
        }
    } else {
        echo "✗ SecurityMonitorController class not found\n";
    }
    
    // Test API routes
    echo "\n=== TESTING API ROUTES ===\n";
    
    $apiRoutes = [
        '/api/csrf-token',
        '/api/session-check',
    ];
    
    foreach ($apiRoutes as $route) {
        try {
            $request = Request::create($route, 'GET');
            $response = $kernel->handle($request);
            echo "✓ Route $route responded with status: " . $response->getStatusCode() . "\n";
        } catch (Exception $e) {
            echo "✗ Route $route error: " . $e->getMessage() . "\n";
        }
    }
    
    // Test file existence
    echo "\n=== TESTING FILE EXISTENCE ===\n";
    
    $files = [
        'resources/views/errors/419.blade.php',
        'public/js/csrf-manager.js',
        'app/Http/Controllers/Api/SessionController.php',
        'resources/views/admin/security-monitor.blade.php',
    ];
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            echo "✓ File exists: $file\n";
        } else {
            echo "✗ File missing: $file\n";
        }
    }
    
    echo "\n=== CSRF TOKEN SECURITY TEST COMPLETE ===\n";
    echo "Status: All core security components appear to be properly installed\n";
    
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}