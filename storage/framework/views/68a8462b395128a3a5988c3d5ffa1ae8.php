

<?php $__env->startSection('title', 'Login - StudEats'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen grid md:grid-cols-2">
    <!-- Cover / Image Side -->
    <div class="hidden md:flex flex-col relative overflow-hidden bg-gradient-to-br from-green-600 to-emerald-500 text-white p-10">
        <div class="absolute inset-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1350&q=60')] bg-cover bg-center"></div>
        <div class="relative z-10 flex flex-col h-full">
            <div class="flex items-center gap-3 text-2xl font-semibold">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/10 backdrop-blur-sm">🍽️</span>
                StudEats
            </div>
            <div class="mt-auto">
                <h1 class="text-4xl font-bold leading-tight">Welcome back to your nutrition journey.</h1>
                <p class="mt-4 max-w-md text-white/90 text-sm">Sign in to access your personalized meal plans, track your nutrition goals, and continue building healthier eating habits.</p>
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
            
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Welcome back</h2>
                <p class="mt-2 text-sm text-muted-foreground">Enter your credentials to access your account.</p>
            </div>

            <form action="<?php echo e(route('login')); ?>" method="POST" class="mt-8 space-y-6">
                <?php echo csrf_field(); ?>
                <div class="space-y-5">
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium">Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required value="<?php echo e(old('email')); ?>"
                               class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" 
                               placeholder="name@example.com">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-destructive mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="w-full rounded-md border border-input bg-background px-3 py-2 pr-10 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" 
                                   placeholder="Enter your password">
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3" onclick="togglePasswordVisibility('password')">
                                <svg id="password-eye-open" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="password-eye-closed" class="h-5 w-5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 select-none cursor-pointer">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border border-input text-primary focus-visible:ring-1 focus-visible:ring-ring" />
                        <span>Remember me</span>
                    </label>
                    <a href="<?php echo e(route('password.request')); ?>" class="font-medium text-primary hover:underline">Forgot password?</a>
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2">
                    Sign in to your account
                </button>

                <div class="text-center text-sm text-muted-foreground">
                    Don't have an account?
                    <a href="<?php echo e(route('register')); ?>" class="font-medium text-primary hover:underline">Create one</a>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\StudEats\resources\views/auth/login_new.blade.php ENDPATH**/ ?>