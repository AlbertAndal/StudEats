<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Meal;
use App\Models\MealPlan;
use App\Models\AdminLog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    /**
     * Get real-time analytics data
     */
    public function getData(): JsonResponse
    {
        try {
            $analytics = Cache::remember('admin_analytics', 60, function () {
                return [
                    'users' => $this->getUserAnalytics(),
                    'meals' => $this->getMealAnalytics(),
                    'activity' => $this->getActivityAnalytics(),
                    'trends' => $this->getTrendAnalytics(),
                    'system' => $this->getSystemMetrics(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $analytics,
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            Log::error('Analytics data retrieval failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to load analytics data',
            ], 500);
        }
    }

    /**
     * Get user analytics
     */
    private function getUserAnalytics(): array
    {
        $now = Carbon::now();
        $weekAgo = $now->copy()->subWeek();
        $monthAgo = $now->copy()->subMonth();

        return [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'new_today' => User::whereDate('created_at', $now->toDateString())->count(),
            'new_this_week' => User::where('created_at', '>=', $weekAgo)->count(),
            'new_this_month' => User::where('created_at', '>=', $monthAgo)->count(),
            'suspended' => User::whereNotNull('suspended_at')->count(),
            'by_role' => User::select('role', DB::raw('count(*) as count'))
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray(),
            'growth_rate' => $this->calculateGrowthRate('users', 'created_at'),
        ];
    }

    /**
     * Get meal analytics
     */
    private function getMealAnalytics(): array
    {
        $now = Carbon::now();
        $weekAgo = $now->copy()->subWeek();

        return [
            'total' => Meal::count(),
            'featured' => Meal::where('is_featured', true)->count(),
            'new_this_week' => Meal::where('created_at', '>=', $weekAgo)->count(),
            'by_cuisine' => Meal::select('cuisine_type', DB::raw('count(*) as count'))
                ->groupBy('cuisine_type')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'cuisine_type')
                ->toArray(),
            'by_difficulty' => Meal::select('difficulty', DB::raw('count(*) as count'))
                ->groupBy('difficulty')
                ->pluck('count', 'difficulty')
                ->toArray(),
            'avg_cost' => round(Meal::avg('cost'), 2),
            'avg_calories' => round(Meal::avg('calories'), 0),
            'popular' => Meal::withCount('mealPlans')
                ->orderByDesc('meal_plans_count')
                ->limit(5)
                ->get(['id', 'name', 'cuisine_type'])
                ->map(function ($meal) {
                    return [
                        'id' => $meal->id,
                        'name' => $meal->name,
                        'cuisine' => $meal->cuisine_type,
                        'plans_count' => $meal->meal_plans_count,
                    ];
                })
                ->toArray(),
        ];
    }

    /**
     * Get activity analytics
     */
    private function getActivityAnalytics(): array
    {
        $now = Carbon::now();
        $todayStart = $now->copy()->startOfDay();
        $weekAgo = $now->copy()->subWeek();

        $totalPlans = MealPlan::count();
        $completedPlans = MealPlan::where('is_completed', true)->count();

        return [
            'meal_plans_total' => $totalPlans,
            'meal_plans_completed' => $completedPlans,
            'meal_plans_pending' => $totalPlans - $completedPlans,
            'completion_rate' => $totalPlans > 0 ? round(($completedPlans / $totalPlans) * 100, 1) : 0,
            'plans_today' => MealPlan::where('scheduled_date', $todayStart->toDateString())->count(),
            'plans_this_week' => MealPlan::where('scheduled_date', '>=', $weekAgo->toDateString())->count(),
            'by_meal_type' => MealPlan::select('meal_type', DB::raw('count(*) as count'))
                ->groupBy('meal_type')
                ->pluck('count', 'meal_type')
                ->toArray(),
            'admin_actions_today' => AdminLog::whereDate('created_at', $now->toDateString())->count(),
            'admin_actions_week' => AdminLog::where('created_at', '>=', $weekAgo)->count(),
        ];
    }

    /**
     * Get trend analytics (last 7 days)
     */
    private function getTrendAnalytics(): array
    {
        $days = [];
        $userRegistrations = [];
        $mealPlansCreated = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $days[] = Carbon::parse($date)->format('M d');

            $userRegistrations[] = User::whereDate('created_at', $date)->count();
            $mealPlansCreated[] = MealPlan::whereDate('created_at', $date)->count();
        }

        return [
            'labels' => $days,
            'datasets' => [
                'user_registrations' => $userRegistrations,
                'meal_plans_created' => $mealPlansCreated,
            ],
        ];
    }

    /**
     * Get system metrics
     */
    private function getSystemMetrics(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        $memoryPercentage = $memoryLimit > 0 ? round(($memoryUsage / $memoryLimit) * 100, 1) : 0;

        return [
            'memory_usage' => [
                'used' => $this->formatBytes($memoryUsage),
                'limit' => $this->formatBytes($memoryLimit),
                'percentage' => $memoryPercentage,
            ],
            'database' => [
                'status' => $this->checkDatabaseConnection() ? 'healthy' : 'error',
                'tables' => $this->getTableCount(),
            ],
            'cache' => [
                'status' => 'operational',
                'driver' => config('cache.default'),
            ],
            'queue' => [
                'pending_jobs' => DB::table('jobs')->count(),
                'failed_jobs' => DB::table('failed_jobs')->count(),
            ],
        ];
    }

    /**
     * Calculate growth rate
     */
    private function calculateGrowthRate(string $table, string $dateColumn): float
    {
        $thisWeek = DB::table($table)
            ->where($dateColumn, '>=', Carbon::now()->subWeek())
            ->count();

        $lastWeek = DB::table($table)
            ->whereBetween($dateColumn, [
                Carbon::now()->subWeeks(2),
                Carbon::now()->subWeek(),
            ])
            ->count();

        if ($lastWeek === 0) {
            return $thisWeek > 0 ? 100.0 : 0.0;
        }

        return round((($thisWeek - $lastWeek) / $lastWeek) * 100, 1);
    }

    /**
     * Check database connection
     */
    private function checkDatabaseConnection(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get table count
     */
    private function getTableCount(): int
    {
        try {
            $tables = DB::select('SHOW TABLES');
            return count($tables);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Parse memory limit string
     */
    private function parseMemoryLimit(string $limit): int
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $value = (int) $limit;

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get hourly activity data
     */
    public function getHourlyActivity(): JsonResponse
    {
        try {
            $hourlyData = Cache::remember('hourly_activity', 300, function () {
                $hours = [];
                $activity = [];

                for ($i = 23; $i >= 0; $i--) {
                    $hour = Carbon::now()->subHours($i);
                    $hours[] = $hour->format('H:00');

                    $activity[] = AdminLog::whereBetween('created_at', [
                        $hour->startOfHour(),
                        $hour->copy()->endOfHour(),
                    ])->count();
                }

                return [
                    'labels' => $hours,
                    'data' => $activity,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $hourlyData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load hourly activity',
            ], 500);
        }
    }

    /**
     * Refresh analytics cache
     */
    public function refresh(): JsonResponse
    {
        try {
            Cache::forget('admin_analytics');
            Cache::forget('hourly_activity');

            return response()->json([
                'success' => true,
                'message' => 'Analytics cache refreshed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to refresh analytics',
            ], 500);
        }
    }
}
