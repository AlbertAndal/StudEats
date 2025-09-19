@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-green-100">
                <span class="text-2xl">🍽️</span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verify Your Email
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                We've sent a verification code to<br>
                <span class="font-medium text-green-600">{{ $user->email }}</span>
            </p>
        </div>

        <!-- Dynamic Status Messages Container -->
        <div id="verification-status" class="hidden"></div>

        <!-- Development Mode OTP -->
        @if(config('app.env') === 'local' && isset($latestOtp) && $latestOtp)
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Development Mode</h3>
                        <div class="mt-1 text-sm text-yellow-700">
                            <p>Your OTP code is <strong>{{ $latestOtp->otp_code }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- OTP Form -->
        <form id="otp-form" class="mt-8 space-y-6">
            @csrf
            <input type="hidden" name="email" value="{{ $user->email }}">
            
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Enter Verification Code
                    </label>
                    <span class="text-xs text-gray-500">
                        Expires in <span id="otp-expiry-timer" class="font-semibold">5:00</span>
                    </span>
                </div>
                <div class="flex justify-center space-x-2">
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" 
                        class="otp-digit h-12 w-12 text-center text-xl font-semibold border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500" />
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" 
                        class="otp-digit h-12 w-12 text-center text-xl font-semibold border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500" />
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" 
                        class="otp-digit h-12 w-12 text-center text-xl font-semibold border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500" />
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" 
                        class="otp-digit h-12 w-12 text-center text-xl font-semibold border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500" />
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" 
                        class="otp-digit h-12 w-12 text-center text-xl font-semibold border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500" />
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" 
                        class="otp-digit h-12 w-12 text-center text-xl font-semibold border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500" />
                </div>
                <input type="hidden" id="otp_code" name="otp_code" required>
            </div>

            <div>
                <button type="submit" 
                        id="verify-btn"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg id="verify-btn-loading" class="hidden animate-spin h-5 w-5 text-green-500 group-hover:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg id="verify-btn-icon" class="h-5 w-5 text-green-500 group-hover:text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span id="verify-btn-text">Verify Email</span>
                </button>
            </div>
        </form>

        <!-- Help Actions -->
        <div class="flex items-center justify-between text-sm">
            <div>
                <span class="text-gray-600">Didn't receive code?</span>
                <button id="resend-btn" 
                        class="ml-1 font-medium text-green-600 hover:text-green-500 disabled:text-gray-400 disabled:cursor-not-allowed"
                        disabled>
                    <span id="resend-text">Resend code</span>
                    <span id="resend-countdown" class="text-gray-500">
                        (wait <span id="resend-timer">60</span>s)
                    </span>
                </button>
            </div>
            <div>
                <a href="#" id="help-toggle" class="font-medium text-green-600 hover:text-green-500">
                    Show help
                </a>
            </div>
        </div>

        <!-- Troubleshooting Panel -->
        <div id="troubleshooting-panel" class="hidden bg-gray-50 border border-gray-200 rounded px-4 py-3">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Troubleshooting Tips</h4>
            <ul class="space-y-1 text-sm text-gray-600">
                <li class="flex items-start">
                    <span class="inline-block w-1 h-1 bg-gray-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                    <span>Check your spam or junk folder for the verification email</span>
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-1 h-1 bg-gray-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                    <span>Ensure you're entering the most recent code received</span>
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-1 h-1 bg-gray-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                    <span>Verify that <strong>{{ $user->email }}</strong> is the correct email</span>
                </li>
            </ul>
            <div class="mt-3 text-xs text-gray-500">
                Still having issues? 
                <a href="{{ route('register') }}" class="text-green-600 hover:text-green-500">Start over with registration</a> or 
                <a href="mailto:support@studeats.local" class="text-green-600 hover:text-green-500">contact support</a>.
            </div>
        </div>


    </div>
</div><script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const otpForm = document.getElementById('otp-form');
    const otpDigits = document.querySelectorAll('.otp-digit');
    const otpCodeInput = document.getElementById('otp_code');
    const verifyBtn = document.getElementById('verify-btn');
    const verifyBtnText = document.getElementById('verify-btn-text');
    const verifyBtnLoading = document.getElementById('verify-btn-loading');
    const resendBtn = document.getElementById('resend-btn');
    const resendText = document.getElementById('resend-text');
    const resendCountdown = document.getElementById('resend-countdown');
    const resendTimer = document.getElementById('resend-timer');
    const helpToggle = document.getElementById('help-toggle');
    const troubleshootingPanel = document.getElementById('troubleshooting-panel');
    
    // Status elements
    const verificationStatus = document.getElementById('verification-status');
    const otpExpiryTimer = document.getElementById('otp-expiry-timer');
    
    // Intervals
    let resendCountdownInterval;
    let otpExpirationInterval;
    let redirectProgressInterval;
    
    // Set expiration time to 300 seconds (5 minutes) to match backend
    const OTP_EXPIRATION_SECONDS = 300;
    
    // Toggle help panel
    helpToggle.addEventListener('click', function(e) {
        e.preventDefault();
        troubleshootingPanel.classList.toggle('hidden');
        if (troubleshootingPanel.classList.contains('hidden')) {
            helpToggle.textContent = 'Show help';
        } else {
            helpToggle.textContent = 'Hide help';
        }
    });
    
    // Update timer display to show minutes:seconds format
    function updateTimerDisplay(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        const formattedTime = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
        otpExpiryTimer.textContent = formattedTime;
    }
    
    // Start OTP expiration countdown
    function startOtpExpirationCountdown(seconds = OTP_EXPIRATION_SECONDS) {
        if (otpExpirationInterval) {
            clearInterval(otpExpirationInterval);
        }
        
        let remaining = seconds;
        updateTimerDisplay(remaining);
        
        otpExpirationInterval = setInterval(() => {
            remaining--;
            updateTimerDisplay(remaining);
            
            if (remaining <= 10) {
                otpExpiryTimer.classList.add('text-red-600');
            } else {
                otpExpiryTimer.classList.remove('text-red-600');
            }
            
            if (remaining <= 0) {
                clearInterval(otpExpirationInterval);
                showStatus('error', 'Your verification code has expired. Please request a new one. You can still try entering a code if you received it recently.');
                // Don't disable inputs - user might have received code just before expiry
            }
        }, 1000);
    }
    
    // Start resend countdown
    function startResendCountdown(seconds = OTP_EXPIRATION_SECONDS) {
        if (resendCountdownInterval) {
            clearInterval(resendCountdownInterval);
        }
        
        resendBtn.disabled = true;
        resendCountdown.classList.remove('hidden');
        
        let remaining = seconds;
        resendTimer.textContent = remaining;
        
        resendCountdownInterval = setInterval(() => {
            remaining--;
            resendTimer.textContent = remaining;
            
            if (remaining <= 0) {
                clearInterval(resendCountdownInterval);
                resendBtn.disabled = false;
                resendCountdown.classList.add('hidden');
            }
        }, 1000);
    }
    
    // Show status
    function showStatus(type, message = null) {
        verificationStatus.classList.remove('hidden');
        
        let statusHtml = '';
        switch (type) {
            case 'processing':
                statusHtml = `
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 px-4 py-3 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-800">Verifying your code</h3>
                                <div class="mt-1 text-sm text-gray-600">
                                    <p>Please wait while we verify your email address...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                break;
            case 'success':
                statusHtml = `
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Email verified successfully!</h3>
                                <div class="mt-1 text-sm text-green-700">
                                    <p id="success-message">${message || 'Redirecting to your dashboard...'}</p>
                                    <div class="mt-2 w-full bg-green-200 rounded-full h-2">
                                        <div id="redirect-progress" class="bg-green-600 h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                break;
            case 'error':
                statusHtml = `
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Verification failed</h3>
                                <div class="mt-1 text-sm text-red-700">
                                    <p id="error-message">${message || 'Invalid or expired verification code. Please try again.'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                break;
            default:
                verificationStatus.classList.add('hidden');
                return;
        }
        
        verificationStatus.innerHTML = statusHtml;
    }
    
    // Start redirect progress animation
    function startRedirectProgress(duration = 3000) {
        const redirectProgress = document.getElementById('redirect-progress');
        if (!redirectProgress) return;
        
        let progress = 0;
        const interval = 30; // Update every 30ms
        const increment = interval / duration * 100;
        
        redirectProgress.style.width = '0%';
        
        redirectProgressInterval = setInterval(() => {
            progress += increment;
            redirectProgress.style.width = `${Math.min(progress, 100)}%`;
            
            if (progress >= 100) {
                clearInterval(redirectProgressInterval);
            }
        }, interval);
    }
    
    // Set loading state
    function setLoading(loading) {
        verifyBtn.disabled = loading;
        const btnIcon = document.getElementById('verify-btn-icon');
        const btnLoading = document.getElementById('verify-btn-loading');
        const btnText = document.getElementById('verify-btn-text');
        
        if (loading) {
            btnText.textContent = 'Verifying...';
            btnIcon.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            showStatus('processing');
        } else {
            btnText.textContent = 'Verify Email';
            btnIcon.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }
    }
    
    // Disable/enable OTP inputs
    function disableOtpInputs(disabled) {
        otpDigits.forEach(input => {
            input.disabled = disabled;
        });
        verifyBtn.disabled = disabled;
    }
    
    // Setup OTP input functionality
    otpDigits.forEach((input, index) => {
        // Auto-focus on first input
        if (index === 0) {
            setTimeout(() => {
                input.focus();
            }, 100);
        }
        
        // When typing
        input.addEventListener('input', function(e) {
            // Allow only numbers
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Move to next input when value is entered
            if (this.value && index < otpDigits.length - 1) {
                otpDigits[index + 1].focus();
            }
            
            // Update hidden input
            updateOtpValue();
            
            // Auto-submit when all digits filled
            if (isOtpComplete()) {
                otpForm.requestSubmit();
            }
        });
        
        // Handle backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                otpDigits[index - 1].focus();
                otpDigits[index - 1].value = '';
                updateOtpValue();
            }
        });
        
        // Handle paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
            
            if (pasteData.length > 0 && /^\d+$/.test(pasteData)) {
                // Clear all inputs first
                otpDigits.forEach(digit => digit.value = '');
                
                // Fill in digits
                for (let i = 0; i < Math.min(pasteData.length, otpDigits.length); i++) {
                    otpDigits[i].value = pasteData[i];
                }
                
                // Focus on appropriate field
                const focusIndex = Math.min(pasteData.length, otpDigits.length - 1);
                otpDigits[focusIndex].focus();
                
                // Update hidden input
                updateOtpValue();
                
                // Auto-submit if complete
                if (isOtpComplete()) {
                    otpForm.requestSubmit();
                }
            }
        });
    });
    
    // Check if OTP is complete
    function isOtpComplete() {
        for (let digit of otpDigits) {
            if (!digit.value) {
                return false;
            }
        }
        return true;
    }
    
    // Update hidden OTP value
    function updateOtpValue() {
        let otp = '';
        otpDigits.forEach(digit => {
            otp += digit.value || '';
        });
        otpCodeInput.value = otp;
    }
    
    // Handle form submission
    otpForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const otpCode = otpCodeInput.value.trim();
        if (otpCode.length !== 6) {
            showStatus('error', 'Please enter a 6-digit verification code.');
            return;
        }
        
        setLoading(true);
        
        try {
            const response = await fetch('{{ route("email-verification.verify-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    email: '{{ $user->email }}',
                    otp_code: otpCode
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Stop OTP expiration countdown
                if (otpExpirationInterval) {
                    clearInterval(otpExpirationInterval);
                }
                
                showStatus('success', 'Email verified successfully! Redirecting...');
                disableOtpInputs(true);
                startRedirectProgress(3000);
                
                // Complete verification and redirect
                setTimeout(async () => {
                    try {
                        const completeResponse = await fetch('{{ route("email-verification.complete") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        const completeData = await completeResponse.json();
                        
                        if (completeData.success && completeData.redirect) {
                            window.location.href = completeData.redirect;
                        } else {
                            window.location.href = '{{ route("dashboard") }}';
                        }
                    } catch (error) {
                        console.error('Completion error:', error);
                        window.location.href = '{{ route("dashboard") }}';
                    }
                }, 3000);
            } else {
                showStatus('error', data.message || 'Invalid verification code. Please try again.');
                otpDigits.forEach(digit => digit.value = '');
                otpCodeInput.value = '';
                otpDigits[0].focus();
            }
        } catch (error) {
            showStatus('error', 'Network error. Please check your connection and try again.');
            console.error('Verification error:', error);
        } finally {
            if (!otpCodeInput.value.startsWith('VALID_')) {
                setLoading(false);
            }
        }
    });
    
    // Handle resend button
    resendBtn.addEventListener('click', async function() {
        if (this.disabled) return;
        
        const originalText = resendText.textContent;
        resendText.textContent = 'Sending...';
        this.disabled = true;
        
        try {
            const response = await fetch('{{ route("email-verification.resend-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    email: '{{ $user->email }}'
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                verificationStatus.classList.add('hidden');
                // Clear current OTP
                otpDigits.forEach(digit => digit.value = '');
                otpCodeInput.value = '';
                disableOtpInputs(false);
                otpDigits[0].focus();
                
                // Use expiration time from server response
                const expirationTime = data.expires_in_seconds || OTP_EXPIRATION_SECONDS;
                
                // Restart countdowns
                startResendCountdown(expirationTime);
                startOtpExpirationCountdown(expirationTime);
                
                // Show simple notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded shadow-sm transform transition-transform duration-300 ease-out z-50';
                notification.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">New verification code sent!</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(notification);
                
                // Remove notification after 4 seconds
                setTimeout(() => {
                    notification.style.transform = 'translateX(120%)';
                    setTimeout(() => {
                        if (document.body.contains(notification)) {
                            document.body.removeChild(notification);
                        }
                    }, 300);
                }, 4000);
            } else {
                showStatus('error', data.message || 'Failed to resend code. Please try again.');
                this.disabled = false;
            }
        } catch (error) {
            showStatus('error', 'Network error. Please try again.');
            this.disabled = false;
            console.error('Resend error:', error);
        } finally {
            resendText.textContent = originalText;
        }
    });
    
    // Initialize
    startResendCountdown(OTP_EXPIRATION_SECONDS);
    startOtpExpirationCountdown(OTP_EXPIRATION_SECONDS);
});
</script>
@endsection