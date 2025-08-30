/**
 * Profile Edit Page JavaScript Enhancements
 * Provides advanced form handling, validation, and user experience improvements
 */

class ProfileEditor {
    constructor() {
        this.form = document.getElementById('profile-form');
        this.saveButton = document.getElementById('save-button');
        this.saveText = document.getElementById('save-text');
        this.loadingOverlay = document.getElementById('loading-overlay');
        this.successMessage = document.getElementById('success-message');
        
        this.init();
    }

    init() {
        if (!this.form) return;
        
        this.setupEventListeners();
        this.setupFormValidation();
        this.setupBudgetPresets();
        this.setupProgressIndicator();
        this.setupAutoSave();
    }

    setupEventListeners() {
        // Form submission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Real-time validation
        this.form.addEventListener('input', (e) => this.validateField(e.target));
        this.form.addEventListener('change', (e) => this.validateField(e.target));
        
        // Budget helper
        document.getElementById('daily_budget')?.addEventListener('input', (e) => {
            this.updateBudgetIndicator(e.target.value);
        });
        
        // Unit conversions
        this.setupUnitConversions();
    }

    setupFormValidation() {
        const requiredFields = this.form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            field.addEventListener('blur', () => {
                this.validateRequiredField(field);
            });
        });

        // Email validation
        const emailField = document.getElementById('email');
        if (emailField) {
            emailField.addEventListener('input', () => {
                this.validateEmail(emailField);
            });
        }
    }

    setupBudgetPresets() {
        document.querySelectorAll('.budget-preset').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const budgetInput = document.getElementById('daily_budget');
                if (budgetInput) {
                    budgetInput.value = button.dataset.value;
                    this.updateBudgetIndicator(button.dataset.value);
                    this.validateField(budgetInput);
                }
            });
        });
    }

    setupProgressIndicator() {
        const sections = this.form.querySelectorAll('.bg-white.shadow-lg');
        const totalFields = this.form.querySelectorAll('input, select').length;
        
        // Create progress indicator
        const progressContainer = document.createElement('div');
        progressContainer.className = 'sticky top-4 bg-white border rounded-lg p-4 mb-6 shadow-sm';
        progressContainer.innerHTML = `
            <div class="flex items-center justify-between text-sm">
                <span class="font-medium">Profile Completion</span>
                <span id="completion-percentage">0%</span>
            </div>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div id="completion-bar" class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
        `;
        
        this.form.insertBefore(progressContainer, this.form.firstElementChild);
        this.updateProgress();
    }

    setupAutoSave() {
        let autoSaveTimeout;
        const autoSaveIndicator = document.createElement('div');
        autoSaveIndicator.id = 'autosave-indicator';
        autoSaveIndicator.className = 'fixed bottom-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg transform translate-y-16 transition-transform duration-300';
        autoSaveIndicator.textContent = 'Auto-saved';
        document.body.appendChild(autoSaveIndicator);
        
        this.form.addEventListener('input', (e) => {
            if (e.target.name) {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    this.autoSave(e.target);
                }, 2000);
            }
        });
    }

    setupUnitConversions() {
        // Height conversion
        const heightInput = document.getElementById('height');
        const heightUnit = document.querySelector('select[name="height_unit"]');
        
        if (heightInput && heightUnit) {
            heightUnit.addEventListener('change', () => {
                const currentValue = parseFloat(heightInput.value);
                if (currentValue && !isNaN(currentValue)) {
                    if (heightUnit.value === 'ft' && currentValue > 10) {
                        // Convert cm to ft
                        heightInput.value = (currentValue / 30.48).toFixed(1);
                    } else if (heightUnit.value === 'cm' && currentValue < 10) {
                        // Convert ft to cm
                        heightInput.value = (currentValue * 30.48).toFixed(0);
                    }
                }
            });
        }

        // Weight conversion
        const weightInput = document.getElementById('weight');
        const weightUnit = document.querySelector('select[name="weight_unit"]');
        
        if (weightInput && weightUnit) {
            weightUnit.addEventListener('change', () => {
                const currentValue = parseFloat(weightInput.value);
                if (currentValue && !isNaN(currentValue)) {
                    if (weightUnit.value === 'lbs' && currentValue < 500) {
                        // Convert kg to lbs
                        weightInput.value = (currentValue * 2.20462).toFixed(1);
                    } else if (weightUnit.value === 'kg' && currentValue > 500) {
                        // Convert lbs to kg
                        weightInput.value = (currentValue / 2.20462).toFixed(1);
                    }
                }
            });
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        if (!this.validateForm()) {
            this.showValidationErrors();
            return;
        }

        this.showLoadingState();
        
        try {
            const formData = new FormData(this.form);
            
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.showSuccess(data.message);
                this.updateProgress();
                
                // Update any displayed user info
                if (data.user && data.user.name) {
                    document.querySelectorAll('[data-user-name]').forEach(el => {
                        el.textContent = data.user.name;
                    });
                }
            } else {
                throw new Error(data.message || 'Something went wrong');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('There was an error saving your profile. Please try again.');
        } finally {
            this.hideLoadingState();
        }
    }

    validateForm() {
        let isValid = true;
        const requiredFields = this.form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!this.validateRequiredField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        if (field.hasAttribute('required')) {
            this.validateRequiredField(field);
        }
        
        if (field.type === 'email') {
            this.validateEmail(field);
        }
        
        if (field.type === 'number') {
            this.validateNumber(field);
        }
        
        this.updateProgress();
    }

    validateRequiredField(field) {
        const isValid = field.value.trim() !== '';
        this.updateFieldValidation(field, isValid);
        return isValid;
    }

    validateEmail(field) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = !field.value || emailRegex.test(field.value);
        this.updateFieldValidation(field, isValid);
        return isValid;
    }

    validateNumber(field) {
        const value = parseFloat(field.value);
        const min = parseFloat(field.getAttribute('min'));
        const max = parseFloat(field.getAttribute('max'));
        
        let isValid = true;
        if (field.value && !isNaN(value)) {
            if (!isNaN(min) && value < min) isValid = false;
            if (!isNaN(max) && value > max) isValid = false;
        }
        
        this.updateFieldValidation(field, isValid);
        return isValid;
    }

    updateFieldValidation(field, isValid) {
        field.classList.remove('border-red-300', 'border-green-500');
        field.classList.add(isValid ? 'border-green-500' : 'border-red-300');
    }

    updateBudgetIndicator(value) {
        const budgetValue = parseInt(value);
        let indicator = '';
        
        if (budgetValue < 200) indicator = '💰 Very tight budget - Focus on basics';
        else if (budgetValue < 300) indicator = '💰 Tight budget - Smart choices needed';
        else if (budgetValue < 500) indicator = '💰 Standard budget - Good balance';
        else if (budgetValue < 800) indicator = '💰 Comfortable budget - Many options';
        else indicator = '💰 Premium budget - Full flexibility';
        
        let indicatorEl = document.querySelector('.budget-indicator');
        if (!indicatorEl) {
            indicatorEl = document.createElement('p');
            indicatorEl.className = 'budget-indicator mt-2 text-sm text-blue-600 font-medium';
            document.getElementById('daily_budget').parentNode.appendChild(indicatorEl);
        }
        indicatorEl.textContent = indicator;
    }

    updateProgress() {
        const allFields = this.form.querySelectorAll('input, select');
        let filledFields = 0;
        
        allFields.forEach(field => {
            if ((field.type === 'checkbox' && field.checked) || 
                (field.type !== 'checkbox' && field.value.trim() !== '')) {
                filledFields++;
            }
        });
        
        const percentage = Math.round((filledFields / allFields.length) * 100);
        
        const percentageEl = document.getElementById('completion-percentage');
        const barEl = document.getElementById('completion-bar');
        
        if (percentageEl && barEl) {
            percentageEl.textContent = `${percentage}%`;
            barEl.style.width = `${percentage}%`;
        }
    }

    async autoSave(field) {
        if (!field.name || field.hasAttribute('required')) return;
        
        const indicator = document.getElementById('autosave-indicator');
        indicator.style.transform = 'translateY(0)';
        
        setTimeout(() => {
            indicator.style.transform = 'translateY(100px)';
        }, 2000);
    }

    showLoadingState() {
        this.loadingOverlay.classList.remove('hidden');
        this.saveButton.disabled = true;
        this.saveText.textContent = 'Saving...';
        this.successMessage.classList.add('hidden');
    }

    hideLoadingState() {
        this.loadingOverlay.classList.add('hidden');
        this.saveButton.disabled = false;
        this.saveText.textContent = 'Save Changes';
    }

    showSuccess(message) {
        this.successMessage.classList.remove('hidden');
        document.getElementById('success-text').textContent = message;
        
        this.successMessage.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            this.successMessage.style.transition = 'opacity 0.5s ease-out';
            this.successMessage.style.opacity = '0';
            setTimeout(() => {
                this.successMessage.classList.add('hidden');
                this.successMessage.style.opacity = '1';
            }, 500);
        }, 5000);
    }

    showError(message) {
        alert(message); // Simple error display - could be enhanced with a toast
    }

    showValidationErrors() {
        const firstInvalidField = this.form.querySelector('.border-red-300');
        if (firstInvalidField) {
            firstInvalidField.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            firstInvalidField.focus();
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ProfileEditor();
});

export default ProfileEditor;