# 🔧 Fix Email Configuration - StudEats

## 🚨 **Current Issue**
Your emails are not being sent because the mail driver is set to `log` instead of an actual email service. This means OTP codes are being written to log files instead of being emailed to you.

## ✅ **Immediate Solution (Development)**
I've added a debug feature that will show the OTP code directly on the verification page when you're in development mode. This will allow you to continue testing while you set up proper email.

**The OTP code will now appear in a blue box on the verification page!**

## 🔧 **Permanent Email Solutions**

### Option 1: Gmail SMTP (Recommended for Development)

1. **Enable 2-Factor Authentication** on your Gmail account
2. **Generate an App Password:**
   - Go to Google Account settings → Security → 2-Step Verification → App passwords
   - Generate a password for "Mail"
3. **Update your `.env` file:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="StudEats"
```

### Option 2: Mailtrap (Best for Development Testing)

1. **Sign up at [mailtrap.io](https://mailtrap.io)** (free)
2. **Create an inbox** and get SMTP credentials
3. **Update your `.env` file:**

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@studeats.com
MAIL_FROM_NAME="StudEats"
```

### Option 3: Resend (Production Ready)

1. **Sign up at [resend.com](https://resend.com)** (free tier available)
2. **Get your API key**
3. **Update your `.env` file:**

```env
MAIL_MAILER=resend
RESEND_KEY=your-resend-api-key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="StudEats"
```

## 🔄 **Apply Configuration Changes**

After updating your `.env` file:

1. **Clear configuration cache:**
```bash
php artisan config:clear
```

2. **Restart your development server** if using `php artisan serve`

3. **Test the email system:**
```bash
php artisan tinker
```

Then in Tinker:
```php
Mail::raw('Test email', function ($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email from StudEats');
});
```

## 🧪 **Testing OTP System**

Once email is configured:

1. **Register a new account** or **use the resend button** on verification page
2. **Check your email** (or inbox if using Mailtrap)
3. **Enter the 6-digit code** on the verification page

## 🐛 **Troubleshooting**

### Emails Still Not Sending?
```bash
# Check current mail configuration
php artisan tinker
config('mail.default')
config('mail.mailers.smtp')
```

### Gmail Issues?
- Make sure 2FA is enabled
- Use App Password, not your regular password
- Check "Less secure app access" is enabled (if not using App Password)

### Still Having Issues?
- Check `storage/logs/laravel.log` for detailed error messages
- Verify your `.env` file has no extra spaces or quotes
- Make sure you restarted your server after changing `.env`

## 🔐 **Security Notes**

- **Never commit your `.env` file** to version control
- **Use App Passwords** for Gmail, not your main password
- **Use environment-specific configs** (different settings for development/production)

## 📧 **Current Status**

- ✅ OTP generation working
- ✅ OTP validation working
- ✅ Debug mode shows OTP on verification page
- ❌ Email delivery (needs configuration)

Once you configure email properly, remove the debug mode by changing your app environment from `local` to `production` in your `.env` file:

```env
APP_ENV=production
```

---

**Quick Fix:** For immediate testing, the OTP code now appears in a blue box on the verification page when in development mode!