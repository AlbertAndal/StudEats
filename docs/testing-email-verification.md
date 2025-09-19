## Email Verification Flow Testing

### Testing the Registration → OTP Verification Flow

1. **Registration Flow Test**:
   ```bash
   # Register a new user
   curl -X POST http://localhost/StudEats/register \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "name=Test User&email=test@example.com&password=password123&password_confirmation=password123"
   ```

2. **Expected Behavior**:
   - User account created but NOT logged in
   - Redirected to `/email-verification/verify` page
   - OTP sent to email address
   - Session contains `pending_verification_user_id`

3. **OTP Verification Test**:
   ```bash
   # Verify OTP (replace 123456 with actual OTP from email)
   curl -X POST http://localhost/StudEats/email-verification/verify-otp \
     -H "Content-Type: application/json" \
     -d '{"email":"test@example.com","otp_code":"123456"}'
   ```

4. **Expected Behavior After OTP Verification**:
   - Email marked as verified (`email_verified_at` timestamp set)
   - User logged in automatically
   - Redirected to dashboard
   - Welcome email sent

5. **Protected Route Access**:
   - Unverified users redirected to verification page
   - Verified users can access dashboard and other protected routes

### Manual Testing Steps

1. Go to `/register` and create a new account
2. Should redirect to `/email-verification/verify` 
3. Check email for 6-digit OTP code
4. Enter OTP on verification page
5. Should redirect to dashboard with welcome message

### Troubleshooting

- Check `storage/logs/laravel.log` for OTP generation logs
- Verify session contains `pending_verification_user_id`
- Check database `email_verification_otps` table for OTP records
- Ensure mail configuration is working