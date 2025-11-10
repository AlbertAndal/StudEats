@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a1 1 0 001.42 0L21 7M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verify your email address
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                We've sent a 6-digit verification code to<br>
                <span class="font-medium text-gray-900">{{ $email }}</span>
            </p>
        </div>

        <!-- Success Messages -->
        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if (session('error'))
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Verification Form -->
        <form class="mt-8 space-y-6" action="{{ route('email.verify.otp') }}" method="POST" id="otpForm">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="space-y-4">
                <!-- OTP Input -->
                <div>
                    <label for="otp_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Enter verification code
                    </label>
                    <input id="otp_code" 
                           name="otp_code" 
                           type="text" 
                           inputmode="numeric" 
                           pattern="[0-9]*" 
                           maxlength="6" 
                           required 
                           autocomplete="one-time-code"
                           class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 text-center text-lg font-mono tracking-widest @error('otp_code') border-red-300 @enderror"
                           placeholder="000000"
                           value="{{ old('otp_code') }}">
                    @error('otp_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Countdown Timer -->
                <div class="text-center">
                    <div id="countdown-container" class="text-sm text-gray-600">
                        <span id="countdown-text">Code expires in </span>
                        <span id="countdown-timer" class="font-mono font-medium text-green-600">5:00</span>
                    </div>
                    <div id="expired-message" class="text-sm text-red-600 hidden">
                        Your verification code has expired. Please request a new one.
                    </div>
                </div>

                <!-- Verify Button -->
                <div>
                    <button type="submit" 
                            id="verifyBtn"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-green-500 group-hover:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        Verify Email
                    </button>
                </div>
            </div>
        </form>

        <!-- Resend OTP Section -->
        <div class="text-center space-y-3">
            <p class="text-sm text-gray-600">
                Didn't receive the code?
            </p>
            
            <!-- Resend Button -->
            <form action="{{ route('email.verify.resend') }}" method="POST" id="resendForm">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" 
                        id="resendBtn"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-600 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span id="resendText">Resend code</span>
                    <span id="resendCountdown" class="hidden">Resend in <span id="resendTimer">60</span>s</span>
                </button>
            </form>

            <!-- Rate Limiting Info -->
            @if($remainingAttempts < $maxAttempts)
                <div class="text-xs text-gray-500">
                    {{ $remainingAttempts }} verification attempts remaining this hour
                </div>
            @endif

            @if($rateLimitResetTime > 0)
                <div class="text-xs text-red-600">
                    Too many attempts. Try again in {{ ceil($rateLimitResetTime / 60) }} minutes
                </div>
            @endif
        </div>

        <!-- Help Section -->
        <div class="text-center text-xs text-gray-500 space-y-2">
            <p>Having trouble? Check your spam folder or contact support.</p>
            <p>
                <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500">
                    ← Back to login
                </a>
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize countdown timers
    let expiryTime = 5 * 60; // 5 minutes in seconds
    let resendTime = 60; // 60 seconds
    
    const countdownTimer = document.getElementById('countdown-timer');
    const countdownContainer = document.getElementById('countdown-container');
    const expiredMessage = document.getElementById('expired-message');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const resendText = document.getElementById('resendText');
    const resendCountdown = document.getElementById('resendCountdown');
    const resendTimerSpan = document.getElementById('resendTimer');
    const otpInput = document.getElementById('otp_code');
    
    // Format time as MM:SS
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
    
    // Main countdown timer
    const mainInterval = setInterval(function() {
        expiryTime--;
        
        if (expiryTime <= 0) {
            clearInterval(mainInterval);
            countdownContainer.classList.add('hidden');
            expiredMessage.classList.remove('hidden');
            verifyBtn.disabled = true;
            verifyBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            countdownTimer.textContent = formatTime(expiryTime);
            
            // Change color when less than 1 minute remaining
            if (expiryTime <= 60) {
                countdownTimer.classList.remove('text-green-600');
                countdownTimer.classList.add('text-red-600');
            }
        }
    }, 1000);
    
    // Resend countdown timer
    const resendInterval = setInterval(function() {
        resendTime--;
        
        if (resendTime <= 0) {
            clearInterval(resendInterval);
            resendBtn.disabled = false;
            resendText.classList.remove('hidden');
            resendCountdown.classList.add('hidden');
        } else {
            resendTimerSpan.textContent = resendTime;
        }
    }, 1000);
    
    // Auto-format OTP input
    otpInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        
        // Auto-submit when 6 digits entered
        if (e.target.value.length === 6) {
            document.getElementById('otpForm').submit();
        }
    });
    
    // Prevent non-numeric input
    otpInput.addEventListener('keypress', function(e) {
        // Allow backspace, delete, tab, escape, enter
        if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
            // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true)) {
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    // Handle resend form submission
    document.getElementById('resendForm').addEventListener('submit', function(e) {
        // Reset timers after resend
        resendTime = 60;
        expiryTime = 5 * 60;
        
        // Reset UI
        resendBtn.disabled = true;
        resendText.classList.add('hidden');
        resendCountdown.classList.remove('hidden');
        
        countdownContainer.classList.remove('hidden');
        expiredMessage.classList.add('hidden');
        verifyBtn.disabled = false;
        verifyBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        
        countdownTimer.classList.remove('text-red-600');
        countdownTimer.classList.add('text-green-600');
        
        // Restart intervals
        clearInterval(mainInterval);
        clearInterval(resendInterval);
        
        setTimeout(() => {
            // Restart main countdown
            const newMainInterval = setInterval(function() {
                expiryTime--;
                
                if (expiryTime <= 0) {
                    clearInterval(newMainInterval);
                    countdownContainer.classList.add('hidden');
                    expiredMessage.classList.remove('hidden');
                    verifyBtn.disabled = true;
                    verifyBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    countdownTimer.textContent = formatTime(expiryTime);
                    
                    if (expiryTime <= 60) {
                        countdownTimer.classList.remove('text-green-600');
                        countdownTimer.classList.add('text-red-600');
                    }
                }
            }, 1000);
            
            // Restart resend countdown
            const newResendInterval = setInterval(function() {
                resendTime--;
                
                if (resendTime <= 0) {
                    clearInterval(newResendInterval);
                    resendBtn.disabled = false;
                    resendText.classList.remove('hidden');
                    resendCountdown.classList.add('hidden');
                } else {
                    resendTimerSpan.textContent = resendTime;
                }
            }, 1000);
        }, 1000); // Small delay to ensure form submission completes
    });
    
    // Focus on OTP input
    otpInput.focus();
});
</script>
@endsection