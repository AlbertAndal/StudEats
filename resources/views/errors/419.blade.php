@extends('layouts.guest')

@section('title', 'Session Expired')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4">
    <div class="max-w-md w-full">
        <!-- Error Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Session Expired</h1>
            <p class="text-gray-600 mb-6">Your session has expired for security reasons. This happens to protect your account when you've been inactive for a while.</p>
        </div>

        <!-- Error Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="text-center">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">What happened?</h2>
                <p class="text-sm text-gray-600 mb-4">
                    Your security token has expired. This is a normal security measure that protects against unauthorized access to your account.
                </p>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 text-left mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Don't worry!</strong> Any form data you entered may have been preserved. Simply refresh the page and try again.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <!-- Primary Action -->
            <button onclick="handleRefresh()" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh Page & Try Again
            </button>

            <!-- Secondary Actions -->
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('login') }}" 
                   class="text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    User Login
                </a>
                <a href="{{ route('admin.login') }}" 
                   class="text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Admin Login
                </a>
            </div>

            <!-- Home Link -->
            <div class="text-center">
                <a href="{{ route('welcome') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                    ← Back to StudEats Homepage
                </a>
            </div>
        </div>

        <!-- Technical Details (Collapsible) -->
        <div class="mt-8 border-t pt-6">
            <button onclick="toggleDetails()" class="text-sm text-gray-500 hover:text-gray-700 flex items-center">
                <svg id="details-icon" class="w-4 h-4 mr-1 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                Technical Details
            </button>
            <div id="technical-details" class="hidden mt-3 text-xs text-gray-500 bg-gray-50 rounded p-3">
                <p><strong>Error Code:</strong> 419 - Page Expired</p>
                <p><strong>Cause:</strong> CSRF token mismatch or session timeout</p>
                <p><strong>Time:</strong> {{ now()->format('Y-m-d H:i:s T') }}</p>
                <p><strong>URL:</strong> {{ request()->fullUrl() }}</p>
                @if(request()->header('referer'))
                <p><strong>Referrer:</strong> {{ request()->header('referer') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
let refreshAttempts = 0;
const maxRefreshAttempts = 3;

function handleRefresh() {
    refreshAttempts++;
    
    if (refreshAttempts <= maxRefreshAttempts) {
        // Show loading state
        const button = event.target;
        const originalContent = button.innerHTML;
        button.innerHTML = `
            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Refreshing...
        `;
        button.disabled = true;
        
        // Clear any cached data
        if ('caches' in window) {
            caches.keys().then(function(names) {
                for (let name of names) {
                    caches.delete(name);
                }
            });
        }
        
        // Clear localStorage data related to forms
        Object.keys(localStorage).forEach(key => {
            if (key.startsWith('form_')) {
                localStorage.removeItem(key);
            }
        });
        
        // Reload the page with cache-busting
        setTimeout(() => {
            window.location.href = window.location.href + (window.location.href.includes('?') ? '&' : '?') + '_t=' + Date.now();
        }, 1000);
    } else {
        // Too many attempts, redirect to login
        alert('Multiple refresh attempts failed. Redirecting to login page...');
        window.location.href = '{{ route("login") }}';
    }
}

function toggleDetails() {
    const details = document.getElementById('technical-details');
    const icon = document.getElementById('details-icon');
    
    if (details.classList.contains('hidden')) {
        details.classList.remove('hidden');
        icon.style.transform = 'rotate(90deg)';
    } else {
        details.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Auto-refresh after 10 seconds if no user interaction
let autoRefreshTimer = setTimeout(() => {
    if (refreshAttempts === 0) {
        console.log('Auto-refreshing due to inactivity...');
        handleRefresh();
    }
}, 10000);

// Clear auto-refresh if user interacts with page
document.addEventListener('click', () => {
    clearTimeout(autoRefreshTimer);
});

document.addEventListener('keydown', () => {
    clearTimeout(autoRefreshTimer);
});

// Log error for debugging (in non-production environments)
@if(!app()->environment('production'))
console.warn('419 Page Expired Error Details:', {
    url: '{{ request()->fullUrl() }}',
    method: '{{ request()->method() }}',
    referrer: '{{ request()->header("referer") }}',
    userAgent: '{{ request()->userAgent() }}',
    timestamp: '{{ now()->toISOString() }}'
});
@endif
</script>

@endsection