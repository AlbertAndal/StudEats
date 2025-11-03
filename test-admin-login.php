<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    echo "Testing admin login credentials...\n\n";
    
    // Test database connection
    echo "Database connection: ";
    DB::connection()->getPdo();
    echo "✅ Connected\n";
    
    // Check if sessions table exists
    echo "Sessions table: ";
    $sessionTableExists = DB::getSchemaBuilder()->hasTable('sessions');
    echo $sessionTableExists ? "✅ Exists\n" : "❌ Missing\n";
    
    if (!$sessionTableExists) {
        echo "Creating sessions table...\n";
        Artisan::call('session:table');
        Artisan::call('migrate', ['--force' => true]);
        echo "✅ Sessions table created\n";
    }
    
    // Check admin users
    echo "\nAdmin Users:\n";
    $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();
    
    if ($adminUsers->isEmpty()) {
        echo "❌ No admin users found. Running seeder...\n";
        Artisan::call('db:seed', ['--class' => 'AdminUserSeeder', '--force' => true]);
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();
    }
    
    foreach ($adminUsers as $user) {
        echo "✅ {$user->name} ({$user->email}) - Role: {$user->role}\n";
        echo "   Active: " . ($user->is_active ? "Yes" : "No") . "\n";
        echo "   Email Verified: " . ($user->email_verified_at ? "Yes" : "No") . "\n";
        
        // Test password
        $testPassword = $user->role === 'super_admin' ? 'superadmin123' : 'admin123';
        $passwordMatch = Hash::check($testPassword, $user->password);
        echo "   Password ({$testPassword}): " . ($passwordMatch ? "✅ Valid" : "❌ Invalid") . "\n\n";
    }
    
    echo "All checks completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}