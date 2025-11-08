#!/usr/bin/env php
<?php

/**
 * StudEats Session & CSRF Troubleshooting Script
 * 
 * This script helps diagnose and fix common 419 Page Expired errors
 * Run with: php troubleshoot-session.php
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "StudEats Session & CSRF Troubleshooting Tool\n";
echo str_repeat("=", 50) . "\n";

// Check 1: App Key
echo "1. Checking APP_KEY...\n";
$appKey = config('app.key');
if (empty($appKey)) {
    echo "   ❌ APP_KEY is missing! Run: php artisan key:generate\n";
    exit(1);
} else {
    echo "   ✅ APP_KEY is set\n";
}

// Check 2: Session Configuration
echo "\n2. Checking session configuration...\n";
$sessionDriver = config('session.driver');
echo "   Session Driver: {$sessionDriver}\n";

if ($sessionDriver === 'database') {
    echo "   Checking sessions table...\n";
    
    try {
        if (Schema::hasTable('sessions')) {
            $sessionCount = DB::table('sessions')->count();
            echo "   ✅ Sessions table exists with {$sessionCount} records\n";
        } else {
            echo "   ❌ Sessions table missing! Run: php artisan session:table && php artisan migrate\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
    }
}

// Check 3: Session Configuration Values
echo "\n3. Session settings analysis...\n";
$lifetime = config('session.lifetime');
$sameSite = config('session.same_site');
$secure = config('session.secure');
$partitioned = config('session.partitioned');

echo "   Lifetime: {$lifetime} minutes\n";
echo "   Same-Site: {$sameSite}\n";
echo "   Secure: " . ($secure ? 'true' : 'false') . "\n";
echo "   Partitioned: " . ($partitioned ? 'true' : 'false') . "\n";

if (app()->environment('local') && $sameSite === 'none') {
    echo "   ⚠️  Warning: same_site=none may cause issues in local development\n";
}

if (app()->environment('local') && $partitioned) {
    echo "   ⚠️  Warning: partitioned=true may cause issues in local development\n";
}

// Check 4: File Permissions
echo "\n4. Checking file permissions...\n";
$storagePath = storage_path();
$frameworkPath = storage_path('framework');
$sessionsPath = storage_path('framework/sessions');

$permissions = [
    $storagePath => 'storage',
    $frameworkPath => 'framework',
    $sessionsPath => 'sessions',
];

foreach ($permissions as $path => $name) {
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        if (is_writable($path)) {
            echo "   ✅ {$name} directory writable ({$perms})\n";
        } else {
            echo "   ❌ {$name} directory not writable ({$perms})\n";
        }
    } else {
        echo "   ❌ {$name} directory missing\n";
    }
}

// Check 5: Environment Settings
echo "\n5. Environment analysis...\n";
$environment = app()->environment();
$appUrl = config('app.url');
$appDebug = config('app.debug');

echo "   Environment: {$environment}\n";
echo "   APP_URL: {$appUrl}\n";
echo "   Debug Mode: " . ($appDebug ? 'enabled' : 'disabled') . "\n";

if ($environment === 'local' && !$appDebug) {
    echo "   ⚠️  Consider enabling debug mode in local environment\n";
}

// Check 6: Recent Errors
echo "\n6. Checking recent errors...\n";
$logPath = storage_path('logs/laravel.log');
if (file_exists($logPath)) {
    $logContent = file_get_contents($logPath);
    $csrfErrors = substr_count($logContent, 'TokenMismatchException');
    $sessionErrors = substr_count($logContent, 'session');
    
    echo "   CSRF errors in log: {$csrfErrors}\n";
    echo "   Session-related entries: {$sessionErrors}\n";
    
    if ($csrfErrors > 0) {
        echo "   ⚠️  CSRF errors detected in logs\n";
    }
} else {
    echo "   ⚠️  Log file not found\n";
}

// Suggested Fixes
echo "\n" . str_repeat("=", 50) . "\n";
echo "SUGGESTED FIXES FOR 419 ERRORS:\n";
echo str_repeat("=", 50) . "\n";

echo "\n1. Clear browser cache and cookies for localhost\n";
echo "   - Press Ctrl+Shift+Del in Firefox/Chrome\n";
echo "   - Select 'Cookies and other site data' and 'Cached images and files'\n";

echo "\n2. Update session configuration (already done in config/session.php):\n";
echo "   - Set same_site to 'lax' for local development\n";
echo "   - Disable partitioned cookies for local development\n";

echo "\n3. Clear application cache:\n";
echo "   php artisan config:clear\n";
echo "   php artisan route:clear\n";
echo "   php artisan view:clear\n";

echo "\n4. Clean session storage:\n";
if ($sessionDriver === 'database') {
    echo "   php artisan tinker\n";
    echo "   >>> DB::table('sessions')->truncate();\n";
} else {
    echo "   rm -rf " . storage_path('framework/sessions/*') . "\n";
}

echo "\n5. Restart the development server:\n";
echo "   composer run dev\n";

echo "\n6. If using Firefox, try these steps:\n";
echo "   - Disable Enhanced Tracking Protection for localhost\n";
echo "   - Check Privacy settings for cookie handling\n";
echo "   - Try in a private/incognito window\n";

echo "\n7. Test with different browsers to isolate Firefox-specific issues\n";

echo "\nAUTOMATIC FIXES:\n";
echo str_repeat("-", 20) . "\n";

// Offer to run automatic fixes
echo "\nWould you like to run automatic fixes? (y/n): ";
$handle = fopen("php://stdin", "r");
$response = trim(fgets($handle));
fclose($handle);

if (strtolower($response) === 'y' || strtolower($response) === 'yes') {
    echo "\nRunning automatic fixes...\n";
    
    // Clear caches
    echo "Clearing caches...\n";
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    echo "✅ Caches cleared\n";
    
    // Clear sessions if using database
    if ($sessionDriver === 'database' && Schema::hasTable('sessions')) {
        echo "Clearing old sessions...\n";
        $deletedSessions = DB::table('sessions')
            ->where('last_activity', '<', time() - (config('session.lifetime') * 60))
            ->delete();
        echo "✅ Deleted {$deletedSessions} old sessions\n";
    }
    
    echo "\n✅ Automatic fixes completed!\n";
    echo "Please restart your development server: composer run dev\n";
} else {
    echo "\nNo automatic fixes applied.\n";
}

echo "\nFor persistent issues, check:\n";
echo "- Browser developer console for JavaScript errors\n";
echo "- Network tab for failed requests\n";
echo "- Laravel logs in storage/logs/\n";
echo "\nTroubleshooting complete!\n";