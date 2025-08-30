

<?php $__env->startSection('title', 'Edit Profile'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-section {
        transition: all 0.3s ease;
    }
    
    .form-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .field-group {
        position: relative;
    }
    
    .field-group input:focus + label,
    .field-group input:valid + label {
        transform: translateY(-1.5rem) scale(0.9);
        color: #10b981;
    }
    
    .progress-ring {
        transition: stroke-dasharray 0.3s ease;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
                <p class="mt-2 text-gray-600">Update your personal information and preferences to get better meal recommendations</p>
            </div>
            <div class="hidden sm:block">
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-2 text-sm text-green-800">Complete your profile for personalized recommendations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700">Saving changes...</span>
        </div>
    </div>

    <form id="profile-form" action="<?php echo e(route('profile.update')); ?>" method="POST" class="space-y-8">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <!-- Personal Information -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-blue-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="h-5 w-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    Personal Information
                </h2>
                <p class="mt-1 text-sm text-gray-600">Basic information about you</p>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('name', $user->name)); ?>"
                           placeholder="Enter your full name">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('email', $user->email)); ?>"
                           placeholder="Enter your email address">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Age -->
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Age</label>
                    <input type="number" id="age" name="age" min="13" max="120"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('age', $user->age)); ?>"
                           placeholder="Enter your age">
                    <p class="mt-1 text-xs text-gray-500">Helps us provide age-appropriate meal recommendations</p>
                    <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select id="gender" name="gender"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Select your gender</option>
                        <option value="male" <?php echo e(old('gender', $user->gender) == 'male' ? 'selected' : ''); ?>>Male</option>
                        <option value="female" <?php echo e(old('gender', $user->gender) == 'female' ? 'selected' : ''); ?>>Female</option>
                        <option value="other" <?php echo e(old('gender', $user->gender) == 'other' ? 'selected' : ''); ?>>Other</option>
                        <option value="prefer_not_to_say" <?php echo e(old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : ''); ?>>Prefer not to say</option>
                    </select>
                    <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Timezone -->
                <div class="md:col-span-2">
                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                    <select id="timezone" name="timezone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Select your timezone</option>
                        <option value="Asia/Manila" <?php echo e(old('timezone', $user->timezone) == 'Asia/Manila' ? 'selected' : ''); ?>>Asia/Manila (Philippine Time)</option>
                        <option value="Asia/Singapore" <?php echo e(old('timezone', $user->timezone) == 'Asia/Singapore' ? 'selected' : ''); ?>>Asia/Singapore</option>
                        <option value="Asia/Jakarta" <?php echo e(old('timezone', $user->timezone) == 'Asia/Jakarta' ? 'selected' : ''); ?>>Asia/Jakarta</option>
                        <option value="Asia/Bangkok" <?php echo e(old('timezone', $user->timezone) == 'Asia/Bangkok' ? 'selected' : ''); ?>>Asia/Bangkok</option>
                        <option value="UTC" <?php echo e(old('timezone', $user->timezone) == 'UTC' ? 'selected' : ''); ?>>UTC</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Used for meal planning schedules and notifications</p>
                    <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Physical Information -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="h-5 w-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Physical Information
                </h2>
                <p class="mt-1 text-sm text-gray-600">Help us calculate your nutritional needs (optional)</p>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Height -->
                <div>
                    <label for="height" class="block text-sm font-medium text-gray-700 mb-2">Height</label>
                    <div class="flex space-x-2">
                        <input type="number" id="height" name="height" min="100" max="250" step="0.1"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors <?php $__errorArgs = ['height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('height', $user->height)); ?>"
                               placeholder="170">
                        <select name="height_unit" 
                                class="px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="cm" <?php echo e(old('height_unit', $user->height_unit ?? 'cm') == 'cm' ? 'selected' : ''); ?>>cm</option>
                            <option value="ft" <?php echo e(old('height_unit', $user->height_unit) == 'ft' ? 'selected' : ''); ?>>ft</option>
                        </select>
                    </div>
                    <?php $__errorArgs = ['height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Weight -->
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight</label>
                    <div class="flex space-x-2">
                        <input type="number" id="weight" name="weight" min="30" max="300" step="0.1"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors <?php $__errorArgs = ['weight'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('weight', $user->weight)); ?>"
                               placeholder="65">
                        <select name="weight_unit" 
                                class="px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="kg" <?php echo e(old('weight_unit', $user->weight_unit ?? 'kg') == 'kg' ? 'selected' : ''); ?>>kg</option>
                            <option value="lbs" <?php echo e(old('weight_unit', $user->weight_unit) == 'lbs' ? 'selected' : ''); ?>>lbs</option>
                        </select>
                    </div>
                    <?php $__errorArgs = ['weight'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Activity Level -->
                <div class="md:col-span-2">
                    <label for="activity_level" class="block text-sm font-medium text-gray-700 mb-2">Activity Level</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                        <?php
                            $activityLevels = [
                                'sedentary' => ['Sedentary', 'Little to no exercise'],
                                'lightly_active' => ['Lightly Active', '1-3 days/week'],
                                'moderately_active' => ['Moderately Active', '3-5 days/week'],
                                'very_active' => ['Very Active', '6-7 days/week'],
                                'extremely_active' => ['Extremely Active', '2x/day or intense training']
                            ];
                        ?>
                        
                        <?php $__currentLoopData = $activityLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="relative flex flex-col p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors <?php echo e(old('activity_level', $user->activity_level) == $value ? 'bg-green-50 border-green-500' : 'border-gray-300'); ?>">
                                <input type="radio" name="activity_level" value="<?php echo e($value); ?>" 
                                       class="sr-only peer"
                                       <?php echo e(old('activity_level', $user->activity_level) == $value ? 'checked' : ''); ?>>
                                <div class="text-sm font-medium text-gray-900 peer-checked:text-green-700"><?php echo e($info[0]); ?></div>
                                <div class="text-xs text-gray-500 peer-checked:text-green-600"><?php echo e($info[1]); ?></div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-green-500 rounded-lg pointer-events-none"></div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Helps us calculate your daily caloric needs</p>
                    <?php $__errorArgs = ['activity_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Dietary Preferences -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="h-5 w-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Dietary Preferences & Budget
                </h2>
                <p class="mt-1 text-sm text-gray-600">Customize your meal recommendations</p>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Daily Budget -->
                <div>
                    <label for="daily_budget" class="block text-sm font-medium text-gray-700 mb-2">Daily Food Budget (₱)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₱</span>
                        </div>
                        <input type="number" id="daily_budget" name="daily_budget" min="100" max="2000" step="10"
                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors <?php $__errorArgs = ['daily_budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('daily_budget', $user->daily_budget)); ?>"
                               placeholder="300">
                    </div>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button type="button" class="budget-preset px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-value="200">₱200 - Tight</button>
                        <button type="button" class="budget-preset px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-value="300">₱300 - Standard</button>
                        <button type="button" class="budget-preset px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-value="500">₱500 - Comfortable</button>
                        <button type="button" class="budget-preset px-3 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-value="800">₱800 - Premium</button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Recommended: ₱270-₱390 for students</p>
                    <?php $__errorArgs = ['daily_budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Dietary Preferences -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Dietary Preferences</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        <?php
                            $preferences = [
                                'vegetarian' => ['🥬', 'Vegetarian'],
                                'vegan' => ['🌱', 'Vegan'],
                                'pescatarian' => ['🐟', 'Pescatarian'],
                                'gluten_free' => ['🌾', 'Gluten Free'],
                                'dairy_free' => ['🥛', 'Dairy Free'],
                                'low_carb' => ['🥩', 'Low Carb'],
                                'high_protein' => ['💪', 'High Protein'],
                                'keto' => ['🥑', 'Keto'],
                                'paleo' => ['🦕', 'Paleo'],
                                'mediterranean' => ['🫒', 'Mediterranean']
                            ];
                        ?>
                        
                        <?php $__currentLoopData = $preferences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="relative flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors <?php echo e(in_array($value, old('dietary_preferences', $user->dietary_preferences ?? [])) ? 'bg-green-50 border-green-500' : 'border-gray-300'); ?>">
                                <input type="checkbox" name="dietary_preferences[]" value="<?php echo e($value); ?>" 
                                       class="sr-only peer"
                                       <?php echo e(in_array($value, old('dietary_preferences', $user->dietary_preferences ?? [])) ? 'checked' : ''); ?>>
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg"><?php echo e($info[0]); ?></span>
                                    <span class="text-sm font-medium text-gray-700 peer-checked:text-green-700"><?php echo e($info[1]); ?></span>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-green-500 rounded-lg pointer-events-none"></div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Select all that apply to get personalized meal recommendations</p>
                    <?php $__errorArgs = ['dietary_preferences'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Success Message Container -->
        <div id="success-message" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="ml-2 text-sm text-green-800" id="success-text">Profile updated successfully!</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
            <a href="<?php echo e(route('profile.show')); ?>" 
               class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl"
                    id="save-button">
                <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span id="save-text">Save Changes</span>
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/profile-edit.js']); ?>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\StudEats\resources\views/profile/edit.blade.php ENDPATH**/ ?>