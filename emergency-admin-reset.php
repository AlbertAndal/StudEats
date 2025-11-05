<?php

/**
 * Emergency Admin Password Reset Script
 * 
 * This script can be run directly to reset the admin password.
 * Upload this file to your Laravel Cloud project root and visit it in your browser.
 * DELETE THIS FILE after running it for security!
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "<!DOCTYPE html><html><head><title>Admin Reset</title>";
echo "<style>body{font-family:monospace;padding:40px;background:#1a1a1a;color:#0f0;}";
echo ".box{border:2px solid #0f0;padding:20px;max-width:600px;margin:20px auto;}</style></head><body>";
echo "<div class='box'><h1>🔐 StudEats Admin Password Reset</h1>";

try {
    $adminEmail = 'admin@studeats.com';
    $adminPassword = 'admin123';
    
    $admin = User::where('email', $adminEmail)->first();
    
    if ($admin) {
        // Update existing admin
        $admin->password = Hash::make($adminPassword);
        $admin->email_verified_at = now();
        $admin->role = 'super_admin';
        $admin->is_active = true;
        $admin->save();
        
        echo "<p>✅ <strong>SUCCESS!</strong> Admin password has been reset.</p>";
        echo "<hr>";
        echo "<p><strong>Email:</strong> {$adminEmail}</p>";
        echo "<p><strong>Password:</strong> {$adminPassword}</p>";
        echo "<p><strong>Role:</strong> {$admin->role}</p>";
        echo "<p><strong>Status:</strong> Active</p>";
        echo "<hr>";
        echo "<p>⚠️ <strong>SECURITY WARNING:</strong> Delete this file immediately!</p>";
        echo "<p>📝 Change your password after logging in.</p>";
        echo "<p><a href='/admin/login' style='color:#0f0;'>→ Go to Admin Login</a></p>";
    } else {
        // Create new admin
        $admin = User::create([
            'name' => 'StudEats Admin',
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
            'email_verified_at' => now(),
            'role' => 'super_admin',
            'is_active' => true,
            'timezone' => 'Asia/Manila',
        ]);
        
        echo "<p>✅ <strong>SUCCESS!</strong> Admin account has been created.</p>";
        echo "<hr>";
        echo "<p><strong>Email:</strong> {$adminEmail}</p>";
        echo "<p><strong>Password:</strong> {$adminPassword}</p>";
        echo "<p><strong>Role:</strong> super_admin</p>";
        echo "<hr>";
        echo "<p>⚠️ <strong>SECURITY WARNING:</strong> Delete this file immediately!</p>";
        echo "<p>📝 Change your password after logging in.</p>";
        echo "<p><a href='/admin/login' style='color:#0f0;'>→ Go to Admin Login</a></p>";
    }
} catch (Exception $e) {
    echo "<p>❌ <strong>ERROR:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database connection and try again.</p>";
}

echo "</div></body></html>";
