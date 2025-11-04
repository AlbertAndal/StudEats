<?php $__env->startSection('title', 'Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-full bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Email Verification Status Alert -->
        <?php if(!$user->email_verified_at): ?>
        <div class="mb-8 bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-orange-400 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-orange-800">Email Verification Required</h3>
                            <p class="text-sm text-orange-700 mt-1">
                                Your email address <strong><?php echo e($user->email); ?></strong> needs to be verified to access all features and ensure account security.
                            </p>
                        </div>
                        <div class="flex items-center space-x-3 ml-4">
                            <form method="POST" action="<?php echo e(route('email.verify.resend')); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="email" value="<?php echo e($user->email); ?>">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-orange-300 text-sm font-medium rounded-lg text-orange-800 bg-orange-100 hover:bg-orange-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Resend Code
                                </button>
                            </form>
                            <a href="<?php echo e(route('email.verify.form')); ?>" 
                               class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 shadow-md hover:shadow-lg">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Verify Now
                            </a>
                        </div>
                    </div>
                    <div class="mt-4 bg-orange-100 rounded-lg p-3">
                        <h4 class="text-sm font-medium text-orange-800 mb-2">Why verify your email?</h4>
                        <ul class="text-sm text-orange-700 space-y-1">
                            <li class="flex items-center">
                                <svg class="h-3 w-3 text-orange-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Secure account recovery and password reset
                            </li>
                            <li class="flex items-center">
                                <svg class="h-3 w-3 text-orange-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Receive important notifications about your meal plans
                            </li>
                            <li class="flex items-center">
                                <svg class="h-3 w-3 text-orange-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Access premium features and personalized recommendations
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="mb-3 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 p-3 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        ✓ Your email address is verified and your account is fully secure!
                        <span class="text-green-600 ml-2">Verified on <?php echo e($user->email_verified_at->format('M d, Y \a\t h:i A')); ?></span>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="relative group">
                        <?php if($user->hasProfilePhoto()): ?>
                            <img class="h-20 w-20 rounded-full object-cover ring-4 ring-white shadow-lg transition-all duration-200 group-hover:shadow-xl" 
                                 src="<?php echo e($user->getAvatarUrl()); ?>" 
                                 alt="<?php echo e($user->name); ?>" 
                                 data-profile-image>
                        <?php else: ?>
                            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center ring-4 ring-white shadow-lg">
                                <span class="text-2xl font-bold text-white"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="absolute inset-0 rounded-full opacity-0 group-hover:opacity-100 bg-black bg-opacity-50 transition-opacity duration-200 cursor-pointer flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center space-x-2.5">
                        <h1 class="text-2xl font-bold text-gray-900"><?php echo e($user->name); ?></h1>
                        <?php if($user->email_verified_at): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Verified
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        <p class="text-sm text-gray-500"><?php echo e($user->email); ?></p>
                    </div>
                    <div class="mt-1 flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Member since <?php echo e($user->created_at->format('M d, Y')); ?></p>
                    </div>
                </div>
            </div>
            <div class="mt-6 md:mt-0">
                <a href="<?php echo e(route('profile.edit')); ?>" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Edit Profile
                </a>
            </div>
        </div>

        <div class="mt-6 space-y-6">
            <!-- Personal Information and Diet Preferences Group -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Personal Info + Diet Preferences -->
                <div class="space-y-4">
                    <!-- Personal Information -->
                    <section aria-labelledby="personal-information-title">
                        <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="px-4 py-3 bg-gradient-to-r from-blue-50/50 to-white border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 bg-blue-500/10 rounded-lg flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h2 id="personal-information-title" class="text-base font-semibold text-gray-900 leading-5">Personal Information</h2>
                                    <p class="text-xs text-gray-500">Basic details about your account</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                                <!-- Full Name -->
                                <div class="flex items-center gap-2 py-2">
                                    <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Full Name</dt>
                                        <dd class="text-sm font-semibold text-gray-900"><?php echo e($user->name); ?></dd>
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="flex items-center gap-2 py-2">
                                    <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                    <div class="flex-1">
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email</dt>
                                        <dd class="text-sm font-semibold text-gray-900 break-words"><?php echo e($user->email); ?></dd>
                                    </div>
                                </div>

                                <!-- Gender -->
                                <div class="flex items-center gap-2 py-2">
                                    <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13.5 5.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Gender</dt>
                                        <dd class="text-sm text-gray-900">
                                            <?php if($user->gender): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium <?php echo e($user->gender === 'male' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700'); ?>">
                                                    <?php echo e(ucfirst($user->gender)); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400 italic text-xs">Not specified</span>
                                            <?php endif; ?>
                                        </dd>
                                    </div>
                                </div>

                                <!-- Age -->
                                <div class="flex items-center gap-2 py-2">
                                    <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Age</dt>
                                        <dd class="text-sm font-semibold text-gray-900">
                                            <?php if($user->age): ?>
                                                <?php echo e($user->age); ?> years old
                                            <?php else: ?>
                                                <span class="text-gray-400 italic text-xs">Not specified</span>
                                            <?php endif; ?>
                                        </dd>
                                    </div>
                                </div>

                                <!-- Daily Budget -->
                                <div class="flex items-center gap-2 py-2">
                                    <svg class="h-4 w-4 text-gray-400 flex-shrink-0" aria-label="Daily budget icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Daily Budget</dt>
                                        <dd class="text-sm text-gray-900">
                                            <?php if($user->daily_budget): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                                    ₱<?php echo e(number_format($user->daily_budget, 0)); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400 italic text-xs">Not specified</span>
                                            <?php endif; ?>
                                        </dd>
                                    </div>
                                </div>

                                <!-- Member Since -->
                                <div class="flex items-center gap-2 py-2">
                                    <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Member Since</dt>
                                        <dd class="text-sm text-gray-900">
                                            <span class="font-medium"><?php echo e($user->created_at->format('M d, Y')); ?></span>
                                            <span class="text-xs text-gray-500 block"><?php echo e($user->created_at->diffForHumans()); ?></span>
                                        </dd>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </section>

                </div>

                <!-- Right Column: Health Information -->
                <div>
                    <!-- Health & Physical Information -->
                <section aria-labelledby="health-information-title" class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="px-4 py-3 bg-gradient-to-r from-emerald-50/50 to-white border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-emerald-500/10 rounded-lg flex items-center justify-center">
                                    <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h2 id="health-information-title" class="text-base font-semibold text-gray-900 leading-5">Health & Physical</h2>
                                <p class="text-xs text-gray-500">Physical stats and health metrics</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-8 py-6">
                            <?php if($user->height && $user->weight): ?>
                                <?php
                                    $bmi = $user->calculateBMI();
                                    $bmiCategory = $user->getBMICategory();
                                    $calorieMultiplier = $user->getCalorieMultiplier();
                                    
                                    // Calculate BMR using User model methods or fallback
                                    $basalMetabolicRate = 0;
                                    if ($user->gender && $user->age) {
                                        $heightInCm = $user->height_unit === 'ft' ? $user->height * 30.48 : $user->height;
                                        $weightInKg = $user->weight_unit === 'lbs' ? $user->weight * 0.453592 : $user->weight;
                                        
                                        if ($user->gender === 'male') {
                                            $basalMetabolicRate = 88.362 + (13.397 * $weightInKg) + (4.799 * $heightInCm) - (5.677 * $user->age);
                                        } else {
                                            $basalMetabolicRate = 447.593 + (9.247 * $weightInKg) + (3.098 * $heightInCm) - (4.330 * $user->age);
                                        }
                                    }
                                    
                                    $activityMultiplier = 1.2;
                                    if ($user->activity_level === 'lightly_active') $activityMultiplier = 1.375;
                                    elseif ($user->activity_level === 'moderately_active') $activityMultiplier = 1.55;
                                    elseif ($user->activity_level === 'very_active') $activityMultiplier = 1.725;
                                    
                                    $estimatedCalories = $basalMetabolicRate * $activityMultiplier;
                                ?>
                                
                                <dl class="space-y-5">
                                    <!-- Height -->
                                    <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-gray-100">
                                        <dt class="flex items-center gap-2 text-sm font-semibold text-gray-600 uppercase tracking-wide sm:w-48 mb-2 sm:mb-0">
                                            <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-6m0 0l5 6m-5-6v18"/>
                                            </svg>
                                            Height
                                        </dt>
                                        <dd class="text-base text-gray-900 sm:flex-1">
                                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                <?php echo e($user->height); ?> <?php echo e($user->height_unit); ?>

                                            </span>
                                        </dd>
                                    </div>

                                    <!-- Weight -->
                                    <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-gray-100">
                                        <dt class="flex items-center gap-2 text-sm font-semibold text-gray-600 uppercase tracking-wide sm:w-48 mb-2 sm:mb-0">
                                            <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                            </svg>
                                            Weight
                                        </dt>
                                        <dd class="text-base text-gray-900 sm:flex-1">
                                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                                <?php echo e($user->weight); ?> <?php echo e($user->weight_unit); ?>

                                            </span>
                                        </dd>
                                    </div>

                                    <!-- BMI -->
                                    <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-gray-100">
                                        <dt class="flex items-center gap-2 text-sm font-semibold text-gray-600 uppercase tracking-wide sm:w-48 mb-2 sm:mb-0">
                                            <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            BMI
                                        </dt>
                                        <dd class="text-base text-gray-900 sm:flex-1">
                                            <?php if($bmi): ?>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-xl font-bold text-gray-900"><?php echo e(number_format($bmi, 1)); ?></span>
                                                    <?php
                                                        $bmiColorClass = '';
                                                        $bmiIcon = '';
                                                        if ($bmiCategory === 'Underweight') {
                                                            $bmiColorClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                                            $bmiIcon = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                                                        } elseif ($bmiCategory === 'Normal') {
                                                            $bmiColorClass = 'bg-green-50 text-green-700 border-green-200';
                                                            $bmiIcon = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
                                                        } elseif ($bmiCategory === 'Overweight') {
                                                            $bmiColorClass = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                                            $bmiIcon = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z';
                                                        } else {
                                                            $bmiColorClass = 'bg-red-50 text-red-700 border-red-200';
                                                            $bmiIcon = 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                                                        }
                                                    ?>
                                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-semibold border <?php echo e($bmiColorClass); ?>">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($bmiIcon); ?>"/>
                                                        </svg>
                                                        <?php echo e($bmiCategory); ?>

                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </dd>
                                    </div>

                                    <!-- Activity Level -->
                                    <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-gray-100">
                                        <dt class="flex items-center gap-2 text-sm font-semibold text-gray-600 uppercase tracking-wide sm:w-48 mb-2 sm:mb-0">
                                            <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Activity Level
                                        </dt>
                                        <dd class="text-base text-gray-900 sm:flex-1">
                                            <?php if($user->activity_level): ?>
                                                <?php
                                                    $activityColorClass = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                                    switch($user->activity_level) {
                                                        case 'sedentary':
                                                            $activityColorClass = 'bg-gray-50 text-gray-700 border-gray-200';
                                                            break;
                                                        case 'lightly_active':
                                                            $activityColorClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                                            break;
                                                        case 'moderately_active':
                                                            $activityColorClass = 'bg-green-50 text-green-700 border-green-200';
                                                            break;
                                                        case 'very_active':
                                                            $activityColorClass = 'bg-orange-50 text-orange-700 border-orange-200';
                                                            break;
                                                    }
                                                ?>
                                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-semibold border <?php echo e($activityColorClass); ?>">
                                                    <?php echo e(ucwords(str_replace('_', ' ', $user->activity_level))); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400 italic text-sm">Not specified</span>
                                            <?php endif; ?>
                                        </dd>
                                    </div>

                                    <?php if($estimatedCalories > 0): ?>
                                    <!-- Recommended Daily Calories -->
                                    <div class="flex flex-col py-4 bg-gradient-to-r from-emerald-50/50 to-green-50/50 rounded-xl border border-emerald-200 px-6 mt-2">
                                        <dt class="flex items-center gap-2 text-sm font-semibold text-emerald-700 uppercase tracking-wide mb-3">
                                            <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                                            </svg>
                                            Recommended Daily Calories
                                        </dt>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400 italic text-sm">Not specified</span>
                                            <?php endif; ?>
                                        </dd>
                                    </div>

                                    <?php if($estimatedCalories > 0): ?>
                                    <!-- Recommended Daily Calories -->
                                    <div class="flex flex-col py-4 bg-gradient-to-r from-emerald-50/50 to-green-50/50 rounded-xl border border-emerald-200 px-6 mt-2">
                                        <dt class="flex items-center gap-2 text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">
                                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                                            </svg>
                                            Recommended Daily Calories
                                        </dt>
                                        <dd class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-3xl font-bold text-emerald-800"><?php echo e(number_format($estimatedCalories, 0)); ?></span>
                                                <span class="text-lg text-emerald-600 font-medium">calories/day</span>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm text-emerald-600 font-medium">BMR: <?php echo e(number_format($basalMetabolicRate, 0)); ?></div>
                                                <div class="text-xs text-emerald-500">Activity Factor: <?php echo e($activityMultiplier); ?>x</div>
                                            </div>
                                        </dd>
                                    </div>
                                    <?php endif; ?>
                                </dl>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <div class="flex justify-center mb-4">
                                        <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Complete Your Health Profile</h3>
                                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Add your height and weight to unlock personalized nutrition recommendations and BMI tracking.</p>
                                    <a href="<?php echo e(route('profile.edit')); ?>" 
                                       class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                                        <svg class="-ml-1 mr-3 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Add Health Information
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                </div>
            </div>

            <!-- Additional Sections -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Quick Actions -->
                <section aria-labelledby="quick-actions-title">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="px-4 py-3 bg-gradient-to-r from-green-50/50 to-white border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-green-500/10 rounded-lg flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h2 id="quick-actions-title" class="text-base font-semibold text-gray-900 leading-5">Quick Actions</h2>
                                <p class="text-xs text-gray-500">Manage your meal planning</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="<?php echo e(route('meal-plans.create')); ?>" 
                               class="group flex items-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 hover:border-green-300 hover:shadow-md transition-all duration-200">
                                <div class="h-8 w-8 bg-green-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-105 transition-transform duration-200">
                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">Create Meal Plan</p>
                            </a>
                            
                            <a href="<?php echo e(route('recipes.index')); ?>" 
                               class="group flex items-center p-3 bg-gradient-to-r from-blue-50 to-sky-50 rounded-lg border border-blue-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                                <div class="h-8 w-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-105 transition-transform duration-200">
                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">Browse Recipes</p>
                            </a>
                            
                            <a href="<?php echo e(route('meal-plans.weekly')); ?>" 
                               class="group flex items-center p-3 bg-gradient-to-r from-purple-50 to-violet-50 rounded-lg border border-purple-200 hover:border-purple-300 hover:shadow-md transition-all duration-200">
                                <div class="h-8 w-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-105 transition-transform duration-200">
                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">Weekly Plan</p>
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Account Security -->
                <section aria-labelledby="account-security-title">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="px-4 py-3 bg-gradient-to-r from-red-50/50 to-white border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 bg-red-500/10 rounded-lg flex items-center justify-center">
                                        <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h2 id="account-security-title" class="text-base font-semibold text-gray-900 leading-5">Account Security</h2>
                                    <p class="text-xs text-gray-500">Security status and settings</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-400">📧</span>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-900">Email Verification</dt>
                                            <dd class="text-xs text-gray-500">Account security status</dd>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <?php if($user->email_verified_at): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <svg class="-ml-0.5 mr-1 h-3 w-3 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Verified
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <svg class="-ml-0.5 mr-1 h-3 w-3 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Pending
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-400">👤</span>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-900">Account Status</dt>
                                            <dd class="text-xs text-gray-500">Current account state</dd>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <?php if($user->is_active ?? true): ?>
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Active
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ⚠ Suspended
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-400">🔐</span>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-900">Password</dt>
                                            <dd class="text-xs text-gray-500">Account security</dd>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <a href="<?php echo e(route('profile.change-password')); ?>" 
                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                                            Change Password
                                        </a>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                        </svg>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-900">Password</dt>
                                            <dd class="text-xs text-gray-500 mt-0.5">Secure your account access</dd>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <a href="<?php echo e(route('profile.change-password')); ?>" 
                                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                                            <svg class="-ml-0.5 mr-1 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                            </svg>
                                            Change
                                        </a>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Security Score & Recommendations -->
                        <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-6 border-t border-gray-100">
                            <?php
                                $securityScore = 0;
                                $maxScore = 100;
                                $recommendations = [];
                                
                                // Calculate security score
                                if ($user->email_verified_at) {
                                    $securityScore += 50; // Increased weight for email verification
                                } else {
                                    $recommendations[] = 'Verify your email address immediately';
                                }
                                
                                if ($user->is_active ?? true) {
                                    $securityScore += 20;
                                }
                                
                                // Additional checks
                                if ($user->height && $user->weight) {
                                    $securityScore += 15; // Profile completeness
                                } else {
                                    $recommendations[] = 'Complete your health profile';
                                }
                                
                                if ($user->dietary_preferences && trim($user->dietary_preferences) !== '') {
                                    $securityScore += 15; // Profile completeness
                                } else {
                                    $recommendations[] = 'Add dietary preferences';
                                }
                                
                                $scoreColor = 'text-red-600';
                                $scoreBg = 'bg-red-100';
                                if ($securityScore >= 80) {
                                    $scoreColor = 'text-green-600';
                                    $scoreBg = 'bg-green-100';
                                } elseif ($securityScore >= 60) {
                                    $scoreColor = 'text-yellow-600';
                                    $scoreBg = 'bg-yellow-100';
                                }
                            ?>
                            
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-900">Security Score</h4>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold <?php echo e($scoreBg); ?> <?php echo e($scoreColor); ?>">
                                    <?php echo e($securityScore); ?>%
                                </span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                                <div class="h-1.5 rounded-full transition-all duration-300 <?php echo e($securityScore >= 80 ? 'bg-green-500' : ($securityScore >= 60 ? 'bg-yellow-500' : 'bg-red-500')); ?>" 
                                     style="width: <?php echo e($securityScore); ?>%"></div>
                            </div>
                            
                            <?php if(count($recommendations) > 0): ?>
                                <div class="text-xs text-gray-600">
                                    <span class="font-medium">Recommendations:</span>
                                    <?php echo e(implode(', ', $recommendations)); ?>

                                </div>
                            <?php else: ?>
                                <div class="text-xs text-green-600 font-medium">
                                    ✓ Your profile is well-secured and complete!
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure CSRF token is available
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '<?php echo e(csrf_token()); ?>';
            document.head.appendChild(meta);
        }

        // Enhanced loading states for navigation links with subtle animations
        const actionLinks = document.querySelectorAll('a[href*="meal-plans"], a[href*="recipes"], a[href*="profile"]');
        actionLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Add loading animation
                this.style.transform = 'scale(0.98)';
                this.style.opacity = '0.8';
                this.style.transition = 'all 0.2s ease';
                
                // Add loading indicator if it's a navigation action
                const loadingSpinner = document.createElement('div');
                loadingSpinner.className = 'inline-block animate-spin rounded-full h-3 w-3 border-b-2 border-current ml-2';
                this.appendChild(loadingSpinner);
                
                // Restore after navigation or timeout
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                    this.style.opacity = '1';
                    if (loadingSpinner.parentNode) {
                        loadingSpinner.remove();
                    }
                }, 2000);
            });
        });

        // Enhanced photo upload functionality with preview and validation
        const profileImage = document.querySelector('[data-profile-image]');
        if (profileImage) {
            profileImage.addEventListener('click', function() {
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = 'image/jpeg,image/jpg,image/png,image/gif';
                fileInput.style.display = 'none';
                
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file size (max 5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            showNotification('File size must be less than 5MB', 'error');
                            return;
                        }
                        
                        // Validate file type
                        if (!file.type.startsWith('image/')) {
                            showNotification('Please select a valid image file', 'error');
                            return;
                        }
                        
                        // Show loading state
                        const originalSrc = profileImage.src;
                        profileImage.style.opacity = '0.6';
                        profileImage.style.transition = 'opacity 0.3s ease';
                        
                        // Preview the image
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            profileImage.src = e.target.result;
                            profileImage.style.opacity = '1';
                            showNotification('Photo updated! Remember to save your changes.', 'success');
                        };
                        
                        reader.onerror = function() {
                            profileImage.src = originalSrc;
                            profileImage.style.opacity = '1';
                            showNotification('Error reading file. Please try again.', 'error');
                        };
                        
                        reader.readAsDataURL(file);
                    }
                });
                
                document.body.appendChild(fileInput);
                fileInput.click();
                document.body.removeChild(fileInput);
            });
            
            // Add hover effect
            profileImage.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.transition = 'transform 0.2s ease';
            });
            
            profileImage.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        }

        // Animate security score progress bar on load
        const progressBar = document.querySelector('.bg-green-500, .bg-yellow-500, .bg-red-500');
        if (progressBar) {
            const targetWidth = progressBar.style.width;
            progressBar.style.width = '0%';
            
            setTimeout(() => {
                progressBar.style.transition = 'width 1.5s ease-out';
                progressBar.style.width = targetWidth;
            }, 500);
        }

        // Add intersection observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all sections for scroll animations
        const sections = document.querySelectorAll('section[aria-labelledby]');
        sections.forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(section);
        });

        // Enhanced dietary preference tooltips
        const preferenceItems = document.querySelectorAll('.group.relative');
        preferenceItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const tooltip = this.querySelector('.absolute.bottom-full');
                if (tooltip) {
                    tooltip.style.opacity = '1';
                    tooltip.style.transform = 'translateX(-50%) translateY(-2px)';
                    tooltip.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                }
            });
            
            item.addEventListener('mouseleave', function() {
                const tooltip = this.querySelector('.absolute.bottom-full');
                if (tooltip) {
                    tooltip.style.opacity = '0';
                    tooltip.style.transform = 'translateX(-50%) translateY(0)';
                }
            });
        });

        // Keyboard navigation improvements
        document.addEventListener('keydown', function(e) {
            // Quick access shortcuts
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'e':
                        e.preventDefault();
                        const editButton = document.querySelector('a[href*="profile.edit"]');
                        if (editButton) editButton.click();
                        break;
                    case 'm':
                        e.preventDefault();
                        const mealPlanButton = document.querySelector('a[href*="meal-plans.create"]');
                        if (mealPlanButton) mealPlanButton.click();
                        break;
                }
            }
        });

        // Utility function for notifications
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto transition-all duration-300 transform translate-x-full`;
            
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            
            notification.innerHTML = `
                <div class="${bgColor} text-white p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            ${type === 'success' ? 
                                '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                                type === 'error' ?
                                '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>' :
                                '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
                            }
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <div class="ml-3 flex-shrink-0">
                            <button class="inline-flex text-white hover:text-gray-200 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Enhanced verification status interactions
        const verificationAlert = document.querySelector('[data-verification-alert]');
        if (verificationAlert) {
            // Add pulse animation for unverified accounts
            verificationAlert.classList.add('animate-pulse');
            
            // Remove pulse on hover
            verificationAlert.addEventListener('mouseenter', function() {
                this.classList.remove('animate-pulse');
            });
            
            verificationAlert.addEventListener('mouseleave', function() {
                this.classList.add('animate-pulse');
            });
        }

        // Handle resend verification code
        const resendForms = document.querySelectorAll('form[action*="email.verify.resend"]');
        resendForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                
                // Show loading state
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin -ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                `;
                
                // Reset after 3 seconds if no response
                setTimeout(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }, 3000);
            });
        });

        // Enhanced verification link styling
        const verifyLinks = document.querySelectorAll('a[href*="email.verify.form"]');
        verifyLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Add a subtle animation
                this.style.transform = 'scale(0.95)';
                this.style.transition = 'transform 0.1s ease';
                
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            });
        });

        // Initial page load animation
        setTimeout(() => {
            const header = document.querySelector('.max-w-3xl.mx-auto.px-4');
            if (header) {
                header.style.opacity = '1';
                header.style.transform = 'translateY(0)';
            }
        }, 100);
        
        // Add verification status indicator animation
        const verificationBadges = document.querySelectorAll('[data-verification-badge]');
        verificationBadges.forEach((badge, index) => {
            setTimeout(() => {
                badge.style.opacity = '1';
                badge.style.transform = 'scale(1)';
            }, 200 + (index * 100));
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\StudEats\resources\views/profile/show.blade.php ENDPATH**/ ?>