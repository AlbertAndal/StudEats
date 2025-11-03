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
        // Initialize all variables with safe defaults
        $stats = [
            'total_users' => 0,
            'active_users' => 0,
            'suspended_users' => 0,
            'total_meals' => 0,
            'featured_meals' => 0,
            'recent_registrations' => 0,
        ];
        $recentActivities = collect();
        $userGrowth = collect();
        $topMeals = collect();

        // Fetch statistics with comprehensive error handling
        try {
            $stats = $this->getStatistics();
        } catch (\Exception $e) {
            \Log::error('Admin dashboard stats error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }

        // Fetch recent activities with error handling
        try {
            $recentActivities = $this->getRecentActivities();
        } catch (\Exception $e) {
            \Log::error('Admin recent activities error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }

        // Fetch user growth with error handling
        try {
            $userGrowth = $this->getUserGrowth();
        } catch (\Exception $e) {
            \Log::error('Admin user growth error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }

        // Fetch top meals with error handling
        try {
            $topMeals = $this->getTopMeals();
        } catch (\Exception $e) {
            \Log::error('Admin top meals error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }

        return view('admin.dashboard', compact('stats', 'recentActivities', 'userGrowth', 'topMeals'));
    }

    private function getStatistics(): array
    {
        return [
            'total_users' => User::count() ?? 0,
            'active_users' => User::where('is_active', true)->count() ?? 0,
            'suspended_users' => User::where('is_active', false)->count() ?? 0,
            'total_meals' => Meal::count() ?? 0,
            'featured_meals' => Meal::where('is_featured', true)->count() ?? 0,
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))->count() ?? 0,
        ];
    }

    private function getRecentActivities()
    {
        try {
            return AdminLog::with('adminUser:id,name,email')
                ->latest()
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Failed to fetch admin activities', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    private function getUserGrowth()
    {
        try {
            return User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        } catch (\Exception $e) {
            \Log::error('Failed to fetch user growth', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    private function getTopMeals()
    {
        try {
            return DB::table('meals')
                ->select(
                    'meals.id',
                    'meals.name',
                    'meals.cost',
                    'meals.cuisine_type',
                    'meals.difficulty',
                    'meals.image_path',
                    DB::raw('COUNT(meal_plans.id) as meal_plans_count')
                )
                ->leftJoin('meal_plans', 'meals.id', '=', 'meal_plans.meal_id')
                ->groupBy('meals.id', 'meals.name', 'meals.cost', 'meals.cuisine_type', 'meals.difficulty', 'meals.image_path')
                ->having('meal_plans_count', '>', 0)
                ->orderByDesc('meal_plans_count')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Failed to fetch top meals', ['error' => $e->getMessage()]);
            return collect();
        }
    }    public function systemHealth()
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
