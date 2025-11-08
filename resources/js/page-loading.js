/**
 * Page Loading Utilities
 * Provides global functions for controlling page loading states
 */

// Loading state management
let loadingTimeout = null;
let isLoading = false;

/**
 * Show the page loading overlay with optional custom message
 * @param {string} message - Custom loading message
 * @param {number} maxDuration - Maximum duration in ms (default: 10000)
 */
export function showPageLoading(message = 'Loading...', maxDuration = 10000) {
    if (typeof window.showPageLoading === 'function') {
        window.showPageLoading(message);
        isLoading = true;
        
        // Clear any existing timeout
        if (loadingTimeout) {
            clearTimeout(loadingTimeout);
        }
        
        // Set failsafe timeout
        loadingTimeout = setTimeout(() => {
            hidePageLoading();
        }, maxDuration);
    }
}

/**
 * Hide the page loading overlay
 */
export function hidePageLoading() {
    if (typeof window.hidePageLoading === 'function') {
        window.hidePageLoading();
        isLoading = false;
        
        // Clear timeout
        if (loadingTimeout) {
            clearTimeout(loadingTimeout);
            loadingTimeout = null;
        }
    }
}

/**
 * Check if loading is currently active
 * @returns {boolean}
 */
export function isPageLoading() {
    return isLoading;
}

/**
 * Navigate to a route with loading indicator
 * @param {string} url - URL to navigate to
 * @param {string} message - Loading message
 */
export function navigateWithLoading(url, message = 'Loading...') {
    showPageLoading(message);
    window.location.href = url;
}

/**
 * Initialize page loading for specific elements
 * @param {string} selector - CSS selector for elements
 * @param {string} message - Loading message
 */
export function initLoadingForElements(selector, message = 'Loading...') {
    document.querySelectorAll(selector).forEach(element => {
        element.addEventListener('click', function(e) {
            if (!element.classList.contains('no-loading')) {
                showPageLoading(message);
            }
        });
    });
}

// Auto-hide loading on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', hidePageLoading);
} else {
    hidePageLoading();
}

window.addEventListener('load', hidePageLoading);

// Export to global scope for backward compatibility
if (typeof window !== 'undefined') {
    window.PageLoadingUtils = {
        show: showPageLoading,
        hide: hidePageLoading,
        isLoading: isPageLoading,
        navigate: navigateWithLoading,
        init: initLoadingForElements
    };
}
