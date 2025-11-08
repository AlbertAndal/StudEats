<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    /**
     * Get a fresh CSRF token with comprehensive logging
     */
    public function getCsrfToken(Request $request): JsonResponse
    {
        $token = csrf_token();
        $sessionId = Session::getId();
        
        // Log token generation for security audit
        Log::info('CSRF token requested', [
            'session_id' => $sessionId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
            'token_hash' => hash('sha256', $token), // Log hash, not actual token
        ]);
        
        return response()->json([
            'success' => true,
            'csrf_token' => $token,
            'session_id' => $sessionId,
            'expires_at' => now()->addMinutes(config('session.lifetime'))->toISOString(),
            'timestamp' => now()->toISOString(),
        ], 200, [
            'X-CSRF-Token-Generated' => 'true',
            'X-Session-Active' => 'true',
        ]);
    }

    /**
     * Check session health with comprehensive validation
     */
    public function checkSession(Request $request): JsonResponse
    {
        $sessionActive = Session::isStarted();
        $sessionId = Session::getId();
        $csrfToken = csrf_token();
        
        // Log session check for monitoring
        Log::debug('Session health check', [
            'session_id' => $sessionId,
            'session_active' => $sessionActive,
            'has_csrf_token' => !empty($csrfToken),
            'ip_address' => $request->ip(),
        ]);
        
        return response()->json([
            'success' => true,
            'status' => $sessionActive ? 'active' : 'inactive',
            'session_id' => $sessionId,
            'csrf_token' => $csrfToken,
            'csrf_token_present' => !empty($csrfToken),
            'session_lifetime' => config('session.lifetime'),
            'expires_at' => now()->addMinutes(config('session.lifetime'))->toISOString(),
            'server_time' => now()->toISOString(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Extend/refresh session with secure token rotation
     */
    public function refreshSession(Request $request): JsonResponse
    {
        $oldSessionId = Session::getId();
        
        // Regenerate session ID for security (prevents session fixation)
        Session::regenerate();
        
        $newSessionId = Session::getId();
        $newCsrfToken = csrf_token();
        
        // Log session refresh for security audit
        Log::info('Session refreshed', [
            'old_session_id' => hash('sha256', $oldSessionId),
            'new_session_id' => hash('sha256', $newSessionId),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
        ]);
        
        return response()->json([
            'success' => true,
            'status' => 'refreshed',
            'session_id' => $newSessionId,
            'csrf_token' => $newCsrfToken,
            'expires_at' => now()->addMinutes(config('session.lifetime'))->toISOString(),
            'timestamp' => now()->toISOString(),
            'message' => 'Session and CSRF token successfully refreshed',
        ], 200, [
            'X-Session-Refreshed' => 'true',
            'X-CSRF-Token-Rotated' => 'true',
        ]);
    }
    
    /**
     * Validate CSRF token (for testing purposes)
     */
    public function validateToken(Request $request): JsonResponse
    {
        $providedToken = $request->input('token') ?? $request->header('X-CSRF-TOKEN');
        $sessionToken = csrf_token();
        
        // Timing-safe comparison
        $isValid = hash_equals($sessionToken, $providedToken ?? '');
        
        // Log validation attempt
        Log::info('CSRF token validation', [
            'valid' => $isValid,
            'session_id' => Session::getId(),
            'ip_address' => $request->ip(),
            'timestamp' => now()->toISOString(),
        ]);
        
        return response()->json([
            'success' => true,
            'valid' => $isValid,
            'message' => $isValid ? 'Token is valid' : 'Token is invalid',
            'timestamp' => now()->toISOString(),
        ], $isValid ? 200 : 403);
    }
}