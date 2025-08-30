

<?php $__env->startSection('title', 'Register'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen grid md:grid-cols-2">
    <!-- Cover / Image Side -->
    <div class="hidden md:flex flex-col relative overflow-hidden bg-gradient-to-br from-green-600 to-emerald-500 text-white p-10">
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=2153&q=80" 
                 alt="Healthy meal preparation" 
                 class="w-full h-full object-cover">
        </div>
        <div class="relative z-10 flex flex-col h-full">
            <div class="flex items-center gap-3 text-2xl font-semibold">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/10 backdrop-blur-sm">🍽️</span>
                StudEats
            </div>
            <div class="mt-auto">
                <h1 class="text-4xl font-bold leading-tight">Fuel better habits with smarter meal planning.</h1>
                <p class="mt-4 max-w-md text-white/90 text-sm">Track nutrition, manage budget-friendly meals, and simplify your weekly planning. Create your account and start building a healthier routine today.</p>
            </div>
            <div class="mt-10 flex items-center gap-8 text-xs uppercase tracking-wide text-white/60">
                <span>Budget Aware</span>
                <span class="h-1 w-1 rounded-full bg-white/40"></span>
                <span>Adaptive Planning</span>
                <span class="h-1 w-1 rounded-full bg-white/40"></span>
                <span>Nutrition Focused</span>
            </div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="flex flex-col justify-center px-6 py-12 md:px-12 lg:px-20 bg-background">
        <div class="w-full max-w-md mx-auto">
            <div class="md:hidden mb-8 flex items-center gap-2 justify-center">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-2xl">🍽️</span>
                <span class="text-xl font-semibold">StudEats</span>
            </div>
            
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold tracking-tight">Create your account</h2>
                <p class="mt-1 text-sm text-muted-foreground">Join StudEats and start planning balanced meals.</p>
            </div>

                    <!-- Registration Form -->
                <form action="<?php echo e(route('register')); ?>" method="POST" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Basic Information Section -->
                    <div class="space-y-4">
                        <!-- Name and Email Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="name" class="text-sm font-medium">Full Name</label>
                                <input id="name" name="name" type="text" autocomplete="name" required value="<?php echo e(old('name')); ?>" placeholder="Jane Doe"
                                       class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-destructive <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="space-y-1">
                                <label for="email" class="text-sm font-medium">Email Address</label>
                                <input id="email" name="email" type="email" autocomplete="email" required value="<?php echo e(old('email')); ?>" placeholder="you@example.com"
                                       class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-destructive <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Password Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="password" class="text-sm font-medium">Password</label>
                                <div class="relative">
                                    <input id="password" name="password" type="password" autocomplete="new-password" required placeholder="••••••••"
                                           class="w-full rounded-md border border-input bg-background px-3 py-2 pr-10 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-destructive <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-foreground transition-colors" onclick="togglePasswordVisibility('password')">
                                        <svg id="password-eye-open" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="password-eye-closed" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="space-y-1">
                                <label for="password_confirmation" class="text-sm font-medium">Confirm Password</label>
                                <div class="relative">
                                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required placeholder="Repeat password"
                                           class="w-full rounded-md border border-input bg-background px-3 py-2 pr-10 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-foreground transition-colors" onclick="togglePasswordVisibility('password_confirmation')">
                                        <svg id="password_confirmation-eye-open" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="password_confirmation-eye-closed" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Age and Budget Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="age" class="text-sm font-medium">Age <span class="text-xs text-muted-foreground">(Optional)</span></label>
                                <input id="age" name="age" type="number" min="13" max="120" value="<?php echo e(old('age')); ?>" placeholder="18"
                                       class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-destructive <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="space-y-1">
                                <label for="daily_budget" class="text-sm font-medium">Daily Budget (₱)</label>
                                <input id="daily_budget" name="daily_budget" type="number" min="100" max="1000" step="10" value="<?php echo e(old('daily_budget')); ?>" placeholder="300"
                                       class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?php $__errorArgs = ['daily_budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-destructive <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <p class="text-xs text-muted-foreground">Recommended: ₱270–₱390</p>
                                <?php $__errorArgs = ['daily_budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Information Section -->
                    <div class="space-y-3 pt-4 border-t border-border">
                        <h3 class="text-sm font-semibold flex items-center gap-2">
                        </h3>
                        
                        <!-- Gender and Activity Level Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="gender" class="text-sm font-medium">Gender</label>
                                <select id="gender" name="gender" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-destructive <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php echo e(old('gender') === 'male' ? 'selected' : ''); ?>>Male</option>
                                    <option value="female" <?php echo e(old('gender') === 'female' ? 'selected' : ''); ?>>Female</option>
                                    <option value="other" <?php echo e(old('gender') === 'other' ? 'selected' : ''); ?>>Other</option>
                                </select>
                                <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="space-y-1">
                                <label for="activity_level" class="text-sm font-medium">Activity Level</label>
                                <select id="activity_level" name="activity_level" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?php $__errorArgs = ['activity_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-destructive <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Select Activity Level</option>
                                    <option value="sedentary" <?php echo e(old('activity_level') === 'sedentary' ? 'selected' : ''); ?>>Sedentary</option>
                                    <option value="lightly_active" <?php echo e(old('activity_level') === 'lightly_active' ? 'selected' : ''); ?>>Lightly Active</option>
                                    <option value="moderately_active" <?php echo e(old('activity_level') === 'moderately_active' ? 'selected' : ''); ?>>Moderately Active</option>
                                    <option value="very_active" <?php echo e(old('activity_level') === 'very_active' ? 'selected' : ''); ?>>Very Active</option>
                                </select>
                                <?php $__errorArgs = ['activity_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <!-- Height and Weight Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label for="height" class="text-sm font-medium">Height</label>
                                <div class="flex gap-2">
                                    <input id="height" name="height" type="number" min="100" max="250" step="0.1" value="<?php echo e(old('height')); ?>" placeholder="170"
                                           class="flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?php $__errorArgs = ['height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-destructive <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <select name="height_unit" class="w-16 rounded-md border border-input bg-background px-2 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        <option value="cm" <?php echo e(old('height_unit', 'cm') === 'cm' ? 'selected' : ''); ?>>cm</option>
                                        <option value="ft" <?php echo e(old('height_unit') === 'ft' ? 'selected' : ''); ?>>ft</option>
                                    </select>
                                </div>
                                <p class="text-xs text-muted-foreground">Better nutrition recommendations</p>
                                <?php $__errorArgs = ['height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="space-y-1">
                                <label for="weight" class="text-sm font-medium">Weight</label>
                                <div class="flex gap-2">
                                    <input id="weight" name="weight" type="number" min="30" max="300" step="0.1" value="<?php echo e(old('weight')); ?>" placeholder="70"
                                           class="flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 <?php $__errorArgs = ['weight'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-destructive <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <select name="weight_unit" class="w-16 rounded-md border border-input bg-background px-2 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        <option value="kg" <?php echo e(old('weight_unit', 'kg') === 'kg' ? 'selected' : ''); ?>>kg</option>
                                        <option value="lbs" <?php echo e(old('weight_unit') === 'lbs' ? 'selected' : ''); ?>>lbs</option>
                                    </select>
                                </div>
                                <p class="text-xs text-muted-foreground">Personalized meal planning</p>
                                <?php $__errorArgs = ['weight'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dietary Preferences Section -->
                    <div class="space-y-3 pt-4 border-t border-border">
                        <h3 class="text-sm font-semibold">Dietary Preferences <span class="text-xs font-normal text-muted-foreground">(Optional)</span></h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 text-sm">
                            <?php ($prefs = old('dietary_preferences', [])); ?>
                            <?php $__currentLoopData = ['vegetarian'=>'Vegetarian','vegan'=>'Vegan','pescatarian'=>'Pescatarian','gluten_free'=>'Gluten Free','dairy_free'=>'Dairy Free','low_carb'=>'Low Carb','high_protein'=>'High Protein']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-2 p-2 border border-border rounded-md hover:bg-accent transition-colors cursor-pointer group text-xs">
                                    <input type="checkbox" name="dietary_preferences[]" value="<?php echo e($val); ?>" class="h-3 w-3 rounded border border-input text-primary focus:ring-1 focus:ring-ring" <?php echo e(in_array($val, $prefs) ? 'checked' : ''); ?>>
                                    <span class="group-hover:text-accent-foreground transition-colors leading-tight"><?php echo e($label); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['dietary_preferences'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-md bg-primary px-4 py-2.5 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 transform hover:scale-[1.01]">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Create Account
                    </button>

                    <!-- Sign In Link -->
                    <div class="text-center text-sm text-muted-foreground">
                        Already have an account?
                        <a href="<?php echo e(route('login')); ?>" class="font-medium text-primary hover:underline">Sign in</a>
                    </div>
                </form>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeOpen = document.getElementById(fieldId + '-eye-open');
    const eyeClosed = document.getElementById(fieldId + '-eye-closed');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        passwordField.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\StudEats\resources\views/auth/register.blade.php ENDPATH**/ ?>