@props([
    'message' => 'Loading...',
    'size' => 'lg', // xs, sm, md, lg, xl
])

<div 
    id="page-loading-overlay" 
    class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-sm hidden"
    role="status" 
    aria-live="polite"
    aria-busy="true"
>
    <div class="flex flex-col items-center gap-4">
        <!-- Animated Spinner -->
        <div class="relative">
            <!-- Outer rotating ring -->
            <div class="absolute inset-0 rounded-full border-4 border-green-200 opacity-25"></div>
            
            <!-- Spinning gradient ring -->
            <div class="relative">
                <svg class="animate-spin {{ $size === 'xl' ? 'w-20 h-20' : ($size === 'lg' ? 'w-16 h-16' : ($size === 'md' ? 'w-12 h-12' : ($size === 'sm' ? 'w-10 h-10' : 'w-8 h-8'))) }}" viewBox="0 0 50 50">
                    <circle 
                        class="stroke-green-600" 
                        cx="25" 
                        cy="25" 
                        r="20" 
                        fill="none" 
                        stroke-width="4"
                        stroke-linecap="round"
                        stroke-dasharray="31.4 31.4"
                        transform="rotate(-90 25 25)"
                    >
                        <animateTransform
                            attributeName="transform"
                            type="rotate"
                            from="0 25 25"
                            to="360 25 25"
                            dur="1s"
                            repeatCount="indefinite"
                        />
                    </circle>
                </svg>
            </div>
            
            <!-- Center logo/icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="{{ $size === 'xl' ? 'w-8 h-8' : ($size === 'lg' ? 'w-6 h-6' : ($size === 'md' ? 'w-5 h-5' : 'w-4 h-4')) }} text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
        </div>
        
        <!-- Loading text -->
        <div class="text-center">
            <p class="text-lg font-semibold text-gray-900 mb-1">{{ $message }}</p>
            <div class="flex items-center justify-center gap-1">
                <span class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                <span class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                <span class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
            </div>
        </div>
    </div>
    
    <span class="sr-only">Loading page content...</span>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    #page-loading-overlay.show {
        display: flex !important;
        animation: fadeIn 0.2s ease-in-out;
    }
    
    #page-loading-overlay.hide {
        animation: fadeOut 0.2s ease-in-out;
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
</style>

@once
@push('scripts')
<script>
(function() {
    const overlay = document.getElementById('page-loading-overlay');
    if (!overlay) return;
    
    // Routes that should show the loading spinner
    const loadingRoutes = ['/dashboard', '/meal-plans', '/recipes'];
    
    // Function to show loading overlay
    window.showPageLoading = function(message = 'Loading...') {
        if (overlay) {
            const messageEl = overlay.querySelector('p');
            if (messageEl) messageEl.textContent = message;
            overlay.classList.remove('hidden', 'hide');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    };
    
    // Function to hide loading overlay
    window.hidePageLoading = function() {
        if (overlay) {
            overlay.classList.remove('show');
            overlay.classList.add('hide');
            document.body.style.overflow = '';
            setTimeout(() => {
                overlay.classList.add('hidden');
                overlay.classList.remove('hide');
            }, 200);
        }
    };
    
    // Check if current path matches loading routes
    function shouldShowLoading(path) {
        return loadingRoutes.some(route => {
            if (route === '/dashboard') return path === route;
            return path.startsWith(route);
        });
    }
    
    // Intercept all link clicks
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href]');
        
        if (link && link.href && !link.hasAttribute('target') && !link.hasAttribute('download')) {
            // Skip if it's an external link
            if (link.hostname !== window.location.hostname) return;
            
            // Skip if it has no-loading class
            if (link.classList.contains('no-loading')) return;
            
            // Skip if it's a hash link
            if (link.getAttribute('href').startsWith('#')) return;
            
            // Get the path
            const url = new URL(link.href);
            const path = url.pathname;
            
            // Show loading if navigating to a loading route
            if (shouldShowLoading(path)) {
                const routeName = path.split('/')[1] || 'page';
                const messages = {
                    'dashboard': 'Loading Dashboard...',
                    'meal-plans': 'Loading Meal Plans...',
                    'recipes': 'Loading Recipes...'
                };
                showPageLoading(messages[routeName] || 'Loading...');
            }
        }
    });
    
    // Intercept form submissions to loading routes
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const action = form.getAttribute('action');
        
        if (action) {
            try {
                const url = new URL(action, window.location.origin);
                const path = url.pathname;
                
                if (shouldShowLoading(path)) {
                    const routeName = path.split('/')[1] || 'page';
                    const messages = {
                        'dashboard': 'Loading Dashboard...',
                        'meal-plans': 'Saving Meal Plan...',
                        'recipes': 'Loading Recipes...'
                    };
                    showPageLoading(messages[routeName] || 'Processing...');
                }
            } catch (err) {
                // Invalid URL, skip
            }
        }
    });
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        const path = window.location.pathname;
        if (shouldShowLoading(path)) {
            const routeName = path.split('/')[1] || 'page';
            const messages = {
                'dashboard': 'Loading Dashboard...',
                'meal-plans': 'Loading Meal Plans...',
                'recipes': 'Loading Recipes...'
            };
            showPageLoading(messages[routeName] || 'Loading...');
        }
    });
    
    // Hide loading when page is fully loaded
    window.addEventListener('load', function() {
        hidePageLoading();
    });
    
    // Also hide on DOMContentLoaded as backup
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', hidePageLoading);
    } else {
        hidePageLoading();
    }
    
    // Hide loading on page show (for browser back button)
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hidePageLoading();
        }
    });
    
    // Failsafe: Hide loading after maximum 10 seconds
    setTimeout(hidePageLoading, 10000);
})();
</script>
@endpush
@endonce
