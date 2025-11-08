/**
 * Modal Utilities for StudEats Application
 * 
 * Provides functionality for showing confirmation modals, managing state,
 * and handling animations across the application.
 */

// Modal configuration and state management
window.StudEatsModal = {
    // Store active modals
    activeModals: new Set(),
    
    // Default configuration
    defaults: {
        animation: true,
        closeOnEscape: true,
        closeOnOverlay: true,
        focusManagement: true,
        animationDuration: 300
    },
    
    /**
     * Show a modal with optional configuration
     * @param {string} modalId - The ID of the modal element
     * @param {Object} config - Configuration options
     */
    show(modalId, config = {}) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error(`Modal with ID "${modalId}" not found`);
            return;
        }
        
        const options = { ...this.defaults, ...config };
        
        // Update modal content if provided
        this.updateContent(modal, config);
        
        // Show modal
        modal.classList.remove('hidden');
        this.activeModals.add(modalId);
        
        // Handle animations
        if (options.animation) {
            this.animateIn(modal, options.animationDuration);
        }
        
        // Focus management
        if (options.focusManagement) {
            this.manageFocus(modal);
        }
        
        // Store previous focus for restoration
        modal.dataset.previousFocus = document.activeElement ? document.activeElement.id : '';
        
        return modal;
    },
    
    /**
     * Hide a modal
     * @param {string} modalId - The ID of the modal element
     * @param {Object} options - Hide options
     */
    hide(modalId, options = {}) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        const config = { ...this.defaults, ...options };
        
        this.activeModals.delete(modalId);
        
        if (config.animation) {
            this.animateOut(modal, config.animationDuration, () => {
                modal.classList.add('hidden');
                this.restoreFocus(modal);
            });
        } else {
            modal.classList.add('hidden');
            this.restoreFocus(modal);
        }
    },
    
    /**
     * Update modal content dynamically
     * @param {HTMLElement} modal - The modal element
     * @param {Object} content - Content to update
     */
    updateContent(modal, content) {
        if (content.title) {
            const titleElement = modal.querySelector('[id$="modal-title"], .modal-title, h3');
            if (titleElement) titleElement.textContent = content.title;
        }
        
        if (content.message) {
            const messageElement = modal.querySelector('.modal-message, .modal-content p');
            if (messageElement) messageElement.textContent = content.message;
        }
        
        if (content.html) {
            const contentElement = modal.querySelector('.modal-content, .modal-body');
            if (contentElement) contentElement.innerHTML = content.html;
        }
    },
    
    /**
     * Animate modal entrance
     * @param {HTMLElement} modal - The modal element
     * @param {number} duration - Animation duration in ms
     */
    animateIn(modal, duration = 300) {
        const overlay = modal.querySelector('.bg-gray-500, .bg-black');
        const panel = modal.querySelector('.bg-white, .modal-panel');
        
        if (overlay && panel) {
            // Initial state
            overlay.style.opacity = '0';
            panel.style.transform = 'scale(0.95)';
            panel.style.opacity = '0';
            
            // Set transitions
            overlay.style.transition = `opacity ${duration}ms ease-out`;
            panel.style.transition = `all ${duration}ms ease-out`;
            
            // Animate to final state
            requestAnimationFrame(() => {
                overlay.style.opacity = '0.75';
                panel.style.transform = 'scale(1)';
                panel.style.opacity = '1';
            });
        }
    },
    
    /**
     * Animate modal exit
     * @param {HTMLElement} modal - The modal element
     * @param {number} duration - Animation duration in ms
     * @param {Function} callback - Callback after animation
     */
    animateOut(modal, duration = 200, callback) {
        const overlay = modal.querySelector('.bg-gray-500, .bg-black');
        const panel = modal.querySelector('.bg-white, .modal-panel');
        
        if (overlay && panel) {
            // Set transitions
            overlay.style.transition = `opacity ${duration}ms ease-in`;
            panel.style.transition = `all ${duration}ms ease-in`;
            
            // Animate to exit state
            overlay.style.opacity = '0';
            panel.style.transform = 'scale(0.95)';
            panel.style.opacity = '0';
            
            setTimeout(callback, duration);
        } else if (callback) {
            callback();
        }
    },
    
    /**
     * Manage focus for accessibility
     * @param {HTMLElement} modal - The modal element
     */
    manageFocus(modal) {
        setTimeout(() => {
            // Focus on first focusable element or first button
            const focusableElements = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
        }, 100);
    },
    
    /**
     * Restore focus to previous element
     * @param {HTMLElement} modal - The modal element
     */
    restoreFocus(modal) {
        const previousFocusId = modal.dataset.previousFocus;
        if (previousFocusId) {
            const previousElement = document.getElementById(previousFocusId);
            if (previousElement) {
                previousElement.focus();
            }
        }
    },
    
    /**
     * Create a confirmation modal programmatically
     * @param {Object} config - Modal configuration
     * @returns {Promise} - Resolves with true/false based on user choice
     */
    confirm(config) {
        return new Promise((resolve) => {
            const modalId = config.id || 'dynamicConfirmModal';
            const existingModal = document.getElementById(modalId);
            
            if (existingModal) {
                existingModal.remove();
            }
            
            // Create modal HTML
            const modalHTML = this.createConfirmModalHTML(modalId, config);
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            const modal = document.getElementById(modalId);
            
            // Set up event listeners
            const confirmBtn = modal.querySelector('.confirm-btn');
            const cancelBtn = modal.querySelector('.cancel-btn');
            const overlay = modal.querySelector('.modal-overlay');
            
            const cleanup = () => {
                modal.remove();
            };
            
            confirmBtn.addEventListener('click', () => {
                this.hide(modalId);
                setTimeout(() => {
                    cleanup();
                    resolve(true);
                }, 200);
            });
            
            cancelBtn.addEventListener('click', () => {
                this.hide(modalId);
                setTimeout(() => {
                    cleanup();
                    resolve(false);
                }, 200);
            });
            
            overlay.addEventListener('click', () => {
                this.hide(modalId);
                setTimeout(() => {
                    cleanup();
                    resolve(false);
                }, 200);
            });
            
            // Show the modal
            this.show(modalId, config);
        });
    },
    
    /**
     * Create HTML for confirmation modal
     * @param {string} modalId - Modal ID
     * @param {Object} config - Configuration
     * @returns {string} - HTML string
     */
    createConfirmModalHTML(modalId, config) {
        const title = config.title || 'Confirm Action';
        const message = config.message || 'Are you sure you want to proceed?';
        const confirmText = config.confirmText || 'Confirm';
        const cancelText = config.cancelText || 'Cancel';
        const icon = config.icon || 'exclamation-triangle';
        const iconColor = config.iconColor || 'red';
        
        const iconSVG = this.getIconSVG(icon, iconColor);
        
        return `
            <div id="${modalId}" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="modal-overlay fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-${iconColor}-100 sm:mx-0 sm:h-10 sm:w-10">
                                ${iconSVG}
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 modal-title">${title}</h3>
                                <div class="mt-3">
                                    <p class="text-sm text-gray-500 modal-message">${message}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="button" class="confirm-btn w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-${iconColor}-600 text-base font-medium text-white hover:bg-${iconColor}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-${iconColor}-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                                ${confirmText}
                            </button>
                            <button type="button" class="cancel-btn mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                                ${cancelText}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },
    
    /**
     * Get SVG icon for modal
     * @param {string} icon - Icon name
     * @param {string} color - Icon color
     * @returns {string} - SVG HTML
     */
    getIconSVG(icon, color) {
        const colorClass = `text-${color}-600`;
        
        const icons = {
            'exclamation-triangle': `<svg class="h-6 w-6 ${colorClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>`,
            'trash': `<svg class="h-6 w-6 ${colorClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" /></svg>`,
            'question': `<svg class="h-6 w-6 ${colorClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
            'check': `<svg class="h-6 w-6 ${colorClass}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>`
        };
        
        return icons[icon] || icons['exclamation-triangle'];
    },
    
    /**
     * Close all active modals
     */
    closeAll() {
        this.activeModals.forEach(modalId => {
            this.hide(modalId);
        });
    }
};

// Backward compatibility aliases
window.showModal = (modalId, config) => StudEatsModal.show(modalId, config);
window.hideModal = (modalId, options) => StudEatsModal.hide(modalId, options);

// ESC key handler
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && StudEatsModal.defaults.closeOnEscape) {
        StudEatsModal.closeAll();
    }
});

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('StudEats Modal System initialized');
});