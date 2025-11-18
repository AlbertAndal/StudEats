# Laravel Cloud 500 Error - Troubleshooting Guide

## 🔴 Problem Identified

Your StudEats application is showing a **500 Server Error** on Laravel Cloud deployment at https://studeats.laravel.cloud/

## Root Cause Analysis

### Primary Issue: Database Connection Misconfiguration

**Location**: `config/database.php` line 21
```php
'default' => env('DB_CONNECTION', 'pgsql'),
```

**Problem**: The default database connection is set to PostgreSQL (`pgsql`), but:
1. Laravel Cloud may be provisioning MySQL
2. Environment variable `DB_CONNECTION` is not set in production
3. PostgreSQL credentials may not be configured

**Result**: Application cannot connect to database → Session middleware fails → 500 error

### Secondary Issues

1. **Missing Environment Variables** - Critical production configs not set
2. **Session Driver** - Requires working database connection
3. **Debug Mode** - Disabled in production, hiding actual error messages
4. **Cache/Config** - May be using stale cached configurations

---

## 🚀 Solution Steps

### Step 1: Fix Database Connection Configuration

**Option A: Use MySQL (Recommended for Laravel Cloud)**

1. **Update `config/database.php`**:
   ```php
   // Line 21 - Change from 'pgsql' to 'mysql'
   'default' => env('DB_CONNECTION', 'mysql'),
   ```

2. **Set environment variables in Laravel Cloud Dashboard**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=<provided-by-laravel-cloud>
   DB_PORT=3306
   DB_DATABASE=<your-database-name>
   DB_USERNAME=<provided-by-laravel-cloud>
   DB_PASSWORD=<provided-by-laravel-cloud>
   ```

**Option B: Use PostgreSQL Properly**

If Laravel Cloud provides PostgreSQL:
1. Keep `'default' => env('DB_CONNECTION', 'pgsql')`
2. Set in Laravel Cloud Dashboard:
   ```env
   DB_CONNECTION=pgsql
   DATABASE_URL=<postgresql-connection-url>
   ```

### Step 2: Configure Essential Environment Variables

In your **Laravel Cloud Dashboard** → **Environment Variables**, add:

```env
# Application
APP_NAME=StudEats
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:cVdpd4/yHnZtTDWQB+yi351zUDmvNHf/j5pPEgTM4a4=
APP_URL=https://studeats.laravel.cloud

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=1440
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail Configuration (Gmail SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=johnalbertandal5@gmail.com
MAIL_PASSWORD=nharujmmwoawzwgp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=johnalbertandal5@gmail.com
MAIL_FROM_NAME=StudEats

# Nutrition API
NUTRITION_API_URL=https://api.nal.usda.gov/fdc/v1
NUTRITION_API_KEY=your_api_key_here

# Vite
VITE_APP_NAME=StudEats
```

⚠️ **Security Note**: Generate a new `APP_KEY` for production using `php artisan key:generate`

### Step 3: Update Deployment Script

Ensure `deploy-laravel-cloud.sh` has proper error handling:

```bash
#!/bin/bash
set -e  # Exit on error

echo "=== Laravel Cloud Deployment ==="

# Clear cached configs FIRST
echo "Clearing cached configurations..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Seed PDRI reference data
echo "Seeding PDRI reference data..."
php artisan db:seed --class=PdriReferenceSeeder --force || echo "⚠️ PDRI seeding skipped"

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force || echo "✓ Storage link exists"

# Setup admin account
echo "Setting up admin account..."
php artisan db:seed --class=AdminSeeder --force || echo "✓ Admin exists"

# Optimize for production
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify database connection
echo "Verifying database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected!';"

echo "=== Deployment Complete ==="
```

### Step 4: Temporary Debug Mode (For Diagnosis Only)

If you need to see the actual error:

1. **Temporarily enable debug**:
   ```env
   APP_DEBUG=true
   APP_ENV=local
   ```

2. **Access the site** - You'll see the detailed error message

3. **IMMEDIATELY disable after diagnosis**:
   ```env
   APP_DEBUG=false
   APP_ENV=production
   ```

⚠️ **CRITICAL**: Never leave `APP_DEBUG=true` in production - it exposes sensitive information!

---

## 🔍 Verification Steps

After making changes:

### 1. Check Database Connection
```bash
# In Laravel Cloud terminal/SSH
php artisan tinker
>>> DB::connection()->getPdo();
>>> DB::select('SELECT 1');
```

### 2. Verify Migrations
```bash
php artisan migrate:status
```

### 3. Test Session Table
```bash
php artisan tinker
>>> DB::table('sessions')->count();
```

### 4. Check Application Health
```bash
curl https://studeats.laravel.cloud/up
# Should return 200 OK
```

---

## 🐛 Common Laravel Cloud Issues

### Issue: "No application encryption key has been specified"
**Solution**: Set `APP_KEY` in environment variables
```bash
php artisan key:generate --show
# Copy the output to Laravel Cloud environment variables
```

### Issue: Database Connection Refused
**Solutions**:
1. Verify database credentials in Laravel Cloud dashboard
2. Ensure database is provisioned and running
3. Check `DB_CONNECTION` matches your database type
4. Use `DATABASE_URL` if provided by Laravel Cloud

### Issue: Session Store Not Configured
**Solutions**:
1. Ensure `sessions` migration has run
2. Verify database connection works
3. Check `SESSION_DRIVER=database` is set
4. Consider using `SESSION_DRIVER=cookie` temporarily

### Issue: Route Not Found / 404 on All Pages
**Solution**: Clear route cache
```bash
php artisan route:clear
php artisan route:cache
```

---

## 📊 Monitoring & Logs

### Access Laravel Cloud Logs
1. Go to Laravel Cloud Dashboard
2. Navigate to your application → Logs
3. Check for:
   - Database connection errors
   - Migration failures
   - Authentication errors
   - File permission issues

### Key Error Patterns to Look For

**Database Connection**:
```
SQLSTATE[HY000] [2002] Connection refused
SQLSTATE[08006] Could not connect to server
```

**APP_KEY Missing**:
```
No application encryption key has been specified
RuntimeException: No application encryption key
```

**Session Issues**:
```
Session store not set on request
SQLSTATE error accessing sessions table
```

---

## 🎯 Quick Fix Checklist

- [ ] **Database config**: Changed default from `pgsql` to `mysql` in `config/database.php`
- [ ] **Environment vars**: Set `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- [ ] **APP_KEY**: Generated and configured in Laravel Cloud
- [ ] **APP_ENV**: Set to `production`
- [ ] **APP_DEBUG**: Set to `false` (or `true` temporarily for diagnosis)
- [ ] **Migrations**: Run successfully via deployment script
- [ ] **Sessions table**: Exists and accessible
- [ ] **Storage link**: Created successfully
- [ ] **Config cache**: Cleared and regenerated
- [ ] **Database connection**: Verified via tinker

---

## 🔧 Updated Configuration Files

### 1. Update `config/database.php`

**Change Line 21**:
```php
// OLD (causing the issue)
'default' => env('DB_CONNECTION', 'pgsql'),

// NEW (recommended fix)
'default' => env('DB_CONNECTION', 'mysql'),
```

### 2. Update `.env.example` for Future Deployments

Add Laravel Cloud specific section:
```env
# Laravel Cloud Production Settings
# Copy these to Laravel Cloud environment variables
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
# DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD provided by Laravel Cloud
```

---

## 📞 Support Resources

### Laravel Cloud Documentation
- Environment Variables: https://cloud.laravel.com/docs/environment
- Database Configuration: https://cloud.laravel.com/docs/databases
- Deployment: https://cloud.laravel.com/docs/deployments

### If Issue Persists
1. **Check Laravel Cloud Status**: https://status.laravel.com
2. **Review Application Logs**: Laravel Cloud Dashboard → Logs
3. **Contact Support**: support@laravel.com with:
   - Application URL: https://studeats.laravel.cloud/
   - Error timestamp
   - Deployment logs
   - Environment configuration (redact sensitive data)

---

## 🎓 Understanding the Error

### Why Config Matters
Laravel Cloud deployment works differently from local development:

**Local Development**:
- `.env` file is read directly
- Errors show detailed stack traces
- Database connection failures are obvious

**Production (Laravel Cloud)**:
- Environment variables set in dashboard
- Configs are cached
- Debug mode disabled for security
- Generic 500 errors protect sensitive info

### The Chain of Failure
1. **Wrong DB default** → Laravel tries to connect to PostgreSQL
2. **No DB credentials** → Connection fails
3. **Session middleware** → Tries to read from database sessions table
4. **Database unavailable** → Middleware crashes
5. **Application crash** → 500 error shown to user

---

## ✅ Expected Outcome

After implementing these fixes:
1. ✅ Application loads successfully at https://studeats.laravel.cloud/
2. ✅ Database connection established
3. ✅ Sessions working properly
4. ✅ User registration/login functional
5. ✅ Admin dashboard accessible
6. ✅ Meal planning features operational

---

## 📝 Post-Deployment Verification

### Test Critical Flows
1. **Homepage**: https://studeats.laravel.cloud/
2. **Registration**: `/register`
3. **Login**: `/login`
4. **Admin Panel**: `/admin/login`
5. **Health Check**: `/up`

### Database Verification
```bash
# SSH into Laravel Cloud or use tinker
php artisan tinker
>>> User::count()
>>> Meal::count()
>>> Recipe::count()
```

---

## 🔐 Security Reminders

Before going live:
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Unique `APP_KEY` for production (different from local)
- [ ] Strong database credentials
- [ ] HTTPS enforced
- [ ] Mail credentials secured
- [ ] API keys rotated if needed
- [ ] Remove any debug routes or test accounts

---

**Last Updated**: November 8, 2025  
**Version**: 1.0  
**Status**: Active Issue - Deployment Configuration Error
