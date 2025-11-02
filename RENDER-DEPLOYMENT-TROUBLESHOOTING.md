# 🚨 Render Deployment Troubleshooting Guide - StudEats

## Common 500 Server Error Causes and Solutions

### 1. ⚠️ Critical Environment Variables Missing

**Check these required environment variables in your Render dashboard:**

```env
# CRITICAL - Application will fail without these
APP_KEY=base64:your_generated_key_here
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-service-name.onrender.com

# Database - Render provides DATABASE_URL automatically
DB_CONNECTION=pgsql

# Email Configuration (Required for OTP system)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME=StudEats

# Cache & Session (File-based for free tier)
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Logging
LOG_CHANNEL=errorlog
LOG_LEVEL=error
```

### 2. 🔍 How to Check Render Logs

1. Go to your Render dashboard: https://dashboard.render.com/project/prj-d3v9s5je5dus73a7tkl0
2. Click on your StudEats service
3. Go to "Logs" tab
4. Look for these common error patterns:

```bash
# APP_KEY Missing
"APP_KEY missing or invalid"

# Database Connection Issues
"SQLSTATE[08006] connection failed"
"No application encryption key has been specified"

# Permission Issues
"Permission denied"
"storage/logs/laravel.log could not be opened"

# Build Failures
"npm ERR!"
"composer install failed"
```

### 3. 🛠️ Common Fix Steps

#### Fix 1: Generate APP_KEY
If logs show APP_KEY issues:
1. In Render dashboard, add environment variable:
   - Key: `APP_KEY`
   - Value: Run locally: `php artisan key:generate --show`

#### Fix 2: Database Configuration
```env
# Use PostgreSQL for Render (not MySQL)
DB_CONNECTION=pgsql
# DATABASE_URL is automatically provided by Render
```

#### Fix 3: Storage Permissions
Your render-start.sh already handles this, but verify logs show:
```bash
✅ Setting permissions...
```

#### Fix 4: Email Configuration
For OTP system to work:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_app_password
```

### 4. 🧪 Local Production Testing

Test locally to replicate issues:

```bash
# Set production environment
cp .env .env.backup
cp .env.example .env

# Configure for production testing
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Test build process
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start production server
php artisan serve --env=production
```

### 5. 📋 Deployment Checklist

**Before Each Deploy:**

- [ ] All environment variables set in Render dashboard
- [ ] APP_KEY generated and set
- [ ] Database created and connected
- [ ] Email credentials configured
- [ ] Branch set to `main` in Render settings
- [ ] Build command: `./render-build.sh`
- [ ] Start command: `./render-start.sh`

**File Permissions:**
- [ ] `render-build.sh` executable: `chmod +x render-build.sh`
- [ ] `render-start.sh` executable: `chmod +x render-start.sh`

### 6. 🔧 Debug Commands

Add these to your Render environment for debugging:

```env
# Enable more detailed logging
LOG_LEVEL=debug
APP_DEBUG=true  # Only for debugging, remove after fixing
```

### 7. 🚨 Emergency Recovery Script

If deployment fails completely, you can use the emergency script:

```bash
# In Render shell (if available)
./render-emergency.sh
```

### 8. 📊 Health Check URLs

Test these URLs after deployment:

- `https://your-app.onrender.com/up` - Laravel health check
- `https://your-app.onrender.com/` - Homepage
- `https://your-app.onrender.com/login` - Auth system

### 9. 🐛 Specific Error Solutions

#### "Route [login] not defined"
- Check routes/web.php for auth routes
- Clear route cache: `php artisan route:clear`

#### "View not found"
- Check storage/framework/views permissions
- Run: `php artisan view:clear`

#### "Database connection failed"
- Verify DATABASE_URL environment variable
- Check DB_CONNECTION=pgsql (not mysql)

#### "Class not found"
- Run: `composer dump-autoload --optimize`
- Check for missing use statements

### 10. 📞 Next Steps

1. **Check Render logs first** - Most issues show clear error messages
2. **Verify environment variables** - 90% of 500 errors are missing config
3. **Test build process locally** - Replicate the production environment
4. **Contact support** - If logs don't show clear errors

---

## 🆘 Emergency Contact

If you're still experiencing issues after following this guide:

1. **Copy the exact error message** from Render logs
2. **Share your environment variables** (without sensitive values)
3. **Describe what changed** since the last working deployment

Remember: **Never share your actual APP_KEY, database passwords, or email credentials in troubleshooting requests.**