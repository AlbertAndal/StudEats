@extends('layouts.guest')

@section('title', 'Admin Login - StudEats')

@section('content')
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
                StudEats Admin
            </div>
            <div class="mt-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-medium mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Administrative Access
                </div>
                <h1 class="text-4xl font-bold leading-tight">Manage your StudEats platform.</h1>
                <p class="mt-4 max-w-md text-white/90 text-sm">Access the admin dashboard to manage users, monitor meal plans, and oversee platform operations with enhanced controls.</p>
            </div>
            <div class="mt-10 flex items-center gap-8 text-xs uppercase tracking-wide text-white/60">
                <span>User Management</span>
                <span class="h-1 w-1 rounded-full bg-white/40"></span>
                <span>Analytics</span>
                <span class="h-1 w-1 rounded-full bg-white/40"></span>
                <span>Content Control</span>
            </div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="flex flex-col justify-center px-6 py-12 md:px-12 lg:px-20 bg-background">
        <div class="w-full max-w-md mx-auto">
            <div class="md:hidden mb-8 flex items-center gap-2 justify-center">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-2xl">🍽️</span>
                <span class="text-xl font-semibold">StudEats Admin</span>
            </div>
            
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold tracking-tight">Admin Sign In</h2>
                <p class="mt-1 text-sm text-muted-foreground">Access the administrative dashboard</p>
            </div>

            <!-- Alert Message -->
            @if (session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5" data-persist id="admin-login-form">
                @csrf
                
                <div class="space-y-4">
                    <!-- Email Field -->
                    <div class="space-y-1">
                        <label for="email" class="text-sm font-medium">Admin Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="admin@studeats.com"
                               class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 @error('email') border-destructive @enderror">
                        @error('email')<p class="text-xs text-destructive mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1">
                        <label for="password" class="text-sm font-medium">Admin Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••"
                                   class="w-full rounded-md border border-input bg-background px-3 py-2 pr-10 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 @error('password') border-destructive @enderror">
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
                        @error('password')<p class="text-xs text-destructive mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 select-none cursor-pointer">
                        <input id="remember" name="remember" type="checkbox" class="h-3 w-3 rounded border border-input text-primary focus:ring-1 focus:ring-ring">
                        <span class="text-muted-foreground">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-md bg-green-600 px-4 py-2.5 text-sm font-medium text-white shadow transition-colors hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2 transform hover:scale-[1.01]">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Access Admin Dashboard
                </button>

                <!-- Back to Login Link -->
                <div class="text-center text-sm text-muted-foreground">
                    Not an admin?
                    <a href="{{ route('login') }}" class="font-medium text-primary hover:underline">Back to user login</a>
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

// Initialize CSRF manager and restore form data if needed
document.addEventListener('DOMContentLoaded', function() {
    // Restore form data if available
    if (window.csrfManager) {
        csrfManager.restoreFormData('admin-login-form');
    }
    
    // Add form submission handling
    const form = document.getElementById('admin-login-form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Authenticating...
            `;
        }
    });
});
</script>

<!-- Include CSRF Manager -->
<script src="{{ asset('js/csrf-manager.js') }}"></script>

<!-- Include Cache Manager -->
<script src="{{ asset('js/cache-manager.js') }}"></script>

@endsection
