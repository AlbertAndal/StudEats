<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Cache expensive queries for 5 minutes to prevent timeouts
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'suspended_users' => User::where('is_active', false)->count(),
                'total_meals' => Meal::count(),
                'featured_meals' => Meal::where('is_featured', true)->count(),
                'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))->count(),
            ];
        });

        // Get recent activities without complex joins
        $recentActivities = Cache::remember('admin_recent_activities', 300, function () {
            return AdminLog::with('adminUser:id,name,email')
                ->latest()
                ->limit(10)
                ->get();
        });

        // Simplified user growth query with date filtering
        $userGrowth = Cache::remember('admin_user_growth', 300, function () {
            return User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        });

        // Optimize top meals query
        $topMeals = Cache::remember('admin_top_meals', 300, function () {
            return Meal::select(['id', 'name', 'cost', 'cuisine_type'])
                ->withCount('mealPlans')
                ->having('meal_plans_count', '>', 0)
                ->orderBy('meal_plans_count', 'desc')
                ->limit(5)
                ->get();
        });

        return view('admin.dashboard', compact('stats', 'recentActivities', 'userGrowth', 'topMeals'));
    }

    public function systemHealth()
    {
        $health = [
            'database' => $this->checkDatabaseConnection(),
            'storage' => $this->checkStorageHealth(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
        ];

        return response()->json($health);
    }

    private function checkDatabaseConnection(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connection is working'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()];
        }
    }

    private function checkStorageHealth(): array
    {
        $storagePath = storage_path();
        $freeSpace = disk_free_space($storagePath);
        $totalSpace = disk_total_space($storagePath);
        $usedPercentage = (($totalSpace - $freeSpace) / $totalSpace) * 100;

        return [
            'status' => $usedPercentage > 90 ? 'warning' : 'healthy',
            'free_space' => $this->formatBytes($freeSpace),
            'total_space' => $this->formatBytes($totalSpace),
            'used_percentage' => round($usedPercentage, 2),
        ];
    }

    private function getMemoryUsage(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->getMemoryLimit();

        return [
            'current' => $this->formatBytes($memoryUsage),
            'limit' => $this->formatBytes($memoryLimit),
            'percentage' => round(($memoryUsage / $memoryLimit) * 100, 2),
        ];
    }

    private function getDiskUsage(): array
    {
        $path = base_path();
        $freeSpace = disk_free_space($path);
        $totalSpace = disk_total_space($path);
        $usedPercentage = (($totalSpace - $freeSpace) / $totalSpace) * 100;

        return [
            'free' => $this->formatBytes($freeSpace),
            'total' => $this->formatBytes($totalSpace),
            'used_percentage' => round($usedPercentage, 2),
            'status' => $usedPercentage > 90 ? 'warning' : 'healthy',
        ];
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / (1024 ** $pow), 2) . ' ' . $units[$pow];
    }

    private function getMemoryLimit(): int
    {
        $memoryLimit = ini_get('memory_limit');
        
        if ($memoryLimit == -1) {
            return PHP_INT_MAX;
        }

        return $this->convertToBytes($memoryLimit);
    }

    private function convertToBytes(string $value): int
    {
        $unit = strtolower(substr($value, -1));
        $number = (int) $value;

        return match($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => $number,
        };
    }
}
