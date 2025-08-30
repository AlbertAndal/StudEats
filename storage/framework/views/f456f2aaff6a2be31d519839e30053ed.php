

<?php $__env->startSection('title', 'Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center shadow-lg">
                <span class="text-3xl font-bold text-white"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-foreground"><?php echo e($user->name); ?></h1>
                <p class="text-muted-foreground"><?php echo e($user->email); ?></p>
            </div>
        </div>
    </div>

    <!-- Profile Completion -->
    <?php
        $totalFields = 10;
        $completedFields = 0;
        if ($user->name) $completedFields++;
        if ($user->email) $completedFields++;
        if ($user->age) $completedFields++;
        if ($user->daily_budget) $completedFields++;
        if ($user->gender) $completedFields++;
        if ($user->activity_level) $completedFields++;
        if ($user->height) $completedFields++;
        if ($user->weight) $completedFields++;
        if ($user->dietary_preferences && count($user->dietary_preferences) > 0) $completedFields++;
        if ($user->email_verified_at) $completedFields++;
        $completionPercentage = ($completedFields / $totalFields) * 100;
    ?>

    <?php if($completionPercentage < 100): ?>
    <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-blue-900">Complete your profile</h3>
            <span class="text-sm text-blue-700 font-medium"><?php echo e(round($completionPercentage)); ?>%</span>
        </div>
        <div class="w-full bg-blue-200 rounded-full h-2 mb-3">
            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: <?php echo e($completionPercentage); ?>%"></div>
        </div>
        <p class="text-sm text-blue-700">Complete your profile to get personalized meal recommendations and better nutrition insights.</p>
        <a href="<?php echo e(route('profile.edit')); ?>" class="inline-flex items-center mt-2 text-sm font-medium text-blue-600 hover:text-blue-500">
            Complete Profile
            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Personal Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-card shadow-sm border border-border rounded-lg">
                <div class="px-6 py-4 border-b border-border">
                    <h2 class="text-lg font-semibold text-card-foreground">Personal Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-muted-foreground">Full Name</label>
                            <p class="text-sm text-card-foreground font-medium"><?php echo e($user->name); ?></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-muted-foreground">Email Address</label>
                            <p class="text-sm text-card-foreground font-medium"><?php echo e($user->email); ?></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-muted-foreground">Age</label>
                            <p class="text-sm text-card-foreground font-medium"><?php echo e($user->age ?? 'Not specified'); ?></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-muted-foreground">Daily Budget</label>
                            <p class="text-sm text-card-foreground font-medium">
                                <?php if($user->daily_budget): ?>
                                    ₱<?php echo e(number_format($user->daily_budget, 0)); ?>

                                <?php else: ?>
                                    Not set
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Physical Information Card -->
            <div class="bg-card shadow-sm border border-border rounded-lg">
                <div class="px-6 py-4 border-b border-border">
                    <h2 class="text-lg font-semibold text-card-foreground">Physical Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-muted-foreground">Gender</label>
                            <p class="text-sm text-card-foreground font-medium"><?php echo e($user->gender ? ucfirst($user->gender) : 'Not specified'); ?></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-muted-foreground">Activity Level</label>
                            <p class="text-sm text-card-foreground font-medium">
                                <?php if($user->activity_level): ?>
                                    <?php echo e(ucwords(str_replace('_', ' ', $user->activity_level))); ?>

                                <?php else: ?>
                                    Not specified
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-muted-foreground">Height</label>
                            <p class="text-sm text-card-foreground font-medium">
                                <?php if($user->height): ?>
                                    <?php echo e($user->height); ?> <?php echo e($user->height_unit); ?>

                                <?php else: ?>
                                    Not specified
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-muted-foreground">Weight</label>
                            <p class="text-sm text-card-foreground font-medium">
                                <?php if($user->weight): ?>
                                    <?php echo e($user->weight); ?> <?php echo e($user->weight_unit); ?>

                                <?php else: ?>
                                    Not specified
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Health Insights Card -->
            <?php if($user->height && $user->weight): ?>
            <?php
                // Convert height to meters for BMI calculation
                $heightInMeters = $user->height_unit === 'ft' 
                    ? $user->height * 0.3048 
                    : $user->height / 100;
                
                // Convert weight to kg for BMI calculation
                $weightInKg = $user->weight_unit === 'lbs' 
                    ? $user->weight * 0.453592 
                    : $user->weight;
                
                $bmi = $weightInKg / ($heightInMeters * $heightInMeters);
                
                // BMI Categories
                $bmiCategory = '';
                $bmiColor = '';
                if ($bmi < 18.5) {
                    $bmiCategory = 'Underweight';
                    $bmiColor = 'text-blue-600';
                } elseif ($bmi < 25) {
                    $bmiCategory = 'Normal weight';
                    $bmiColor = 'text-green-600';
                } elseif ($bmi < 30) {
                    $bmiCategory = 'Overweight';
                    $bmiColor = 'text-yellow-600';
                } else {
                    $bmiCategory = 'Obese';
                    $bmiColor = 'text-red-600';
                }
                
                // Estimated daily calorie needs (simplified calculation)
                $basalMetabolicRate = 0;
                if ($user->gender === 'male') {
                    $basalMetabolicRate = 88.362 + (13.397 * $weightInKg) + (4.799 * ($heightInMeters * 100)) - (5.677 * ($user->age ?? 25));
                } else {
                    $basalMetabolicRate = 447.593 + (9.247 * $weightInKg) + (3.098 * ($heightInMeters * 100)) - (4.330 * ($user->age ?? 25));
                }
                
                // Activity multiplier
                $activityMultiplier = 1.2; // sedentary
                if ($user->activity_level === 'lightly_active') $activityMultiplier = 1.375;
                elseif ($user->activity_level === 'moderately_active') $activityMultiplier = 1.55;
                elseif ($user->activity_level === 'very_active') $activityMultiplier = 1.725;
                
                $estimatedCalories = $basalMetabolicRate * $activityMultiplier;
            ?>
            <div class="bg-card shadow-sm border border-border rounded-lg">
                <div class="px-6 py-4 border-b border-border">
                    <h2 class="text-lg font-semibold text-card-foreground">Health Insights</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-muted-foreground">BMI (Body Mass Index)</label>
                            <p class="text-lg font-bold <?php echo e($bmiColor); ?>"><?php echo e(number_format($bmi, 1)); ?></p>
                            <p class="text-sm <?php echo e($bmiColor); ?> font-medium"><?php echo e($bmiCategory); ?></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-muted-foreground">Estimated Daily Calories</label>
                            <p class="text-lg font-bold text-card-foreground"><?php echo e(number_format($estimatedCalories, 0)); ?></p>
                            <p class="text-sm text-muted-foreground">Based on your activity level</p>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-muted rounded-md">
                        <p class="text-sm text-muted-foreground">
                            <strong>Note:</strong> These calculations are estimates. Consult with a healthcare professional for personalized advice.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dietary Preferences Card -->
            <div class="bg-card shadow-sm border border-border rounded-lg">
                <div class="px-6 py-4 border-b border-border">
                    <h2 class="text-lg font-semibold text-card-foreground">Dietary Preferences</h2>
                </div>
                <div class="p-6">
                    <?php if($user->dietary_preferences && count($user->dietary_preferences) > 0): ?>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $user->dietary_preferences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $preference): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $preference))); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <svg class="mx-auto h-12 w-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-muted-foreground">No dietary preferences set</p>
                            <a href="<?php echo e(route('profile.edit')); ?>" class="mt-2 inline-flex items-center text-sm text-primary hover:text-primary/80">
                                Add preferences
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('profile.edit')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-primary text-primary-foreground text-sm font-medium rounded-md hover:bg-primary/90 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Profile
                </a>
                <a href="<?php echo e(route('profile.change-password')); ?>" 
                   class="inline-flex items-center px-4 py-2 border border-input bg-background text-foreground text-sm font-medium rounded-md hover:bg-accent hover:text-accent-foreground transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Change Password
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-card shadow-sm border border-border rounded-lg">
                <div class="px-6 py-4 border-b border-border">
                    <h2 class="text-lg font-semibold text-card-foreground">Quick Actions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="<?php echo e(route('meal-plans.create')); ?>" 
                           class="flex items-center p-3 text-sm font-medium text-card-foreground bg-muted/50 rounded-lg hover:bg-muted transition-colors group">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Add Meal Plan</p>
                                <p class="text-xs text-muted-foreground">Plan your next meal</p>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('recipes.index')); ?>" 
                           class="flex items-center p-3 text-sm font-medium text-card-foreground bg-muted/50 rounded-lg hover:bg-muted transition-colors group">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Browse Recipes</p>
                                <p class="text-xs text-muted-foreground">Discover new dishes</p>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('meal-plans.weekly')); ?>" 
                           class="flex items-center p-3 text-sm font-medium text-card-foreground bg-muted/50 rounded-lg hover:bg-muted transition-colors group">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Weekly Meal Plan</p>
                                <p class="text-xs text-muted-foreground">View your week ahead</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Security -->
            <div class="bg-card shadow-sm border border-border rounded-lg">
                <div class="px-6 py-4 border-b border-border">
                    <h2 class="text-lg font-semibold text-card-foreground">Account Security</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-card-foreground">Email Verification</h3>
                                    <p class="text-xs text-muted-foreground">Secure your account</p>
                                </div>
                            </div>
                            <?php if($user->email_verified_at): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    Verified
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    Pending
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-card-foreground">Two-Factor Auth</h3>
                                    <p class="text-xs text-muted-foreground">Extra security layer</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                Available Soon
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-card-foreground">Account Status</h3>
                                    <p class="text-xs text-muted-foreground">Your account security</p>
                                </div>
                            </div>
                            <?php if($user->is_active): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    Active
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    Suspended
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\StudEats\resources\views/profile/show.blade.php ENDPATH**/ ?>