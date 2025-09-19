# OTP Email Verification API Documentation

This document describes how to use the OTP-based email verification system implemented in StudEats.

## Overview

The system provides secure email verification using One-Time Passwords (OTPs) with a 30-second expiration period. It includes rate limiting to prevent abuse and comprehensive logging for security monitoring.

## Features

- **30-second OTP expiration** for enhanced security
- **Rate limiting**: Maximum 5 attempts per email per hour
- **Secure email delivery** via SMTP (Gmail)
- **Comprehensive logging** for security monitoring
- **Automatic cleanup** of expired OTPs
- **Queue support** for email delivery

## API Endpoints

### 1. Send OTP

Send a verification code to an email address.

**Endpoint:** `POST /email-verification/send-otp`

**Request Body:**
```json
{
    "email": "user@example.com"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Verification code sent to your email address.",
    "expires_in_seconds": 30,
    "remaining_attempts": 4
}
```

**Rate Limited Response (429):**
```json
{
    "success": false,
    "message": "Too many OTP requests. Please wait before requesting again.",
    "retry_after": 3540
}
```

**cURL Example:**
```bash
curl -X POST http://localhost/email-verification/send-otp \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com"}'
```

### 2. Verify OTP

Verify a received OTP code.

**Endpoint:** `POST /email-verification/verify-otp`

**Request Body:**
```json
{
    "email": "user@example.com",
    "otp_code": "123456"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Email verified successfully.",
    "user_exists": true
}
```

**Invalid OTP Response (422):**
```json
{
    "success": false,
    "message": "Invalid or expired verification code."
}
```

**cURL Example:**
```bash
curl -X POST http://localhost/email-verification/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "otp_code": "123456"}'
```

### 3. Resend OTP

Request a new OTP code for the same email.

**Endpoint:** `POST /email-verification/resend-otp`

**Request Body:**
```json
{
    "email": "user@example.com"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "New verification code sent to your email address.",
    "expires_in_seconds": 30,
    "remaining_attempts": 3
}
```

**cURL Example:**
```bash
curl -X POST http://localhost/email-verification/resend-otp \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com"}'
```

### 4. Get OTP Status

Check the current status of OTP requests for an email (for debugging/admin).

**Endpoint:** `GET /email-verification/status?email=user@example.com`

**Success Response (200):**
```json
{
    "email": "user@example.com",
    "user_exists": true,
    "email_verified": false,
    "is_rate_limited": false,
    "remaining_attempts": 5,
    "rate_limit_reset_in": 0
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost/email-verification/status?email=user@example.com"
```

## SMTP Configuration

To use the email verification system, configure the following environment variables in your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=xyrj oymk wrha pppg
MAIL_FROM_ADDRESS="noreply@studeats.com"
MAIL_FROM_NAME="StudEats"
```

**Note:** The provided password (`xyrj oymk wrha pppg`) is a Gmail App Password. Ensure 2FA is enabled on the Gmail account.

## Security Features

### Rate Limiting
- Maximum 5 OTP requests per email per hour
- Rate limit keys are hashed for privacy
- Automatic reset after 1 hour

### OTP Security
- 6-digit cryptographically secure random codes
- 30-second expiration time
- One-time use (marked as used after verification)
- Automatic invalidation of previous codes

### Logging
- All OTP generation and verification attempts are logged
- IP addresses and user agents are recorded
- Failed verification attempts are tracked

## Console Commands

### Cleanup Expired OTPs

Remove expired OTP records from the database:

```bash
php artisan otp:cleanup --force
```

You can set up a scheduled task to run this command regularly:

```php
// In app/Console/Kernel.php or routes/console.php
Schedule::command('otp:cleanup --force')->everyMinute();
```

## Database Schema

The system uses the `email_verification_otps` table:

```sql
CREATE TABLE email_verification_otps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    otp_code VARCHAR(6) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    is_used BOOLEAN DEFAULT FALSE,
    ip_address VARCHAR(255) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_email_otp_used (email, otp_code, is_used),
    INDEX idx_expires_at (expires_at),
    INDEX idx_email (email)
);
```

## Integration Examples

### Frontend JavaScript

```javascript
// Send OTP
async function sendOtp(email) {
    const response = await fetch('/email-verification/send-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ email })
    });
    
    return await response.json();
}

// Verify OTP
async function verifyOtp(email, otpCode) {
    const response = await fetch('/email-verification/verify-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            email, 
            otp_code: otpCode 
        })
    });
    
    return await response.json();
}
```

### Registration Flow Example

```javascript
// 1. User enters email during registration
const email = 'user@example.com';

// 2. Send OTP
const otpResult = await sendOtp(email);
if (otpResult.success) {
    // Show OTP input form
    showOtpForm();
    
    // Start countdown timer (30 seconds)
    startCountdown(30);
}

// 3. User enters OTP code
const otpCode = '123456';

// 4. Verify OTP
const verifyResult = await verifyOtp(email, otpCode);
if (verifyResult.success) {
    // Proceed with registration
    completeRegistration();
} else {
    // Show error message
    showError(verifyResult.message);
}
```

## Error Handling

All endpoints return structured error responses:

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

Common error scenarios:
- Invalid email format
- Rate limit exceeded
- OTP expired or invalid
- SMTP configuration issues
- Network connectivity problems

## Testing

Test the OTP generation and verification using Tinker:

```php
// Generate OTP
$otpService = app(\App\Services\OtpService::class);
$otp = $otpService->generateOtp('test@example.com');
echo "Generated OTP: " . $otp->otp_code;

// Verify OTP
$isValid = $otpService->verifyOtp('test@example.com', $otp->otp_code);
echo "Valid: " . ($isValid ? 'Yes' : 'No');
```