<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    /**
     * Get a fresh CSRF token
     */
    public function getCsrfToken(): JsonResponse
    {
        return response()->json([
            'csrf_token' => csrf_token(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Check session health
     */
    public function checkSession(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'active',
            'session_id' => Session::getId(),
            'expires_at' => now()->addMinutes(config('session.lifetime'))->toISOString(),
            'csrf_token' => csrf_token(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Extend/refresh session
     */
    public function refreshSession(Request $request): JsonResponse
    {
        // Regenerate session ID for security
        Session::regenerate();
        
        return response()->json([
            'status' => 'refreshed',
            'session_id' => Session::getId(),
            'csrf_token' => csrf_token(),
            'expires_at' => now()->addMinutes(config('session.lifetime'))->toISOString(),
            'timestamp' => now()->toISOString(),
        ]);
    }
}