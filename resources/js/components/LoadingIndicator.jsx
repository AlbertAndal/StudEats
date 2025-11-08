/**
 * LoadingIndicator - Vanilla JavaScript Loading Component
 * 
 * A versatile loading indicator that works with Blade templates and vanilla JavaScript.
 * Supports multiple variants, themes, auto-cycling messages, and accessibility features.
 * 
 * @class LoadingIndicator
 * 
 * @example
 * // Create a loading indicator
 * const loader = new LoadingIndicator({
 *   container: document.getElementById('my-container'),
 *   messages: ["Please wait...", "Processing...", "Fetching data..."],
 *   variant: 'spinner',
 *   size: 'medium',
 *   onStageComplete: (stage) => console.log(`Stage ${stage} complete`)
 * });
 * 
 * loader.show();
 * loader.hide();
 */
class LoadingIndicator {
    constructor(options = {}) {
        this.options = {
            container: null,
            initialMessage: "Please wait...",
            messages: [
                "Please wait...",
                "Processing...",
                "Fetching data...",
                "Retrieving information...",
                "Preparing content...",
                "Initializing..."
            ],
            transitionInterval: 2000,
            autoTransition: true,
            size: 'medium',
            theme: 'auto',
            variant: 'spinner',
            onStageComplete: null,
            onAllStagesComplete: null,
            className: '',
            showProgress: false,
            ...options
        };

        this.currentMessageIndex = 0;
        this.progress = 0;
        this.isTransitioning = false;
        this.isVisible = false;
        this.timers = [];
        this.element = null;

        this.sizeConfig = {
            small: {
                spinner: 'w-6 h-6',
                text: 'text-sm',
                container: 'gap-2'
            },
            medium: {
                spinner: 'w-10 h-10',
                text: 'text-base',
                container: 'gap-3'
            },
            large: {
                spinner: 'w-16 h-16',
                text: 'text-lg',
                container: 'gap-4'
            }
        };

        this.themeConfig = {
            light: {
                bg: 'bg-white',
                text: 'text-gray-700',
                spinner: 'border-gray-300 border-t-blue-600',
                dots: 'bg-blue-600',
                pulse: 'bg-blue-600',
                progress: 'bg-blue-600'
            },
            dark: {
                bg: 'bg-gray-800',
                text: 'text-gray-200',
                spinner: 'border-gray-600 border-t-blue-400',
                dots: 'bg-blue-400',
                pulse: 'bg-blue-400',
                progress: 'bg-blue-400'
            },
            auto: {
                bg: 'bg-white dark:bg-gray-800',
                text: 'text-gray-700 dark:text-gray-200',
                spinner: 'border-gray-300 dark:border-gray-600 border-t-blue-600 dark:border-t-blue-400',
                dots: 'bg-blue-600 dark:bg-blue-400',
                pulse: 'bg-blue-600 dark:bg-blue-400',
                progress: 'bg-blue-600 dark:bg-blue-400'
            }
        };

        this.init();
    }

    init() {
        this.createElement();
        if (this.options.container) {
            this.options.container.appendChild(this.element);
        }
    }

    createElement() {
        const { size, theme, variant, className, showProgress } = this.options;
        const currentSize = this.sizeConfig[size];
        const currentTheme = this.themeConfig[theme];

        this.element = document.createElement('div');
        this.element.className = `loading-indicator flex flex-col items-center justify-center ${currentSize.container} ${className} hidden`;
        this.element.setAttribute('role', 'alert');
        this.element.setAttribute('aria-live', 'polite');
        this.element.setAttribute('aria-busy', 'true');

        // Create indicator element
        const indicatorContainer = document.createElement('div');
        indicatorContainer.className = 'loading-indicator-visual';
        
        switch (variant) {
            case 'dots':
                indicatorContainer.innerHTML = this.createDotsHTML(size, currentTheme);
                break;
            case 'pulse':
                indicatorContainer.innerHTML = this.createPulseHTML(currentSize, currentTheme);
                break;
            case 'progress':
                indicatorContainer.innerHTML = this.createProgressHTML(size, currentTheme);
                break;
            case 'spinner':
            default:
                indicatorContainer.innerHTML = this.createSpinnerHTML(currentSize, currentTheme);
                break;
        }

        // Create message element
        const messageElement = document.createElement('div');
        messageElement.className = `loading-message ${currentSize.text} font-medium ${currentTheme.text} transition-opacity duration-300 text-center`;
        messageElement.setAttribute('aria-label', `Loading status: ${this.options.messages[0]}`);
        messageElement.textContent = this.options.messages[0];

        // Create progress bar if needed
        let progressElement = null;
        if (showProgress) {
            progressElement = document.createElement('div');
            progressElement.className = 'w-full max-w-xs';
            progressElement.innerHTML = `
                <div class="h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="loading-progress-bar h-full ${currentTheme.progress} transition-all duration-100 ease-linear" 
                         style="width: 0%" 
                         role="progressbar" 
                         aria-valuenow="0" 
                         aria-valuemin="0" 
                         aria-valuemax="100"></div>
                </div>
            `;
        }

        this.element.appendChild(indicatorContainer);
        this.element.appendChild(messageElement);
        if (progressElement) {
            this.element.appendChild(progressElement);
        }
    }

    createSpinnerHTML(currentSize, currentTheme) {
        return `<div class="${currentSize.spinner} border-4 rounded-full animate-spin ${currentTheme.spinner}" role="status" aria-label="Loading spinner"></div>`;
    }

    createDotsHTML(size, currentTheme) {
        const dotSize = size === 'small' ? 'w-2 h-2' : size === 'large' ? 'w-4 h-4' : 'w-3 h-3';
        return `
            <div class="flex gap-2" role="status" aria-label="Loading dots">
                <div class="${dotSize} rounded-full ${currentTheme.dots} animate-pulse" style="animation-delay: 0s"></div>
                <div class="${dotSize} rounded-full ${currentTheme.dots} animate-pulse" style="animation-delay: 0.2s"></div>
                <div class="${dotSize} rounded-full ${currentTheme.dots} animate-pulse" style="animation-delay: 0.4s"></div>
            </div>
        `;
    }

    createPulseHTML(currentSize, currentTheme) {
        return `
            <div class="relative" role="status" aria-label="Loading pulse">
                <div class="${currentSize.spinner} rounded-full ${currentTheme.pulse} opacity-75 animate-ping"></div>
                <div class="${currentSize.spinner} rounded-full ${currentTheme.pulse} absolute top-0 left-0"></div>
            </div>
        `;
    }

    createProgressHTML(size, currentTheme) {
        const width = size === 'small' ? 'w-32' : size === 'large' ? 'w-64' : 'w-48';
        return `
            <div class="relative ${width}" role="status" aria-label="Loading progress">
                <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="loading-progress-indicator h-full ${currentTheme.progress} transition-all duration-300 ease-out" style="width: 0%"></div>
                </div>
            </div>
        `;
    }

    show() {
        if (!this.element) return;
        
        this.isVisible = true;
        this.element.classList.remove('hidden');
        this.element.classList.add('flex');
        
        if (this.options.autoTransition && this.options.messages.length > 1) {
            this.startAutoTransition();
        }
        
        if (this.options.showProgress) {
            this.startProgressAnimation();
        }

        // Start progress variant animation if needed
        if (this.options.variant === 'progress') {
            this.startProgressVariantAnimation();
        }
    }

    hide() {
        if (!this.element) return;
        
        this.isVisible = false;
        this.element.classList.add('hidden');
        this.element.classList.remove('flex');
        
        this.clearTimers();
    }

    startAutoTransition() {
        if (this.options.messages.length <= 1) return;

        const timer = setTimeout(() => {
            if (!this.isVisible) return;
            
            this.isTransitioning = true;
            const messageElement = this.element.querySelector('.loading-message');
            
            if (messageElement) {
                messageElement.style.opacity = '0';
                
                setTimeout(() => {
                    if (!this.isVisible) return;
                    
                    const nextIndex = (this.currentMessageIndex + 1) % this.options.messages.length;
                    this.currentMessageIndex = nextIndex;
                    
                    messageElement.textContent = this.options.messages[nextIndex];
                    messageElement.setAttribute('aria-label', `Loading status: ${this.options.messages[nextIndex]}`);
                    messageElement.style.opacity = '1';
                    
                    this.isTransitioning = false;
                    
                    // Trigger callbacks
                    if (this.options.onStageComplete) {
                        this.options.onStageComplete(this.currentMessageIndex);
                    }
                    
                    if (nextIndex === 0 && this.options.onAllStagesComplete) {
                        this.options.onAllStagesComplete();
                    }
                    
                    // Continue auto-transition
                    this.startAutoTransition();
                }, 300);
            }
        }, this.options.transitionInterval);
        
        this.timers.push(timer);
    }

    startProgressAnimation() {
        if (!this.options.showProgress) return;
        
        const progressBar = this.element.querySelector('.loading-progress-bar');
        if (!progressBar) return;
        
        this.progress = 0;
        progressBar.style.width = '0%';
        progressBar.setAttribute('aria-valuenow', '0');
        
        const progressInterval = setInterval(() => {
            if (!this.isVisible) {
                clearInterval(progressInterval);
                return;
            }
            
            if (this.progress >= 100) {
                clearInterval(progressInterval);
                return;
            }
            
            this.progress += (100 / (this.options.transitionInterval / 50));
            if (this.progress > 100) this.progress = 100;
            
            progressBar.style.width = `${this.progress}%`;
            progressBar.setAttribute('aria-valuenow', Math.round(this.progress).toString());
        }, 50);
        
        this.timers.push(progressInterval);
    }

    startProgressVariantAnimation() {
        const progressIndicator = this.element.querySelector('.loading-progress-indicator');
        if (!progressIndicator) return;
        
        let progressValue = 0;
        const interval = setInterval(() => {
            if (!this.isVisible) {
                clearInterval(interval);
                return;
            }
            
            progressValue = (progressValue >= 100) ? 0 : progressValue + 5;
            progressIndicator.style.width = `${progressValue}%`;
        }, 100);
        
        this.timers.push(interval);
    }

    setMessage(index) {
        if (index < 0 || index >= this.options.messages.length) return;
        
        const messageElement = this.element?.querySelector('.loading-message');
        if (!messageElement) return;
        
        this.isTransitioning = true;
        messageElement.style.opacity = '0';
        
        setTimeout(() => {
            this.currentMessageIndex = index;
            messageElement.textContent = this.options.messages[index];
            messageElement.setAttribute('aria-label', `Loading status: ${this.options.messages[index]}`);
            messageElement.style.opacity = '1';
            this.isTransitioning = false;
        }, 300);
    }

    nextMessage() {
        const nextIndex = (this.currentMessageIndex + 1) % this.options.messages.length;
        this.setMessage(nextIndex);
    }

    previousMessage() {
        const prevIndex = (this.currentMessageIndex - 1 + this.options.messages.length) % this.options.messages.length;
        this.setMessage(prevIndex);
    }

    clearTimers() {
        this.timers.forEach(timer => {
            if (typeof timer === 'number') {
                clearTimeout(timer);
                clearInterval(timer);
            }
        });
        this.timers = [];
    }

    destroy() {
        this.clearTimers();
        if (this.element && this.element.parentNode) {
            this.element.parentNode.removeChild(this.element);
        }
    }

    // Static methods for quick usage
    static createOverlay(options = {}) {
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center transition-all duration-300';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.setAttribute('aria-label', 'Loading overlay');
        
        const card = document.createElement('div');
        card.className = 'bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-100';
        
        const loader = new LoadingIndicator({
            ...options,
            container: card,
            showProgress: true
        });
        
        overlay.appendChild(card);
        document.body.appendChild(overlay);
        
        loader.show();
        
        return {
            loader,
            overlay,
            hide() {
                loader.hide();
                overlay.remove();
            }
        };
    }
}

// Make it available globally
if (typeof window !== 'undefined') {
    window.LoadingIndicator = LoadingIndicator;
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LoadingIndicator;
}
