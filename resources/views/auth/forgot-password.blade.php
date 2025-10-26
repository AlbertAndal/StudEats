@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
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
                <h1 class="text-4xl font-bold leading-tight">Reset your password and get back to planning.</h1>
                <p class="mt-4 max-w-md text-white/90 text-sm">Don't worry, we've all been there. Enter your email address and we'll send you a secure link to reset your password and continue your nutrition journey.</p>
            </div>
            <div class="mt-10 flex items-center gap-8 text-xs uppercase tracking-wide text-white/60">
                <span>Secure Recovery</span>
                <span class="h-1 w-1 rounded-full bg-white/40"></span>
                <span>Quick Access</span>
                <span class="h-1 w-1 rounded-full bg-white/40"></span>
                <span>Always Protected</span>
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
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-muted mb-4">
                    <div class="icon-rotate-ccw-key"></div>
                </div>
                <h2 class="text-3xl font-bold tracking-tight">Forgot your password?</h2>
                <p class="mt-2 text-sm text-muted-foreground">No problem! Enter your email address and we'll send you a password reset link.</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mt-6 rounded-md border border-green-200 bg-green-50 p-4">
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

            <form action="{{ route('password.email') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium">Email Address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 @error('email') ring-2 ring-destructive @enderror" 
                           placeholder="you@example.com">
                    @error('email')<p class="text-xs text-destructive mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Send Password Reset Link
                </button>

                <div class="text-center text-sm text-muted-foreground">
                    Remember your password?
                    <a href="{{ route('login') }}" class="font-medium text-primary hover:underline">Back to login</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection