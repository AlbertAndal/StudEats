## Quick SMTP Setup Guide

### Option 1: Mailtrap (Recommended for Development)

1. **Sign up at [mailtrap.io](https://mailtrap.io)** (free account)
2. **Create an inbox** in your dashboard
3. **Get your credentials** from the SMTP settings
4. **Run the configuration command:**

```bash
php artisan setup:smtp --test
```

Select "Mailtrap" and enter your credentials when prompted.

### Option 2: Gmail SMTP (If you have Gmail)

1. **Enable 2-Factor Authentication** on your Gmail account
2. **Generate App Password:**
   - Go to Google Account → Security → 2-Step Verification → App passwords
   - Select "Mail" and generate 16-character password
3. **Run the configuration command:**

```bash
php artisan setup:smtp --test
```

Select "Gmail SMTP" and enter your actual Gmail and App Password.

### Manual Configuration (Alternative)

If you prefer to edit .env manually, replace the mail section with:

#### For Mailtrap:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=test@studeats.dev
MAIL_FROM_NAME="StudEats"
```

#### For Gmail:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-actual-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-actual-email@gmail.com
MAIL_FROM_NAME="StudEats"
```

### After Configuration:

1. **Clear config cache:**
```bash
php artisan config:clear
```

2. **Test the setup:**
```bash
php artisan test:email-config your-email@example.com
```

3. **Try registration** - OTP codes will now be delivered via email!

### Verification:

The debug mode will still show OTP codes on the verification page until you set `APP_ENV=production` in your .env file.