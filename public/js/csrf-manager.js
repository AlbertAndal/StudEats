/**
 * CSRF Token Management & Session Handling
 * Automatically handles CSRF token refresh and session expiration
 */

class CSRFManager {
    constructor() {
        this.token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.refreshInterval = 1800000; // 30 minutes
        this.warningTime = 300000; // 5 minutes before expiration
        this.sessionWarningShown = false;
        
        this.init();
    }

    init() {
        this.setupTokenRefresh();
        this.setupFormHandlers();
        this.setupAjaxHeaders();
        this.monitorUserActivity();
        this.setupSessionWarning();
    }

    /**
     * Set up automatic token refresh
     */
    setupTokenRefresh() {
        setInterval(() => {
            this.refreshToken();
        }, this.refreshInterval);

        // Refresh token when page becomes visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.refreshToken();
            }
        });
    }

    /**
     * Refresh CSRF token
     */
    async refreshToken() {
        try {
            const response = await fetch('/api/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateToken(data.csrf_token);
                console.log('[CSRF] Token refreshed successfully');
            }
        } catch (error) {
            console.warn('[CSRF] Token refresh failed:', error);
        }
    }

    /**
     * Update CSRF token in DOM
     */
    updateToken(newToken) {
        this.token = newToken;
        
        // Update meta tag
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            metaTag.setAttribute('content', newToken);
        }

        // Update all forms
        document.querySelectorAll('input[name="_token"]').forEach(input => {
            input.value = newToken;
        });

        // Update any data attributes
        document.querySelectorAll('[data-csrf]').forEach(element => {
            element.setAttribute('data-csrf', newToken);
        });
    }

    /**
     * Set up form handlers for better error handling
     */
    setupFormHandlers() {
        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!form.matches('form')) return;

            // Add loading state
            const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                const originalText = submitButton.textContent || submitButton.value;
                submitButton.textContent = 'Processing...';
                
                // Restore button state if form submission fails
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }, 10000);
            }

            // Ensure CSRF token is present
            const csrfInput = form.querySelector('input[name="_token"]');
            if (csrfInput && !csrfInput.value) {
                csrfInput.value = this.token;
            }
        });

        // Handle form errors (419 specifically)
        window.addEventListener('beforeunload', () => {
            // Save form data to localStorage for recovery
            document.querySelectorAll('form[data-persist]').forEach(form => {
                const formData = new FormData(form);
                const data = {};
                for (let [key, value] of formData.entries()) {
                    if (key !== '_token') { // Don't save CSRF tokens
                        data[key] = value;
                    }
                }
                localStorage.setItem(`form_${form.id || 'default'}`, JSON.stringify(data));
            });
        });
    }

    /**
     * Set up AJAX request headers
     */
    setupAjaxHeaders() {
        // Fetch API interceptor
        const originalFetch = window.fetch;
        window.fetch = (input, init = {}) => {
            // Add CSRF token to POST requests
            if (!init.method || ['POST', 'PUT', 'PATCH', 'DELETE'].includes(init.method.toUpperCase())) {
                init.headers = {
                    'X-CSRF-TOKEN': this.token,
                    ...init.headers
                };
            }
            
            return originalFetch(input, init).catch(error => {
                if (error.status === 419) {
                    this.handle419Error();
                }
                throw error;
            });
        };

        // jQuery AJAX setup (if jQuery is available)
        if (window.jQuery) {
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': this.token
                },
                error: (xhr) => {
                    if (xhr.status === 419) {
                        this.handle419Error();
                    }
                }
            });
        }
    }

    /**
     * Monitor user activity for session management
     */
    monitorUserActivity() {
        let activityTimer;
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        const resetTimer = () => {
            clearTimeout(activityTimer);
            this.sessionWarningShown = false;
            
            // Set timer for session warning
            activityTimer = setTimeout(() => {
                this.showSessionWarning();
            }, this.refreshInterval - this.warningTime);
        };

        events.forEach(event => {
            document.addEventListener(event, resetTimer, true);
        });

        resetTimer(); // Initialize timer
    }

    /**
     * Show session expiration warning
     */
    showSessionWarning() {
        if (this.sessionWarningShown) return;
        this.sessionWarningShown = true;

        const warning = this.createWarningElement();
        document.body.appendChild(warning);

        // Auto-dismiss after 5 minutes
        setTimeout(() => {
            if (warning.parentNode) {
                warning.remove();
            }
        }, this.warningTime);
    }

    /**
     * Create session warning element
     */
    createWarningElement() {
        const div = document.createElement('div');
        div.className = 'csrf-session-warning';
        div.innerHTML = `
            <div style="position: fixed; top: 20px; right: 20px; background: #f59e0b; color: white; padding: 16px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000; max-width: 350px;">
                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                    <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <strong>Session Expiring Soon</strong>
                </div>
                <p style="margin: 0 0 12px 0; font-size: 14px;">Your session will expire in 5 minutes. Any unsaved changes may be lost.</p>
                <div style="display: flex; gap: 8px;">
                    <button onclick="csrfManager.refreshToken(); this.closest('.csrf-session-warning').remove();" 
                            style="background: white; color: #f59e0b; border: none; padding: 8px 16px; border-radius: 4px; font-size: 12px; cursor: pointer;">
                        Extend Session
                    </button>
                    <button onclick="this.closest('.csrf-session-warning').remove();" 
                            style="background: rgba(255,255,255,0.2); color: white; border: none; padding: 8px 16px; border-radius: 4px; font-size: 12px; cursor: pointer;">
                        Dismiss
                    </button>
                </div>
            </div>
        `;
        return div;
    }

    /**
     * Set up session warning system
     */
    setupSessionWarning() {
        // Check for session expiration every minute
        setInterval(() => {
            this.checkSessionHealth();
        }, 60000);
    }

    /**
     * Check session health
     */
    async checkSessionHealth() {
        try {
            const response = await fetch('/api/session-check', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Session check failed');
            }
        } catch (error) {
            console.warn('[Session] Health check failed:', error);
        }
    }

    /**
     * Handle 419 errors
     */
    handle419Error() {
        // Prevent multiple modals
        if (document.querySelector('.csrf-419-modal')) {
            return;
        }

        // Save current form data before showing modal
        this.saveAllFormData();

        // Show user-friendly message
        const message = document.createElement('div');
        message.className = 'csrf-419-modal';
        message.innerHTML = `
            <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 24px; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.3); z-index: 10001; text-align: center; max-width: 400px; font-family: system-ui, -apple-system, sans-serif;">
                <div style="color: #f59e0b; font-size: 48px; margin-bottom: 16px;">⚠️</div>
                <h3 style="margin: 0 0 12px 0; color: #374151; font-size: 18px; font-weight: 600;">Session Expired</h3>
                <p style="margin: 0 0 20px 0; color: #6b7280; font-size: 14px; line-height: 1.5;">Your session has expired for security reasons. Don't worry - your form data has been saved and will be restored when you refresh.</p>
                <div style="display: flex; gap: 8px; justify-content: center;">
                    <button onclick="csrfManager.refreshPageWithRetry();" 
                            style="background: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; cursor: pointer; font-weight: 500;">
                        Refresh & Continue
                    </button>
                    <button onclick="this.closest('.csrf-419-modal').remove();" 
                            style="background: #6b7280; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; cursor: pointer; font-weight: 500;">
                        Dismiss
                    </button>
                </div>
            </div>
            <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 10000;" onclick="this.parentNode.remove();"></div>
        `;
        document.body.appendChild(message);
    }

    /**
     * Save all form data before session expires
     */
    saveAllFormData() {
        document.querySelectorAll('form[data-persist], form#admin-login-form, form[id*="login"]').forEach(form => {
            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                if (key !== '_token' && key !== 'password') { // Don't save sensitive data
                    data[key] = value;
                }
            }
            if (Object.keys(data).length > 0) {
                const formId = form.id || form.name || 'form_' + Date.now();
                localStorage.setItem(`csrf_recovery_${formId}`, JSON.stringify({
                    data: data,
                    timestamp: Date.now(),
                    url: window.location.href
                }));
            }
        });
    }

    /**
     * Refresh page with retry mechanism
     */
    refreshPageWithRetry() {
        // Clear any existing CSRF tokens from localStorage
        Object.keys(localStorage).forEach(key => {
            if (key.startsWith('csrf_token_')) {
                localStorage.removeItem(key);
            }
        });

        // Add cache-busting parameter and reload
        const url = new URL(window.location.href);
        url.searchParams.set('_refresh', Date.now());
        window.location.href = url.toString();
    }

    /**
     * Restore form data from localStorage
     */
    restoreFormData(formId) {
        // Try new format first
        const newFormatKey = `csrf_recovery_${formId}`;
        const newFormatData = localStorage.getItem(newFormatKey);
        
        if (newFormatData) {
            try {
                const savedData = JSON.parse(newFormatData);
                const form = document.getElementById(formId) || document.querySelector('form[data-persist]');
                
                if (form && savedData.data) {
                    Object.entries(savedData.data).forEach(([key, value]) => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input && input.type !== 'password') {
                            input.value = value;
                        }
                    });
                    
                    // Show restoration notification
                    this.showRestorationNotification();
                }
                
                localStorage.removeItem(newFormatKey);
                return;
            } catch (error) {
                console.warn('[CSRF] Failed to restore form data (new format):', error);
            }
        }
        
        // Fallback to old format
        const oldFormatKey = `form_${formId || 'default'}`;
        const oldFormatData = localStorage.getItem(oldFormatKey);
        
        if (oldFormatData) {
            try {
                const data = JSON.parse(oldFormatData);
                const form = document.getElementById(formId) || document.querySelector('form[data-persist]');
                
                if (form) {
                    Object.entries(data).forEach(([key, value]) => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input && input.type !== 'password') {
                            input.value = value;
                        }
                    });
                }
                
                localStorage.removeItem(oldFormatKey);
            } catch (error) {
                console.warn('[CSRF] Failed to restore form data (old format):', error);
            }
        }
    }

    /**
     * Show form data restoration notification
     */
    showRestorationNotification() {
        const notification = document.createElement('div');
        notification.innerHTML = `
            <div style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background: #10b981; color: white; padding: 12px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000; font-size: 14px;">
                ✓ Form data restored after session refresh
            </div>
        `;
        document.body.appendChild(notification);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }
}

// Initialize CSRF manager when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.csrfManager = new CSRFManager();
    });
} else {
    window.csrfManager = new CSRFManager();
}