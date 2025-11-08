/**
 * Browser Cache Management for StudEats
 * Helps resolve session and CSRF issues by managing browser cache
 */

class BrowserCacheManager {
    constructor() {
        this.init();
    }

    init() {
        // Detect common cache-related issues
        this.detectCacheIssues();
        
        // Add cache-busting to form submissions
        this.addCacheBustingToForms();
        
        // Monitor for stale page loads
        this.monitorPageFreshness();
    }

    /**
     * Detect potential cache-related issues
     */
    detectCacheIssues() {
        // Check if page was loaded from cache
        if (performance.getEntriesByType('navigation')[0]?.type === 'back_forward') {
            console.warn('[Cache] Page loaded from back/forward cache - potential CSRF issues');
            this.showCacheWarning();
        }

        // Check for stale CSRF tokens
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken && this.isTokenStale(csrfToken)) {
            console.warn('[Cache] Potentially stale CSRF token detected');
            this.refreshToken();
        }
    }

    /**
     * Check if CSRF token appears stale
     */
    isTokenStale(token) {
        // Very basic staleness check - could be enhanced
        const lastRefresh = localStorage.getItem('last_csrf_refresh');
        const now = Date.now();
        const oneHour = 60 * 60 * 1000;
        
        return lastRefresh && (now - parseInt(lastRefresh)) > oneHour;
    }

    /**
     * Show cache warning to user
     */
    showCacheWarning() {
        const warning = document.createElement('div');
        warning.className = 'cache-warning';
        warning.innerHTML = `
            <div style="position: fixed; top: 0; left: 0; right: 0; background: #f59e0b; color: white; padding: 8px; text-align: center; z-index: 10002; font-size: 14px;">
                ⚠️ Page may be cached. If you experience login issues, please 
                <button onclick="window.location.reload(true);" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px; cursor: pointer; margin: 0 4px;">
                    refresh the page
                </button>
                <button onclick="this.closest('.cache-warning').remove();" style="background: none; border: none; color: white; cursor: pointer; float: right; padding: 0 8px;">✕</button>
            </div>
        `;
        document.body.appendChild(warning);
        
        // Auto-dismiss after 10 seconds
        setTimeout(() => {
            if (warning.parentNode) {
                warning.remove();
            }
        }, 10000);
    }

    /**
     * Add cache-busting parameters to forms
     */
    addCacheBustingToForms() {
        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!form.matches('form')) return;

            // Add cache-busting timestamp to non-GET forms
            if (form.method.toUpperCase() !== 'GET') {
                let timestampInput = form.querySelector('input[name="_timestamp"]');
                if (!timestampInput) {
                    timestampInput = document.createElement('input');
                    timestampInput.type = 'hidden';
                    timestampInput.name = '_timestamp';
                    form.appendChild(timestampInput);
                }
                timestampInput.value = Date.now();
            }
        });
    }

    /**
     * Monitor page freshness
     */
    monitorPageFreshness() {
        // Store page load timestamp
        const pageLoadTime = Date.now();
        sessionStorage.setItem('page_load_time', pageLoadTime.toString());
        
        // Check for very old pages (more than 30 minutes)
        const thirtyMinutes = 30 * 60 * 1000;
        
        setInterval(() => {
            const now = Date.now();
            if ((now - pageLoadTime) > thirtyMinutes) {
                this.showStalePageWarning();
            }
        }, 5 * 60 * 1000); // Check every 5 minutes
    }

    /**
     * Show stale page warning
     */
    showStalePageWarning() {
        if (document.querySelector('.stale-page-warning')) {
            return; // Already showing
        }

        const warning = document.createElement('div');
        warning.className = 'stale-page-warning';
        warning.innerHTML = `
            <div style="position: fixed; bottom: 20px; right: 20px; background: #f59e0b; color: white; padding: 16px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000; max-width: 350px; font-size: 14px;">
                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                    <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <strong>Page is getting old</strong>
                </div>
                <p style="margin: 0 0 12px 0; font-size: 13px;">This page has been open for a while. For security and optimal performance, consider refreshing.</p>
                <div style="display: flex; gap: 8px;">
                    <button onclick="window.location.reload();" 
                            style="background: white; color: #f59e0b; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; font-weight: 500;">
                        Refresh Now
                    </button>
                    <button onclick="this.closest('.stale-page-warning').remove();" 
                            style="background: rgba(255,255,255,0.2); color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer;">
                        Dismiss
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(warning);
    }

    /**
     * Force refresh CSRF token
     */
    async refreshToken() {
        try {
            const response = await fetch('/api/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                cache: 'no-cache' // Force fresh request
            });

            if (response.ok) {
                const data = await response.json();
                
                // Update meta tag
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag) {
                    metaTag.setAttribute('content', data.csrf_token);
                }

                // Update all forms
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.csrf_token;
                });

                // Store refresh timestamp
                localStorage.setItem('last_csrf_refresh', Date.now().toString());
                
                console.log('[Cache] CSRF token refreshed due to staleness');
            }
        } catch (error) {
            console.warn('[Cache] Failed to refresh stale token:', error);
        }
    }

    /**
     * Clear all browser cache (where possible)
     */
    static async clearAllCache() {
        try {
            // Clear service worker caches
            if ('caches' in window) {
                const cacheNames = await caches.keys();
                await Promise.all(
                    cacheNames.map(cacheName => caches.delete(cacheName))
                );
                console.log('[Cache] Service worker caches cleared');
            }

            // Clear localStorage items related to forms and sessions
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('form_') || 
                    key.startsWith('csrf_') || 
                    key.startsWith('session_') ||
                    key.includes('cache')) {
                    localStorage.removeItem(key);
                }
            });

            // Clear sessionStorage
            sessionStorage.clear();

            console.log('[Cache] Browser cache cleared');
            return true;
        } catch (error) {
            console.error('[Cache] Failed to clear cache:', error);
            return false;
        }
    }

    /**
     * Clear cache and reload page
     */
    static clearCacheAndReload() {
        this.clearAllCache().then(() => {
            // Force reload from server
            window.location.reload(true);
        });
    }
}

// Initialize cache manager
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.cacheManager = new BrowserCacheManager();
    });
} else {
    window.cacheManager = new BrowserCacheManager();
}

// Global utility functions
window.clearCacheAndReload = () => BrowserCacheManager.clearCacheAndReload();
window.clearBrowserCache = () => BrowserCacheManager.clearAllCache();