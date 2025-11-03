<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        try {
            // Cache expensive queries for 5 minutes to prevent timeouts
            $stats = Cache::remember('admin_dashboard_stats', 300, function () {
                try {
                    return [
                        'total_users' => User::count(),
                        'active_users' => User::where('is_active', true)->count(),
                        'suspended_users' => User::where('is_active', false)->count(),
                        'total_meals' => Meal::count(),
                        'featured_meals' => Meal::where('is_featured', true)->count(),
                        'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))->count(),
                    ];
                } catch (\Exception $e) {
                    \Log::error('Admin dashboard stats query failed', ['error' => $e->getMessage()]);

                    return [
                        'total_users' => 0,
                        'active_users' => 0,
                        'suspended_users' => 0,
                        'total_meals' => 0,
                        'featured_meals' => 0,
                        'recent_registrations' => 0,
                    ];
                }
            });

            // Get recent activities without complex joins
            // Fixed: Include admin_user_id foreign key in the select for eager loading
            $recentActivities = Cache::remember('admin_recent_activities', 300, function () {
                try {
                    return AdminLog::with('adminUser:id,name,email')
                        ->select(['id', 'admin_user_id', 'action', 'description', 'created_at'])
                        ->latest()
                        ->limit(10)
                        ->get();
                } catch (\Exception $e) {
                    \Log::error('Admin recent activities query failed', ['error' => $e->getMessage()]);

                    return collect([]);
                }
            });

            // Simplified user growth query with date filtering
            $userGrowth = Cache::remember('admin_user_growth', 300, function () {
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
                    \Log::error('Admin user growth query failed', ['error' => $e->getMessage()]);

                    return collect([]);
                }
            });

            // Optimize top meals query
            $topMeals = Cache::remember('admin_top_meals', 300, function () {
                try {
                    return Meal::select(['id', 'name', 'cost', 'cuisine_type', 'difficulty', 'image_path'])
                        ->withCount('mealPlans')
                        ->having('meal_plans_count', '>', 0)
                        ->orderBy('meal_plans_count', 'desc')
                        ->limit(5)
                        ->get();
                } catch (\Exception $e) {
                    \Log::error('Admin top meals query failed', ['error' => $e->getMessage()]);

                    return collect([]);
                }
            });

            return view('admin.dashboard', compact('stats', 'recentActivities', 'userGrowth', 'topMeals'));
        } catch (\Exception $e) {
            \Log::error('Admin dashboard fatal error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return error view or redirect with error message
            return back()->with('error', 'Unable to load dashboard. Please try again or contact support.');
        }
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
            return ['status' => 'error', 'message' => 'Database connection failed: '.$e->getMessage()];
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

        return round($bytes / (1024 ** $pow), 2).' '.$units[$pow];
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

        return match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => $number,
        };
    }
}
