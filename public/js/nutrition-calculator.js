/**
 * Nutrition Calculator for StudEats Admin Recipe Editor
 * 
 * This module provides real-time nutrition calculation using the Nutrition API
 * Supports multiple units (kg, g, lb, oz, cups, etc.) and automatic conversion
 * 
 * Usage:
 * <script src="{{ asset('js/nutrition-calculator.js') }}"></script>
 * const calculator = new NutritionCalculator();
 */

class NutritionCalculator {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        this.isCalculating = false;
        this.ingredientNutrients = [];
        this.init();
    }
    
    init() {
        console.log('🍎 Nutrition Calculator initialized');
        this.createCalculatorUI();
        this.bindEvents();
    }
    
    /**
     * Create the nutrition calculator UI panel
     */
    createCalculatorUI() {
        const nutritionSection = document.querySelector('details summary').closest('details');
        if (!nutritionSection) return;
        
        // Insert calculator button before nutrition section
        const calculatorHTML = `
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg shadow-sm border border-green-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-green-200 bg-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Automatic Nutrition Calculator
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Calculate nutritional values from ingredients using USDA database</p>
                        </div>
                        <button type="button" id="calculate-nutrition-btn" 
                                class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span class="btn-text">Calculate Nutrition</span>
                        </button>
                    </div>
                </div>
                
                <!-- Calculation Progress -->
                <div id="nutrition-progress" class="hidden px-6 py-4 bg-white border-b border-green-100">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900" id="progress-text">Calculating nutrition...</p>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                <div id="progress-bar" class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Nutrition Summary -->
                <div id="nutrition-summary" class="hidden px-6 py-4 bg-white">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Calculated Nutrition (Per Serving)</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="text-2xl font-bold text-blue-600" id="calc-calories">0</div>
                            <div class="text-xs text-gray-600 mt-1">Calories</div>
                        </div>
                        <div class="text-center p-3 bg-orange-50 rounded-lg border border-orange-100">
                            <div class="text-2xl font-bold text-orange-600" id="calc-protein">0g</div>
                            <div class="text-xs text-gray-600 mt-1">Protein</div>
                        </div>
                        <div class="text-center p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <div class="text-2xl font-bold text-yellow-600" id="calc-carbs">0g</div>
                            <div class="text-xs text-gray-600 mt-1">Carbs</div>
                        </div>
                        <div class="text-center p-3 bg-purple-50 rounded-lg border border-purple-100">
                            <div class="text-2xl font-bold text-purple-600" id="calc-fats">0g</div>
                            <div class="text-xs text-gray-600 mt-1">Fats</div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-sm">
                        <span class="text-gray-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span id="ingredients-processed">0</span> ingredients processed
                        </span>
                        <button type="button" id="apply-nutrition-btn" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                            Apply to Form →
                        </button>
                    </div>
                </div>
                
                <!-- Error Message -->
                <div id="nutrition-error" class="hidden px-6 py-4 bg-red-50 border-t border-red-100">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="ml-3">
                            <h4 class="text-sm font-semibold text-red-800">Calculation Error</h4>
                            <p class="text-sm text-red-700 mt-1" id="error-message"></p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        nutritionSection.insertAdjacentHTML('beforebegin', calculatorHTML);
    }
    
    /**
     * Bind event listeners
     */
    bindEvents() {
        const calculateBtn = document.getElementById('calculate-nutrition-btn');
        const applyBtn = document.getElementById('apply-nutrition-btn');
        
        if (calculateBtn) {
            calculateBtn.addEventListener('click', () => this.calculateRecipeNutrition());
        }
        
        if (applyBtn) {
            applyBtn.addEventListener('click', () => this.applyNutritionToForm());
        }
        
        // Add event listeners for price calculation
        document.addEventListener('input', (e) => {
            if (e.target.matches('[name="ingredient_price[]"]') || 
                e.target.matches('[name="ingredient_quantity[]"]') ||
                e.target.matches('[name="servings"]')) {
                this.calculateTotalPrice();
            }
        });
        
        // Calculate initial price on page load
        document.addEventListener('DOMContentLoaded', () => {
            this.calculateTotalPrice();
        });
    }
    
    /**
     * Calculate nutrition for all ingredients in the recipe
     */
    async calculateRecipeNutrition() {
        if (this.isCalculating) return;
        
        // Get all ingredients from the form
        const ingredients = this.getIngredientsFromForm();
        
        if (ingredients.length === 0) {
            this.showError('No ingredients found. Please add at least one ingredient.');
            return;
        }
        
        // Validate servings
        const servings = parseInt(document.querySelector('[name="servings"]')?.value) || 1;
        if (servings < 1) {
            this.showError('Please enter a valid number of servings.');
            return;
        }
        
        this.isCalculating = true;
        this.showProgress(true);
        this.hideError();
        this.hideSummary();
        
        const calculateBtn = document.getElementById('calculate-nutrition-btn');
        const btnText = calculateBtn.querySelector('.btn-text');
        const originalText = btnText.textContent;
        btnText.textContent = 'Calculating...';
        calculateBtn.disabled = true;
        
        try {
            // Call the nutrition API
            const response = await fetch('/api/calculate-recipe-nutrition', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    ingredients: ingredients,
                    servings: servings
                })
            });
            
            if (!response.ok) {
                throw new Error(`API returned status ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.displayNutritionSummary(data);
                this.ingredientNutrients = data.ingredients || [];
                this.showNotification('✅ Nutrition calculated successfully!', 'success');
            } else {
                throw new Error(data.error || 'Failed to calculate nutrition');
            }
            
        } catch (error) {
            console.error('Nutrition calculation error:', error);
            this.showError(error.message || 'Failed to calculate nutrition. Please check your internet connection and try again.');
        } finally {
            this.isCalculating = false;
            this.showProgress(false);
            btnText.textContent = originalText;
            calculateBtn.disabled = false;
        }
    }
    
    /**
     * Get ingredients from the form
     */
    getIngredientsFromForm() {
        const ingredients = [];
        const rows = document.querySelectorAll('.ingredient-row');
        
        rows.forEach(row => {
            const nameInput = row.querySelector('[name="ingredient_name[]"]');
            const quantityInput = row.querySelector('[name="ingredient_quantity[]"]');
            const unitInput = row.querySelector('[name="ingredient_unit[]"]');
            const priceInput = row.querySelector('[name="ingredient_price[]"]');
            
            const name = nameInput?.value.trim();
            const quantity = quantityInput?.value.trim();
            const unit = unitInput?.value.trim();
            const price = priceInput?.value.trim();
            const id = row.id || row.dataset.id || `ingredient-${Date.now()}-${Math.random().toString(36).substr(2, 5)}`;
            
            if (name && quantity) {
                ingredients.push({
                    id: id,
                    name: name,
                    quantity: parseFloat(quantity),
                    unit: unit || '',
                    price: parseFloat(price) || 0
                });
            }
        });
        
        return ingredients;
    }
    
    /**
     * Display nutrition summary
     */
    displayNutritionSummary(data) {
        const summary = document.getElementById('nutrition-summary');
        if (!summary) return;
        
        const perServing = data.per_serving || {};
        
        document.getElementById('calc-calories').textContent = Math.round(perServing.calories || 0);
        document.getElementById('calc-protein').textContent = (perServing.protein || 0).toFixed(1) + 'g';
        document.getElementById('calc-carbs').textContent = (perServing.carbs || 0).toFixed(1) + 'g';
        document.getElementById('calc-fats').textContent = (perServing.fats || 0).toFixed(1) + 'g';
        document.getElementById('ingredients-processed').textContent = data.ingredient_count || 0;
        
        this.showSummary();
    }
    
    /**
     * Apply calculated nutrition values to the form inputs
     */
    applyNutritionToForm() {
        const nutritionSummary = document.getElementById('nutrition-summary');
        if (!nutritionSummary || nutritionSummary.classList.contains('hidden')) {
            this.showError('Please calculate nutrition first.');
            return;
        }
        
        // Get calculated values
        const calories = document.getElementById('calc-calories')?.textContent || '0';
        const protein = document.getElementById('calc-protein')?.textContent.replace('g', '') || '0';
        const carbs = document.getElementById('calc-carbs')?.textContent.replace('g', '') || '0';
        const fats = document.getElementById('calc-fats')?.textContent.replace('g', '') || '0';
        
        // Apply to form inputs
        const caloriesInput = document.querySelector('[name="calories"]');
        const proteinInput = document.querySelector('[name="protein"]');
        const carbsInput = document.querySelector('[name="carbs"]');
        const fatsInput = document.querySelector('[name="fats"]');
        
        if (caloriesInput) caloriesInput.value = parseFloat(calories);
        if (proteinInput) proteinInput.value = parseFloat(protein);
        if (carbsInput) carbsInput.value = parseFloat(carbs);
        if (fatsInput) fatsInput.value = parseFloat(fats);
        
        // Calculate total price
        this.calculateTotalPrice();
        
        this.showNotification('✅ Nutrition values applied to form!', 'success');
    }
    
    /**
     * Calculate total price for all ingredients
     */
    calculateTotalPrice() {
        const ingredients = this.getIngredientsFromForm();
        let totalPrice = 0;
        
        ingredients.forEach(ingredient => {
            const quantity = parseFloat(ingredient.quantity) || 0;
            const price = parseFloat(ingredient.price) || 0;
            const ingredientTotal = quantity * price;
            totalPrice += ingredientTotal;
            
            // Update individual ingredient price display if element exists
            const priceDisplayEl = document.getElementById(`ingredient-total-${ingredient.id}`);
            if (priceDisplayEl) {
                priceDisplayEl.textContent = `₱${ingredientTotal.toFixed(2)}`;
            }
        });
        
        // Update total price display
        const totalPriceEl = document.getElementById('total-recipe-price');
        if (totalPriceEl) {
            totalPriceEl.textContent = `₱${totalPrice.toFixed(2)}`;
        }
        
        // Calculate price per serving
        const servings = parseInt(document.querySelector('[name="servings"]')?.value) || 1;
        const pricePerServing = totalPrice / servings;
        
        // Update price per serving display
        const pricePerServingEl = document.getElementById('price-per-serving');
        if (pricePerServingEl) {
            pricePerServingEl.textContent = `₱${pricePerServing.toFixed(2)}`;
        }
    }
    
    /**
     * Animate value change in input field
     */
    animateValue(input, newValue) {
        input.classList.add('bg-green-100', 'border-green-500');
        input.value = newValue;
        
        setTimeout(() => {
            input.classList.remove('bg-green-100', 'border-green-500');
        }, 1500);
    }
    
    /**
     * Show/hide progress indicator
     */
    showProgress(show) {
        const progress = document.getElementById('nutrition-progress');
        if (!progress) return;
        
        if (show) {
            progress.classList.remove('hidden');
            this.updateProgress(0);
            this.animateProgress();
        } else {
            progress.classList.add('hidden');
        }
    }
    
    /**
     * Animate progress bar
     */
    animateProgress() {
        if (!this.isCalculating) return;
        
        let progress = 0;
        const interval = setInterval(() => {
            if (!this.isCalculating || progress >= 90) {
                clearInterval(interval);
                if (progress >= 90) {
                    this.updateProgress(100);
                }
                return;
            }
            progress += Math.random() * 30;
            if (progress > 90) progress = 90;
            this.updateProgress(progress);
        }, 500);
    }
    
    /**
     * Update progress bar and text
     */
    updateProgress(percent) {
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        
        if (progressBar) {
            progressBar.style.width = percent + '%';
        }
        
        if (progressText) {
            if (percent < 30) {
                progressText.textContent = 'Fetching ingredient data...';
            } else if (percent < 70) {
                progressText.textContent = 'Calculating nutritional values...';
            } else if (percent < 100) {
                progressText.textContent = 'Processing results...';
            } else {
                progressText.textContent = 'Calculation complete!';
            }
        }
    }
    
    /**
     * Show nutrition summary panel
     */
    showSummary() {
        const summary = document.getElementById('nutrition-summary');
        if (summary) {
            summary.classList.remove('hidden');
        }
    }
    
    /**
     * Hide nutrition summary panel
     */
    hideSummary() {
        const summary = document.getElementById('nutrition-summary');
        if (summary) {
            summary.classList.add('hidden');
        }
    }
    
    /**
     * Show error message
     */
    showError(message) {
        const errorDiv = document.getElementById('nutrition-error');
        const errorMessage = document.getElementById('error-message');
        
        if (errorDiv && errorMessage) {
            errorMessage.textContent = message;
            errorDiv.classList.remove('hidden');
        }
        
        this.showNotification('❌ ' + message, 'error');
    }
    
    /**
     * Hide error message
     */
    hideError() {
        const errorDiv = document.getElementById('nutrition-error');
        if (errorDiv) {
            errorDiv.classList.add('hidden');
        }
    }
    
    /**
     * Show notification toast
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-100 border-green-500 text-green-800' :
                       type === 'error' ? 'bg-red-100 border-red-500 text-red-800' :
                       'bg-blue-100 border-blue-500 text-blue-800';
        
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg border-l-4 ${bgColor} font-medium text-sm max-w-md transform transition-all duration-300 translate-x-0`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('[name="ingredient_names[]"]')) {
        new NutritionCalculator();
    }
});
