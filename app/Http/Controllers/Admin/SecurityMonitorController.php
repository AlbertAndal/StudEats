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
        $sessionStats = $this->getSessionStatistics();
        
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
     * Get session statistics (private helper)
     */
    private function getSessionStatistics()
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

    /**
     * Get dashboard statistics API
     */
    public function getStats(Request $request)
    {
        $csrfStats = $this->getCsrfErrorStats(now()->subDay());
        $sessionStats = $this->getSessionStatistics();
        $alerts = $this->getSecurityAlerts(now()->subDay());

        return response()->json([
            'csrf_errors_today' => $csrfStats['total'],
            'active_sessions' => $sessionStats['active_sessions'],
            'security_alerts' => count($alerts),
            'session_health' => $sessionStats['error'] ?? 'Good',
        ]);
    }

    /**
     * Get CSRF errors API
     */
    public function getCsrfErrors(Request $request)
    {
        $csrfStats = $this->getCsrfErrorStats(now()->subDay());
        
        $errors = collect($csrfStats['details'])->map(function ($error) {
            // Parse IP and URL from log line if available
            $line = $error['line'];
            $ip = 'N/A';
            $url = 'N/A';
            $userAgent = 'N/A';
            
            // Extract IP if present in log
            if (preg_match('/\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}\b/', $line, $matches)) {
                $ip = $matches[0];
            }
            
            // Extract URL if present
            if (preg_match('/url:([^\s]+)/', $line, $matches)) {
                $url = $matches[1];
            }
            
            return [
                'timestamp' => $error['timestamp']->format('Y-m-d H:i:s'),
                'ip' => $ip,
                'url' => $url,
                'user_agent' => $userAgent,
            ];
        })->take(20); // Limit to 20 recent errors

        return response()->json([
            'errors' => $errors->values()->toArray(),
        ]);
    }

    /**
     * Get session statistics API
     */
    public function getSessionStats(Request $request)
    {
        $sessionStats = $this->getSessionStatistics();
        
        return response()->json([
            'total_sessions_24h' => $sessionStats['total_sessions'],
            'expired_sessions' => max(0, $sessionStats['total_sessions'] - $sessionStats['active_sessions']),
            'avg_session_duration' => config('session.lifetime') . ' min',
            'peak_sessions' => $sessionStats['active_sessions'],
        ]);
    }

    /**
     * Export security report
     */
    public function exportReport(Request $request)
    {
        $csrfStats = $this->getCsrfErrorStats(now()->subWeek());
        $sessionStats = $this->getSessionStatistics();
        $alerts = $this->getSecurityAlerts(now()->subWeek());
        
        $report = [
            'generated_at' => now()->toDateTimeString(),
            'period' => 'Last 7 days',
            'csrf_errors' => $csrfStats,
            'session_stats' => $sessionStats,
            'security_alerts' => $alerts,
        ];
        
        $filename = 'security_report_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($report)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Test security configuration
     */
    public function testSecurity(Request $request)
    {
        $tests = [];
        
        // Test CSRF token functionality
        $tests['csrf_token'] = [
            'status' => csrf_token() ? 'pass' : 'fail',
            'message' => csrf_token() ? 'CSRF token generation working' : 'CSRF token generation failed',
        ];
        
        // Test session functionality
        try {
            DB::table('sessions')->count();
            $tests['sessions'] = [
                'status' => 'pass',
                'message' => 'Session storage accessible',
            ];
        } catch (\Exception $e) {
            $tests['sessions'] = [
                'status' => 'fail',
                'message' => 'Session storage not accessible: ' . $e->getMessage(),
            ];
        }
        
        // Test session configuration
        $lifetime = config('session.lifetime');
        $tests['session_config'] = [
            'status' => $lifetime >= 60 ? 'pass' : 'warning',
            'message' => $lifetime >= 60 ? 'Session lifetime is appropriate' : 'Session lifetime may be too short',
        ];
        
        $allPassed = collect($tests)->every(fn($test) => $test['status'] === 'pass');
        
        return response()->json([
            'success' => true,
            'message' => $allPassed ? 'All security tests passed' : 'Some security tests failed or have warnings',
            'tests' => $tests,
        ]);
    }
}