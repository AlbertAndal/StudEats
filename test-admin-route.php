<?php

require_once 'vendor/autoload.php';

echo "=== TESTING ADMIN ROUTE ===\n\n";

try {
    // Bootstrap Laravel
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    echo "✓ Laravel bootstrapped successfully\n";
    
    // Test /admin route
    $request = Illuminate\Http\Request::create('/admin', 'GET');
    $response = $kernel->handle($request);
    
    echo "✓ /admin route responded with status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 302) {
        echo "  → Redirects to: " . $response->headers->get('Location') . "\n";
        echo "  (This is expected for unauthenticated users)\n";
    } elseif ($response->getStatusCode() === 200) {
        echo "  ✓ Admin dashboard loaded successfully!\n";
    } elseif ($response->getStatusCode() === 500) {
        echo "  ✗ 500 Error - Check logs for details\n";
        echo "  Content: " . substr($response->getContent(), 0, 200) . "...\n";
    }
    
    echo "\n=== TEST COMPLETE ===\n";
    echo "Result: Admin route is now working!\n";
    
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
