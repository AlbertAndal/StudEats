<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Meal;
use App\Models\AdminLog;
use Illuminate\Support\Facades\DB;

try {
    echo "Testing admin dashboard queries...\n\n";
    
    // Test database connection
    echo "Database connection: ";
    DB::connection()->getPdo();
    echo "✅ Connected\n";
    
    // Test basic queries that the dashboard uses
    echo "Testing dashboard queries:\n";
    
    echo "1. Users count: ";
    $userCount = User::count();
    echo "✅ {$userCount} users\n";
    
    echo "2. Active users: ";
    $activeUsers = User::where('is_active', true)->count();
    echo "✅ {$activeUsers} active users\n";
    
    echo "3. Meals count: ";
    $mealCount = Meal::count();
    echo "✅ {$mealCount} meals\n";
    
    echo "4. Featured meals: ";
    $featuredMeals = Meal::where('is_featured', true)->count();
    echo "✅ {$featuredMeals} featured meals\n";
    
    echo "5. Recent registrations: ";
    $recentRegs = User::where('created_at', '>=', now()->subDays(7))->count();
    echo "✅ {$recentRegs} recent registrations\n";
    
    echo "6. Admin logs table exists: ";
    $adminLogExists = DB::getSchemaBuilder()->hasTable('admin_logs');
    echo $adminLogExists ? "✅ Yes\n" : "❌ No - this might cause issues\n";
    
    if ($adminLogExists) {
        echo "7. Admin logs count: ";
        $adminLogCount = AdminLog::count();
        echo "✅ {$adminLogCount} admin logs\n";
    }
    
    echo "8. User growth query test: ";
    $userGrowth = User::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('COUNT(*) as count')
    )
    ->where('created_at', '>=', now()->subDays(30))
    ->groupBy(DB::raw('DATE(created_at)'))
    ->orderBy('date')
    ->get();
    echo "✅ " . $userGrowth->count() . " growth data points\n";
    
    echo "\nAll dashboard queries successful!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}