/**
 * LoadingIndicator - Vanilla JavaScript Loading Component
 * 
 * A versatile loading indicator that works with Blade templates and vanilla JavaScript.
 * Supports multiple variants, themes, auto-cycling messages, and accessibility features.
 */

// Import the main LoadingIndicator class
import './components/LoadingIndicator.js';

/**
 * Utility functions for common loading operations
 */
window.LoadingUtils = {
    /**
     * Show a loading indicator in a specific container
     * @param {string|Element} container - CSS selector or DOM element
     * @param {Object} options - LoadingIndicator options
     * @returns {LoadingIndicator} - The loading indicator instance
     */
    show(container, options = {}) {
        const containerEl = typeof container === 'string' 
            ? document.querySelector(container) 
            : container;
            
        if (!containerEl) {
            console.error('LoadingUtils.show: Container not found');
            return null;
        }
        
        const loader = new LoadingIndicator({
            container: containerEl,
            ...options
        });
        
        loader.show();
        return loader;
    },
    
    /**
     * Show a full-screen loading overlay
     * @param {Object} options - LoadingIndicator options
     * @returns {Object} - Object with loader and hide method
     */
    showOverlay(options = {}) {
        return LoadingIndicator.createOverlay(options);
    },
    
    /**
     * Show loading on a button
     * @param {string|Element} button - CSS selector or button element
     * @param {Object} options - Options
     * @returns {Function} - Function to restore button
     */
    showOnButton(button, options = {}) {
        const btnEl = typeof button === 'string' 
            ? document.querySelector(button) 
            : button;
            
        if (!btnEl) {
            console.error('LoadingUtils.showOnButton: Button not found');
            return () => {};
        }
        
        const originalText = btnEl.innerHTML;
        const originalDisabled = btnEl.disabled;
        
        const loadingText = options.text || 'Loading...';
        const size = options.size || 'small';
        const spinnerSize = size === 'small' ? 'w-4 h-4' : size === 'large' ? 'w-6 h-6' : 'w-5 h-5';
        
        btnEl.disabled = true;
        btnEl.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 ${spinnerSize} text-current" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            ${loadingText}
        `;
        
        return () => {
            btnEl.innerHTML = originalText;
            btnEl.disabled = originalDisabled;
        };
    },
    
    /**
     * Create a loading state manager for async operations
     * @param {Object} options - Configuration options
     * @returns {Object} - Loading state manager
     */
    createStateManager(options = {}) {
        const {
            messages = ['Please wait...', 'Processing...', 'Fetching data...'],
            minLoadingTime = 0,
            onStageComplete = null,
            onComplete = null
        } = options;
        
        let currentMessageIndex = 0;
        let startTime = null;
        let isLoading = false;
        let activeLoader = null;
        
        return {
            get isLoading() { return isLoading; },
            get currentMessage() { return messages[currentMessageIndex]; },
            get currentMessageIndex() { return currentMessageIndex; },
            get progress() { 
                return messages.length > 1 
                    ? ((currentMessageIndex + 1) / messages.length) * 100 
                    : 0;
            },
            
            start(container, loaderOptions = {}) {
                if (isLoading) return;
                
                isLoading = true;
                startTime = Date.now();
                currentMessageIndex = 0;
                
                if (container) {
                    activeLoader = LoadingUtils.show(container, {
                        messages,
                        ...loaderOptions
                    });
                }
            },
            
            async stop() {
                if (!isLoading) return;
                
                if (minLoadingTime > 0 && startTime) {
                    const elapsed = Date.now() - startTime;
                    const remaining = minLoadingTime - elapsed;
                    
                    if (remaining > 0) {
                        await new Promise(resolve => setTimeout(resolve, remaining));
                    }
                }
                
                isLoading = false;
                currentMessageIndex = 0;
                startTime = null;
                
                if (activeLoader) {
                    activeLoader.hide();
                    activeLoader = null;
                }
                
                if (onComplete) {
                    onComplete();
                }
            },
            
            setStage(index) {
                if (index >= 0 && index < messages.length) {
                    currentMessageIndex = index;
                    
                    if (activeLoader) {
                        activeLoader.setMessage(index);
                    }
                    
                    if (onStageComplete) {
                        onStageComplete(index);
                    }
                }
            },
            
            nextStage() {
                if (currentMessageIndex < messages.length - 1) {
                    this.setStage(currentMessageIndex + 1);
                }
            },
            
            async withLoading(operation, container = null, loaderOptions = {}) {
                try {
                    this.start(container, loaderOptions);
                    const result = await operation();
                    await this.stop();
                    return result;
                } catch (error) {
                    await this.stop();
                    throw error;
                }
            }
        };
    }
};

/**
 * Common loading messages for StudEats application
 */
window.LoadingMessages = {
    MEAL_PLANNING: [
        "Analyzing your preferences...",
        "Calculating nutritional requirements...",
        "Selecting recipes...",
        "Generating meal plan...",
        "Finalizing recommendations..."
    ],
    
    RECIPE_PROCESSING: [
        "Processing recipe data...",
        "Calculating nutrition values...",
        "Optimizing ingredients...",
        "Saving recipe..."
    ],
    
    DATA_SYNC: [
        "Connecting to server...",
        "Syncing your data...",
        "Updating preferences...",
        "Finalizing sync..."
    ],
    
    FILE_UPLOAD: [
        "Preparing upload...",
        "Uploading file...",
        "Processing data...",
        "Completing upload..."
    ],
    
    PRICE_FETCH: [
        "Fetching market prices...",
        "Calculating costs...",
        "Updating totals..."
    ],
    
    GENERAL: [
        "Please wait...",
        "Processing...",
        "Fetching data...",
        "Retrieving information...",
        "Preparing content...",
        "Initializing..."
    ]
};

// Initialize after DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('StudEats Loading Indicator System initialized');
});
