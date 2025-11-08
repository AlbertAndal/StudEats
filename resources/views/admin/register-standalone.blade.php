@extends('layouts.guest')

@section('title', 'Admin Registration - StudEats')

@section('content')
<div class="min-h-screen grid md:grid-cols-2">
    <!-- Cover / Image Side -->
    <div class="hidden md:flex flex-col relative overflow-hidden bg-gradient-to-br from-blue-600 to-indigo-600 text-white p-10">
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=2153&q=80" 
                 alt="Admin management workspace" 
                 class="w-full h-full object-cover">
        </div>
        <div class="relative z-10 flex flex-col h-full">
            <div class="flex items-center gap-3 text-2xl font-semibold">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/10 backdrop-blur-sm">👤</span>
                StudEats Admin
            </div>
            <div class="mt-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-medium mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Admin Account Creation
                </div>
                <h1 class="text-4xl font-bold leading-tight">Create new administrator accounts.</h1>
                <p class="mt-4 max-w-md text-white/90 text-sm">Add new administrators to help manage the StudEats platform with appropriate access levels and security controls.</p>
            </div>
            <div class="mt-10 flex items-center gap-8 text-xs uppercase tracking-wide text-white/60">
                <span>Role Assignment</span>
                <span class="h-1 w-1 rounded-full bg-white/40"></span>
                <span>Security</span>
                <span class="h-1 w-1 rounded-full bg-white/40"></span>
                <span>Access Control</span>
            </div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="flex flex-col justify-center px-6 py-12 md:px-12 lg:px-20 bg-background">
        <div class="w-full max-w-md mx-auto">
            <div class="md:hidden mb-8 flex items-center gap-2 justify-center">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-2xl">👤</span>
                <span class="text-xl font-semibold">StudEats Admin</span>
            </div>
            
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold tracking-tight">Create Admin Account</h2>
                <p class="mt-1 text-sm text-muted-foreground">Register a new administrator for the platform</p>
            </div>

            <!-- CSRF Error Alert -->
            @include('components.csrf-error-alert')

            <!-- Alert Messages -->
            @if (session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Registration Form -->
            <form action="{{ route('admin.register.standalone.submit') }}" method="POST" class="space-y-5" id="admin-register-form">
                @csrf
                
                <div class="space-y-4">
                    <!-- Full Name Field -->
                    <div class="space-y-1">
                        <label for="name" class="text-sm font-medium">Full Name</label>
                        <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}" placeholder="Enter administrator's full name"
                               class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 @error('name') border-destructive @enderror">
                        @error('name')<p class="text-xs text-destructive mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-1">
                        <label for="email" class="text-sm font-medium">Admin Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="admin@example.com"
                               class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 @error('email') border-destructive @enderror">
                        @error('email')<p class="text-xs text-destructive mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="space-y-1">
                        <label for="role" class="text-sm font-medium">Admin Role</label>
                        <select id="role" name="role" required
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 @error('role') border-destructive @enderror">
                            <option value="">Select admin role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin - Standard privileges</option>
                            <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin - Full access</option>
                        </select>
                        @error('role')<p class="text-xs text-destructive mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-muted-foreground mt-1">Super admins can create other admin accounts</p>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1">
                        <label for="password" class="text-sm font-medium">Admin Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="new-password" required placeholder="••••••••"
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

                    <!-- Confirm Password Field -->
                    <div class="space-y-1">
                        <label for="password_confirmation" class="text-sm font-medium">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required placeholder="••••••••"
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
                        <p class="text-xs text-muted-foreground mt-1">Must match the password above</p>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Security Notice</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Admin accounts are automatically verified upon creation</li>
                                    <li>Strong passwords are required for security</li>
                                    <li>All admin account creation is logged for auditing</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow transition-colors hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 transform hover:scale-[1.01]">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Create Admin Account
                </button>

                <!-- Navigation Links -->
                <div class="text-center text-sm text-muted-foreground space-y-2">
                    <div>
                        Already have admin access?
                        <a href="{{ route('admin.login') }}" class="font-medium text-primary hover:underline">Sign in to admin panel</a>
                    </div>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="font-medium text-primary hover:underline">Back to admin dashboard</a>
                    </div>
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

// Password confirmation validation
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('password_confirmation');
    
    function validatePasswordMatch() {
        if (confirmField.value && passwordField.value !== confirmField.value) {
            confirmField.classList.add('border-destructive');
            confirmField.classList.remove('border-green-500');
        } else if (confirmField.value && passwordField.value === confirmField.value) {
            confirmField.classList.remove('border-destructive');
            confirmField.classList.add('border-green-500');
        } else {
            confirmField.classList.remove('border-destructive', 'border-green-500');
        }
    }
    
    if (passwordField && confirmField) {
        confirmField.addEventListener('input', validatePasswordMatch);
        passwordField.addEventListener('input', validatePasswordMatch);
    }
    
    // Form submission handling
    const form = document.getElementById('admin-register-form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating Admin Account...
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