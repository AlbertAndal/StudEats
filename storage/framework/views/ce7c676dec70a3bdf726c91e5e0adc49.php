

<?php $__env->startSection('title', 'Add Meal to Plan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center">
            <a href="<?php echo e(route('meal-plans.index')); ?>" 
               class="mr-4 p-2 text-gray-400 hover:text-gray-600 transition-colors"
               aria-label="Back to meal plans">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add Meal to Plan</h1>
                <p class="mt-2 text-gray-600">Select a meal and schedule it for your plan</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Meal Selection Form -->
        <div class="lg:col-span-2">
            <form action="<?php echo e(route('meal-plans.store')); ?>" method="POST" id="meal-plan-form">
                <?php echo csrf_field(); ?>
                
                <div class="bg-white shadow rounded-lg mb-6 relative schedule-details-container">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-900 text-left">Schedule Details</h2>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Date & Time Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Date
                                </label>
                                <input type="date" id="scheduled_date" name="scheduled_date" required
                                       class="block w-full px-4 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-all duration-200 <?php $__errorArgs = ['scheduled_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:ring-red-500 focus:border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(request('date', now()->format('Y-m-d'))); ?>">
                                <?php $__errorArgs = ['scheduled_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <?php echo e($message); ?>

                                    </p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="scheduled_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Time (Optional)
                                </label>
                                <input type="time" id="scheduled_time" name="scheduled_time"
                                       class="block w-full px-4 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-all duration-200 hover:border-gray-400 focus:outline-none"
                                       placeholder="Select time"
                                       aria-label="Select scheduled time"
                                       data-tooltip="Choose a specific time for your meal (optional)">
                            </div>
                        </div>

                        <!-- Meal Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Meal Type
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <?php
                                    $mealTypes = [
                                        'breakfast' => ['icon' => '🌅', 'label' => 'Breakfast', 'color' => 'yellow'],
                                        'lunch' => ['icon' => '☀️', 'label' => 'Lunch', 'color' => 'orange'],
                                        'dinner' => ['icon' => '🌙', 'label' => 'Dinner', 'color' => 'purple'],
                                        'snack' => ['icon' => '🍎', 'label' => 'Snack', 'color' => 'green']
                                    ];
                                ?>
                                <?php $__currentLoopData = $mealTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="meal_type" value="<?php echo e($type); ?>" 
                                               class="sr-only peer" 
                                               <?php echo e(request('meal_type') == $type ? 'checked' : ''); ?> required>
                                        <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-<?php echo e($details['color']); ?>-300 peer-checked:border-<?php echo e($details['color']); ?>-500 peer-checked:bg-<?php echo e($details['color']); ?>-50 transition-all duration-200">
                                            <div class="text-2xl mb-2"><?php echo e($details['icon']); ?></div>
                                            <div class="text-sm font-medium text-gray-700 peer-checked:text-<?php echo e($details['color']); ?>-700"><?php echo e($details['label']); ?></div>
                                        </div>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php $__errorArgs = ['meal_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?php echo e($message); ?>

                                </p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Hidden meal_id input -->
                <input type="hidden" id="meal_id" name="meal_id" value="<?php echo e(request('meal_id')); ?>">

                <!-- Action Buttons -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <a href="<?php echo e(route('meal-plans.index')); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" id="submit-btn" disabled
                                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-400 cursor-not-allowed transition-all duration-200 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add to Plan
                        </button>
                    </div>
                    <div id="form-validation" class="mt-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Please select a meal type and choose a meal from the available options.
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Meal Preview Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-8">
                <!-- Selected Meal Preview -->
                <div id="meal-preview" class="bg-white shadow rounded-lg mb-6 hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Selected Meal
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl">🍽️</span>
                            </div>
                            <h4 class="font-semibold text-gray-900 text-lg" id="preview-name"></h4>
                            <p class="text-sm text-gray-600 mt-2" id="preview-description"></p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-3 mb-4">
                            <div class="bg-green-50 p-3 rounded-lg text-center">
                                <div class="text-xs text-green-600 font-medium uppercase tracking-wide">Calories</div>
                                <div class="text-lg font-bold text-green-700" id="preview-calories"></div>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-lg text-center">
                                <div class="text-xs text-blue-600 font-medium uppercase tracking-wide">Cost</div>
                                <div class="text-lg font-bold text-blue-700" id="preview-cost"></div>
                            </div>
                        </div>
                        
                        <button type="button" onclick="clearSelection()" 
                                class="w-full px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                            Change Selection
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="<?php echo e(route('recipes.index')); ?>" 
                           class="w-full flex items-center px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Browse All Recipes
                        </a>
                        <a href="<?php echo e(route('meal-plans.index')); ?>" 
                           class="w-full flex items-center px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            View Meal Plans
                        </a>
                    </div>
                </div>
                
                <!-- BMI Status -->
                <?php if(isset($bmiStatus) && $bmiStatus['bmi']): ?>
                <div class="bg-white shadow rounded-lg mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                            </svg>
                            Your Health Profile
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">BMI Score</span>
                                <span class="text-lg font-bold text-gray-900"><?php echo e($bmiStatus['bmi']); ?></span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Category</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border <?php echo e($bmiStatus['colors'][0]); ?> <?php echo e($bmiStatus['colors'][1]); ?> <?php echo e($bmiStatus['colors'][2]); ?>">
                                    <?php echo e($bmiStatus['category_label']); ?>

                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Daily Calories</span>
                                <span class="text-lg font-bold text-green-600"><?php echo e(number_format($bmiStatus['daily_calories'])); ?></span>
                            </div>
                            
                            <div class="pt-3 border-t border-gray-200">
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-600"><?php echo e($bmiStatus['recommendation']); ?></p>
                                        <?php if($bmiStatus['calorie_multiplier'] != 1): ?>
                                            <p class="text-xs text-blue-600 mt-1">
                                                Meals are adjusted 
                                                <?php echo e($bmiStatus['calorie_multiplier'] > 1 ? '+' : ''); ?><?php echo e(round(($bmiStatus['calorie_multiplier'] - 1) * 100)); ?>% 
                                                for your health goals.
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Available Meals Grid -->
    <div class="mt-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Available Meals
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Click on a meal to select it for your plan</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        <?php echo e($meals->count()); ?> meals available
                    </div>
                </div>
            </div>
            <div class="p-6">
                <?php if($meals->count() > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="group border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-green-400 hover:shadow-lg transition-all duration-300 meal-option transform hover:-translate-y-1"
                                 data-meal-id="<?php echo e($meal->id); ?>"
                                 data-meal-name="<?php echo e($meal->name); ?>"
                                 data-meal-description="<?php echo e($meal->description); ?>"
                                 data-meal-calories="<?php echo e($meal->nutritionalInfo->calories ?? 'N/A'); ?>"
                                 data-meal-cost="₱<?php echo e($meal->cost); ?>"
                                 aria-label="Select <?php echo e($meal->name); ?> for your meal plan">
                                
                                <!-- Meal Image/Icon -->
                                <div class="flex items-center justify-center w-16 h-16 bg-gray-100 group-hover:bg-green-100 rounded-full mx-auto mb-4 transition-colors duration-300">
                                    <?php if($meal->image_path): ?>
                                        <img src="<?php echo e($meal->image_path); ?>" alt="<?php echo e($meal->name); ?>" class="w-full h-full object-cover rounded-full">
                                    <?php else: ?>
                                        <span class="text-2xl">🍽️</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Meal Info -->
                                <div class="text-center">
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="font-semibold text-gray-900 group-hover:text-green-700 transition-colors duration-300 text-left flex-1">
                                            <?php echo e($meal->name); ?>

                                        </h3>
                                        <span class="text-lg font-bold text-green-600 ml-2">₱<?php echo e($meal->cost); ?></span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-4 text-left line-clamp-2">
                                        <?php echo e(Str::limit($meal->description, 100)); ?>

                                    </p>
                                    
                                    <!-- Meal Stats -->
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div class="bg-gray-50 group-hover:bg-green-50 rounded-lg p-3 transition-colors duration-300">
                                            <div class="flex flex-col items-center justify-center text-gray-500 group-hover:text-green-600">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                    <?php if(isset($bmiStatus) && $bmiStatus['calorie_multiplier'] != 1): ?>
                                                        <?php
                                                            $originalCalories = $meal->nutritionalInfo->calories ?? 0;
                                                            $adjustedCalories = round($originalCalories * $bmiStatus['calorie_multiplier']);
                                                        ?>
                                                        <span class="text-xs font-medium"><?php echo e($adjustedCalories); ?> cal</span>
                                                    <?php else: ?>
                                                        <span class="text-xs font-medium"><?php echo e($meal->nutritionalInfo->calories ?? 'N/A'); ?> cal</span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if(isset($bmiStatus) && $bmiStatus['calorie_multiplier'] != 1): ?>
                                                    <span class="text-xs text-gray-400 line-through"><?php echo e($meal->nutritionalInfo->calories ?? 'N/A'); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 group-hover:bg-green-50 rounded-lg p-3 transition-colors duration-300">
                                            <div class="flex items-center justify-center text-gray-500 group-hover:text-green-600">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                <span class="text-xs font-medium capitalize"><?php echo e($meal->cuisine_type); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selection Indicator -->
                                    <div class="flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="flex items-center text-green-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            <span class="text-sm font-medium">Select This Meal</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Selected State -->
                                    <div class="selected-indicator hidden">
                                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No meals available</h3>
                        <p class="text-gray-500 mb-6">There are no meals in the system yet.</p>
                        <a href="<?php echo e(route('recipes.index')); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">
                            Browse Recipes
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mealIdInput = document.getElementById('meal_id');
    const mealPreview = document.getElementById('meal-preview');
    const mealOptions = document.querySelectorAll('.meal-option');
    const submitBtn = document.getElementById('submit-btn');
    const formValidation = document.getElementById('form-validation');
    const mealTypeInputs = document.querySelectorAll('input[name="meal_type"]');
    
    let selectedMealId = mealIdInput.value;

    // Handle meal option clicks
    mealOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            mealOptions.forEach(opt => {
                opt.classList.remove('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
                opt.classList.add('border-gray-200');
                opt.querySelector('.selected-indicator')?.classList.add('hidden');
            });
            
            // Add selected class to clicked option
            this.classList.remove('border-gray-200');
            this.classList.add('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
            this.querySelector('.selected-indicator')?.classList.remove('hidden');
            
            // Update form
            const mealId = this.dataset.mealId;
            mealIdInput.value = mealId;
            selectedMealId = mealId;
            updateMealPreview();
            validateForm();
            
            // Scroll to preview on mobile
            if (window.innerWidth < 1024) {
                mealPreview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    });

    // Handle meal type changes
    mealTypeInputs.forEach(input => {
        input.addEventListener('change', validateForm);
    });
    
    // Enhanced time input functionality
    const timeInput = document.getElementById('scheduled_time');
    
    // Add common meal times for quick selection
    function addTimeQuickSelect() {
        const timeContainer = timeInput.parentElement;
        const quickTimesDiv = document.createElement('div');
        quickTimesDiv.className = 'mt-2 flex flex-wrap gap-2';
        
        const commonTimes = [
            { label: 'Breakfast', time: '08:00', icon: '🌅' },
            { label: 'Lunch', time: '12:00', icon: '☀️' },
            { label: 'Dinner', time: '18:00', icon: '🌙' },
            { label: 'Snack', time: '15:00', icon: '🍎' }
        ];
        
        commonTimes.forEach(timeOption => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200';
            button.innerHTML = `${timeOption.icon} ${timeOption.label} (${timeOption.time})`;
            button.addEventListener('click', () => {
                timeInput.value = timeOption.time;
                timeInput.dispatchEvent(new Event('change'));
            });
            quickTimesDiv.appendChild(button);
        });
        
        timeContainer.appendChild(quickTimesDiv);
    }
    
    // Add quick time selection if on desktop
    if (window.innerWidth >= 768) {
        addTimeQuickSelect();
    }
    
    // Enhanced mobile touch handling
    timeInput.addEventListener('focus', function() {
        if (window.innerWidth < 768) {
            // Scroll input into view on mobile
            setTimeout(() => {
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        }
    });
    
    // Time input validation and formatting
    timeInput.addEventListener('change', function() {
        if (this.value) {
            // Provide visual feedback when time is selected
            this.classList.add('border-green-300', 'bg-green-50');
            setTimeout(() => {
                this.classList.remove('border-green-300', 'bg-green-50');
            }, 1000);
        }
    });

    function updateMealPreview() {
        if (selectedMealId) {
            const selectedMeal = Array.from(mealOptions).find(option => 
                option.dataset.mealId === selectedMealId
            );

            if (selectedMeal) {
                document.getElementById('preview-name').textContent = selectedMeal.dataset.mealName;
                document.getElementById('preview-description').textContent = selectedMeal.dataset.mealDescription;
                document.getElementById('preview-calories').textContent = selectedMeal.dataset.mealCalories;
                document.getElementById('preview-cost').textContent = selectedMeal.dataset.mealCost;
                mealPreview.classList.remove('hidden');
                
                // Add smooth reveal animation
                setTimeout(() => {
                    mealPreview.style.opacity = '1';
                    mealPreview.style.transform = 'translateY(0)';
                }, 100);
            }
        } else {
            mealPreview.classList.add('hidden');
        }
    }

    function validateForm() {
        const selectedMealType = document.querySelector('input[name="meal_type"]:checked');
        const isValid = selectedMealType && selectedMealId;
        
        if (isValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-green-600', 'hover:bg-green-700', 'cursor-pointer');
            formValidation.innerHTML = `
                <div class="flex items-center text-green-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ready to add "${document.getElementById('preview-name').textContent}" to your ${selectedMealType.value} plan.
                </div>
            `;
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700', 'cursor-pointer');
            
            let message = 'Please ';
            const missing = [];
            if (!selectedMealType) missing.push('select a meal type');
            if (!selectedMealId) missing.push('choose a meal');
            message += missing.join(' and ') + '.';
            
            formValidation.innerHTML = `
                <div class="flex items-center text-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ${message}
                </div>
            `;
        }
    }

    function clearSelection() {
        selectedMealId = null;
        mealIdInput.value = '';
        
        // Clear visual selection
        mealOptions.forEach(option => {
            option.classList.remove('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
            option.classList.add('border-gray-200');
            option.querySelector('.selected-indicator')?.classList.add('hidden');
        });
        
        mealPreview.classList.add('hidden');
        validateForm();
    }
    
    // Make clearSelection globally available
    window.clearSelection = clearSelection;

    // Initialize preview if meal is pre-selected
    if (selectedMealId) {
        updateMealPreview();
        
        // Highlight pre-selected meal
        const preSelectedMeal = Array.from(mealOptions).find(option => 
            option.dataset.mealId === selectedMealId
        );
        if (preSelectedMeal) {
            preSelectedMeal.classList.remove('border-gray-200');
            preSelectedMeal.classList.add('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
            preSelectedMeal.querySelector('.selected-indicator')?.classList.remove('hidden');
        }
    }
    
    // Initialize form validation
    validateForm();
    
    // Add smooth transitions for preview
    mealPreview.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    mealPreview.style.opacity = '0';
    mealPreview.style.transform = 'translateY(-10px)';
});

// Add some CSS for smooth transitions
const style = document.createElement('style');
style.textContent = `
    .meal-option {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .meal-option:hover {
        transform: translateY(-4px);
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Fix for Schedule Details positioning */
    #scheduled_date, #scheduled_time {
        position: relative !important;
        transform: none !important;
        left: auto !important;
        top: auto !important;
        margin: 0 !important;
    }
    
    /* Ensure form container is properly positioned */
    #meal-plan-form {
        position: relative;
    }
    
    /* Schedule Details container fix */
    .schedule-details-container {
        position: relative;
        z-index: 1;
    }
    
    /* Enhanced time input styling */
    #scheduled_time {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='%236b7280'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0Z' /%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px 16px;
        padding-right: 40px;
    }
    
    /* Mobile optimization for time input */
    @media (max-width: 768px) {
        #scheduled_time {
            font-size: 16px; /* Prevents zoom on iOS */
            padding: 12px 40px 12px 16px;
        }
        
        #scheduled_date {
            font-size: 16px; /* Prevents zoom on iOS */
            padding: 12px 16px;
        }
    }
    
    /* Enhanced focus states */
    #scheduled_time:focus,
    #scheduled_date:focus {
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        border-color: #22c55e;
        outline: none;
    }
    
    /* Hover effects */
    #scheduled_time:hover:not(:focus),
    #scheduled_date:hover:not(:focus) {
        border-color: #9ca3af;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    /* Time picker enhancement for webkit browsers */
    #scheduled_time::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        color: transparent;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: auto;
    }
    
    /* Custom styling for time input in different browsers */
    #scheduled_time::-webkit-datetime-edit-fields-wrapper {
        padding: 0;
    }
    
    #scheduled_time::-webkit-datetime-edit-text {
        color: #6b7280;
        padding: 0 2px;
    }
    
    #scheduled_time::-webkit-datetime-edit-hour-field,
    #scheduled_time::-webkit-datetime-edit-minute-field {
        background-color: transparent;
        border: none;
        color: #374151;
        font-weight: 500;
    }
    
    /* Accessibility improvements */
    #scheduled_time:focus-visible,
    #scheduled_date:focus-visible {
        outline: 2px solid #22c55e;
        outline-offset: 2px;
    }
`;
document.head.appendChild(style);
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\StudEats\resources\views/meal-plans/create.blade.php ENDPATH**/ ?>