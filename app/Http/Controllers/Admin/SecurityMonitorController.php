<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SecurityMonitorController extends Controller
{
    /**
     * Show security monitoring dashboard
     */
    public function index(Request $request)
    {
        $timeRange = $request->get('range', '24h');
        
        $startTime = match($timeRange) {
            '1h' => now()->subHour(),
            '24h' => now()->subDay(),
            '7d' => now()->subWeek(),
            '30d' => now()->subMonth(),
            default => now()->subDay(),
        };

        // Get CSRF error statistics from logs
        $csrfErrors = $this->getCsrfErrorStats($startTime);
        
        // Get session statistics
        $sessionStats = $this->getSessionStats();
        
        // Get security alerts
        $alerts = $this->getSecurityAlerts($startTime);

        return view('admin.security-monitor', compact('csrfErrors', 'sessionStats', 'alerts', 'timeRange'));
    }

    /**
     * Get CSRF error statistics from log files
     */
    private function getCsrfErrorStats(Carbon $startTime)
    {
        $logPath = storage_path('logs/laravel.log');
        $errors = [];
        
        if (file_exists($logPath)) {
            $logContent = file_get_contents($logPath);
            $lines = explode("\n", $logContent);
            
            foreach ($lines as $line) {
                if (strpos($line, 'CSRF token mismatch detected') !== false) {
                    // Parse timestamp from log line
                    preg_match('/\[(.*?)\]/', $line, $matches);
                    if (isset($matches[1])) {
                        try {
                            $timestamp = Carbon::parse($matches[1]);
                            if ($timestamp->greaterThan($startTime)) {
                                $errors[] = [
                                    'timestamp' => $timestamp,
                                    'line' => $line
                                ];
                            }
                        } catch (\Exception $e) {
                            // Skip invalid timestamps
                        }
                    }
                }
            }
        }

        return [
            'total' => count($errors),
            'recent' => collect($errors)->filter(fn($error) => $error['timestamp']->greaterThan(now()->subHour()))->count(),
            'hourly_breakdown' => $this->groupErrorsByHour($errors),
            'details' => array_slice($errors, -50), // Last 50 errors
        ];
    }

    /**
     * Group errors by hour for charting
     */
    private function groupErrorsByHour($errors)
    {
        $grouped = [];
        foreach ($errors as $error) {
            $hour = $error['timestamp']->format('H:00');
            $grouped[$hour] = ($grouped[$hour] ?? 0) + 1;
        }
        return $grouped;
    }

    /**
     * Get session statistics
     */
    private function getSessionStats()
    {
        try {
            $sessionCount = DB::table('sessions')->count();
            $activeCount = DB::table('sessions')
                ->where('last_activity', '>', now()->subMinutes(30)->timestamp)
                ->count();
                
            return [
                'total_sessions' => $sessionCount,
                'active_sessions' => $activeCount,
                'session_lifetime' => config('session.lifetime'),
                'driver' => config('session.driver'),
            ];
        } catch (\Exception $e) {
            return [
                'total_sessions' => 'N/A',
                'active_sessions' => 'N/A',
                'session_lifetime' => config('session.lifetime'),
                'driver' => config('session.driver'),
                'error' => 'Unable to query sessions table',
            ];
        }
    }

    /**
     * Get security alerts
     */
    private function getSecurityAlerts(Carbon $startTime)
    {
        $alerts = [];
        
        // Check for high CSRF error rate
        $recentErrors = $this->getCsrfErrorStats(now()->subHour())['total'];
        if ($recentErrors > 10) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "High CSRF error rate: {$recentErrors} errors in the last hour",
                'action' => 'Check for bot activity or session configuration issues',
            ];
        }

        // Check session configuration
        if (config('session.lifetime') < 120) {
            $alerts[] = [
                'type' => 'info',
                'message' => 'Session lifetime is quite short (' . config('session.lifetime') . ' minutes)',
                'action' => 'Consider increasing session lifetime for better user experience',
            ];
        }

        // Check if sessions table exists
        try {
            DB::table('sessions')->count();
        } catch (\Exception $e) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'Sessions table is not accessible',
                'action' => 'Run: php artisan session:table && php artisan migrate',
            ];
        }

        return $alerts;
    }

    /**
     * Clear old sessions (manual cleanup)
     */
    public function clearOldSessions(Request $request)
    {
        try {
            $cutoff = now()->subMinutes(config('session.lifetime'))->timestamp;
            $deleted = DB::table('sessions')->where('last_activity', '<', $cutoff)->delete();
            
            Log::info('Manual session cleanup completed', ['deleted_sessions' => $deleted]);
            
            return response()->json([
                'success' => true,
                'message' => "Cleared {$deleted} expired sessions",
                'deleted' => $deleted,
            ]);
        } catch (\Exception $e) {
            Log::error('Session cleanup failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Session cleanup failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get CSRF error analytics API
     */
    public function getCsrfAnalytics(Request $request)
    {
        $range = $request->get('range', '24h');
        $startTime = match($range) {
            '1h' => now()->subHour(),
            '24h' => now()->subDay(),
            '7d' => now()->subWeek(),
            '30d' => now()->subMonth(),
            default => now()->subDay(),
        };

        $stats = $this->getCsrfErrorStats($startTime);
        
        return response()->json($stats);
    }
}