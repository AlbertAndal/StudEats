# Gmail SMTP Configuration for Email Verification

## Current Issue: Emails Not Being Received

### Root Cause Analysis

The OTP emails are being queued but may not be sent due to Gmail SMTP authentication issues.

---

## Gmail App Password Setup (REQUIRED)

Google requires **App Passwords** for applications to send emails via SMTP. Regular Gmail passwords won't work.

### Step 1: Enable 2-Factor Authentication

1. Go to: https://myaccount.google.com/security
2. Click **"2-Step Verification"**
3. Follow prompts to enable 2FA (required for App Passwords)

### Step 2: Generate App Password

1. Go to: https://myaccount.google.com/apppasswords
2. Select app: **"Mail"**
3. Select device: **"Other (Custom name)"**
4. Enter: **"StudEats Production"**
5. Click **"Generate"**
6. Copy the 16-character password (format: `xxxx xxxx xxxx xxxx`)

### Step 3: Update Render Environment Variables

**In Render Dashboard:**

1. Go to your web service: `studeats`
2. Click **"Environment"** tab
3. Update `MAIL_PASSWORD` with the **App Password** (remove spaces)
4. Format: `xxxxxxxxxxxxxxxx` (16 characters, no spaces)

**Also update for queue worker:**

1. Go to service: `studeats-queue-worker`
2. Click **"Environment"** tab  
3. Update `MAIL_PASSWORD` with same App Password

### Step 4: Redeploy Both Services

1. After updating environment variables
2. Go to **"Manual Deploy"** tab
3. Click **"Deploy latest commit"** for BOTH services

---

## Environment Variables Checklist

Make sure BOTH services (web + worker) have these set:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=johnalbertandal5@gmail.com
MAIL_PASSWORD=your-16-char-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=johnalbertandal5@gmail.com
MAIL_FROM_NAME=StudEats
```

---

## Common Issues & Solutions

### Issue 1: "Authentication failed"
**Solution:** 
- Generate new App Password
- Make sure 2FA is enabled on Gmail account
- Remove all spaces from App Password

### Issue 2: "Connection timeout"
**Solution:**
- Verify MAIL_PORT=587 (not 465)
- Verify MAIL_ENCRYPTION=tls (not ssl)
- Check Render firewall allows outbound SMTP

### Issue 3: Queue worker not processing emails
**Solution:**
- Check worker service is running in Render dashboard
- View worker logs for error messages
- Restart worker service if needed

### Issue 4: Emails in spam folder
**Solution:**
- Check recipient's spam/junk folder
- Add `noreply@render.com` to contacts
- Gmail may flag new senders as spam initially

---

## Testing Email Configuration

### Method 1: Check Queue Worker Logs

After user registration, check worker logs for:

```
Processing: Illuminate\Mail\SendQueuedMailable
Processed:  Illuminate\Mail\SendQueuedMailable
```

### Method 2: Manual Test via Render Shell

1. Go to web service → Shell tab
2. Run:
```bash
php artisan tinker
Mail::raw('Test email from StudEats', function($msg) {
    $msg->to('your-email@gmail.com')->subject('Test');
});
exit
```

### Method 3: Check Application Logs

Look for these log entries:
```
Verification email queued successfully
```

If you see errors instead:
```
Failed to queue verification email
```

Then check the error details in logs.

---

## Alternative: Use Gmail OAuth2

If App Passwords don't work, you can use OAuth2:

1. Create Google Cloud Project
2. Enable Gmail API
3. Create OAuth2 credentials
4. Use package: `league/oauth2-google`

(More complex, not recommended for MVP)

---

## Fallback: Use Different SMTP Provider

If Gmail continues to have issues, consider:

### SendGrid (Recommended for production)
- Free tier: 100 emails/day
- Better deliverability
- Easier configuration
- env: `MAIL_MAILER=smtp, MAIL_HOST=smtp.sendgrid.net, MAIL_PORT=587`

### Mailtrap (For testing only)
- Free tier: unlimited test emails
- Emails don't actually send (testing inbox)
- Perfect for development

### Amazon SES
- Very reliable
- Pay per email (~$0.10 per 1000 emails)
- Requires AWS account

---

## Current Configuration Status

### ✅ Confirmed Working:
- Queue system operational
- Worker service running
- Database connected
- OTP generation successful

### ⚠️ Needs Verification:
- Gmail SMTP authentication (likely needs App Password)
- Email delivery to inbox
- Spam filter status

### 🔧 Action Required:
1. Generate Gmail App Password
2. Update MAIL_PASSWORD in both services
3. Redeploy services
4. Test registration flow

---

## Debugging Steps

### 1. Check if emails are being queued:

**Web service logs should show:**
```
Verification email queued successfully
```

### 2. Check if worker is processing jobs:

**Worker logs should show:**
```
Processing: Illuminate\Mail\SendQueuedMailable
```

### 3. Check for SMTP errors:

**Worker logs errors to look for:**
- `Authentication failed` → Wrong password
- `Connection timeout` → Wrong port/host
- `TLS negotiation failed` → Wrong encryption type

### 4. Check database jobs table:

```sql
SELECT * FROM jobs ORDER BY id DESC LIMIT 10;
```

Should show queued email jobs.

---

## Quick Fix Summary

**For immediate fix:**

1. **Generate App Password:**
   - https://myaccount.google.com/apppasswords
   - Select "Mail" + "Other"
   - Copy 16-character password

2. **Update Environment Variables:**
   - Render Dashboard → Service → Environment
   - Update `MAIL_PASSWORD` = App Password (no spaces)
   - Do this for BOTH web + worker services

3. **Redeploy:**
   - Manual Deploy → Deploy latest commit
   - Wait 2-3 minutes for deployment

4. **Test:**
   - Register new account
   - Check worker logs
   - Check email inbox (and spam folder!)

---

**Updated:** November 3, 2025  
**Priority:** CRITICAL - Email verification not working  
**Estimated Fix Time:** 5-10 minutes (after generating App Password)
