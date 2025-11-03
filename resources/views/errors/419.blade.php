@extends('layouts.guest')

@section('title', '419 - Page Expired')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-24 w-24 text-red-500">
                <!-- Clock Icon -->
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Session Expired
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Your session has expired for security reasons. Please refresh the page and try again.
            </p>
        </div>

        <div class="mt-8 space-y-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            What happened?
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Your security token expired. This happens when:</p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>You've been inactive for too long</li>
                                <li>Your browser was closed and reopened</li>
                                <li>The page was left open for an extended period</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col space-y-3">
                <button onclick="refreshPage()" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh Page & Try Again
                </button>

                <div class="text-center">
                    <span class="text-gray-500 text-sm">or</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('welcome') }}" 
                       class="flex justify-center items-center py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Home
                    </a>

                    <a href="{{ route('login') }}" 
                       class="flex justify-center items-center py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Login
                    </a>
                </div>

                <!-- Admin Login Button (if accessing admin routes) -->
                @if(request()->is('admin*'))
                <a href="{{ route('admin.login') }}" 
                   class="w-full flex justify-center py-2 px-4 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Admin Login
                </a>
                @endif
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400">
                Error Code: 419 | StudEats Security System
            </p>
        </div>
    </div>
</div>

<script>
function refreshPage() {
    // Clear any cached form data
    if (typeof Storage !== 'undefined') {
        // Clear localStorage form data (if any)
        for (let i = localStorage.length - 1; i >= 0; i--) {
            const key = localStorage.key(i);
            if (key && key.startsWith('form_')) {
                localStorage.removeItem(key);
            }
        }
    }
    
    // Reload the page
    window.location.reload(true);
}

// Auto-refresh after 30 seconds if user doesn't take action
setTimeout(function() {
    const refreshBtn = document.querySelector('button[onclick="refreshPage()"]');
    if (refreshBtn && !document.hidden) {
        refreshBtn.textContent = 'Auto-refreshing...';
        refreshBtn.disabled = true;
        setTimeout(refreshPage, 2000);
    }
}, 30000);
</script>
@endsection