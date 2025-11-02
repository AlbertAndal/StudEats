# Render Environment Variables Fix Guide

## Priority 1: Cache & Session (File-Based - No DB Dependency)

These should be set FIRST to avoid database dependency during application bootstrap:

```env
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

**Why?** Using `file` driver prevents Laravel from trying to access the database during config caching and early bootstrap phases. Once the app is running, you can switch to `database` if needed.

---

## Step-by-Step: Render Dashboard Configuration

### 1. Core Application Settings

Go to: **Render Dashboard > Your Web Service > Environment tab**

Add/Update these variables:

```env
# Application Basics
APP_NAME=StudEats
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:cVdpd4/yHnZtTDWQB+yi351zUDmvNHf/j5pPEgTM4a4=
APP_URL=https://your-render-app.onrender.com

# Logging
LOG_CHANNEL=errorlog
LOG_LEVEL=error
LOG_STACK=single
```

⚠️ **Important:** Replace `your-render-app.onrender.com` with your actual Render URL

---

### 2. Cache & Session (CRITICAL - Set These First!)

```env
# File-based cache (no DB required during startup)
CACHE_STORE=file
CACHE_PREFIX=studeats-cache-

# File-based sessions (no DB required)
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=

# Sync queue (no DB/worker required)
QUEUE_CONNECTION=sync
```

---

### 3. Database Configuration

Render provides a PostgreSQL database. Update to use internal connection:

```env
DB_CONNECTION=pgsql
DB_HOST=dpg-xxxxx-a (use internal host)
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**How to find these:**
- Go to Render Dashboard > PostgreSQL Database
- Copy the **Internal Database URL** or individual credentials
- Use **internal hostname** (faster, no external routing)

---

### 4. Mail Configuration (Gmail SMTP)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=johnalbertandal5@gmail.com
MAIL_PASSWORD=fovl fkzb btdz ezvk
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=johnalbertandal5@gmail.com
MAIL_FROM_NAME=StudEats
```

⚠️ **Security Note:** Consider using Render's environment variable encryption for `MAIL_PASSWORD`

---

### 5. API Keys (Optional)

```env
NUTRITION_API_URL=https://api.nal.usda.gov/fdc/v1
NUTRITION_API_KEY=Qs1e7LNogSHAQI1GBnHA9vuWx2OwzJceO4FHhEdP
```

---

### 6. Additional Laravel Settings

```env
# Locale
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

# Filesystem
FILESYSTEM_DISK=local

# Broadcast
BROADCAST_CONNECTION=log

# Security
BCRYPT_ROUNDS=12
```

---

## Migration Strategy: Database vs File Cache

### Current Issue: Database Dependency Loop

Your current config uses `database` for cache/session, which causes:
```
App boots → Needs config cache → Tries DB connection → DB not ready → CRASH
```

### Solution: File-Based During Bootstrap

**Phase 1: Deployment (Use File)**
```env
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

**Phase 2: After Stable (Optional - Switch to DB)**
Once deployed successfully, you can switch back if needed:
```env
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

---

## Verification Checklist

After setting environment variables in Render:

- [ ] Click **"Save Changes"** in Environment tab
- [ ] Trigger **Manual Deploy** or wait for auto-deploy
- [ ] Check Deploy Logs for:
  - ✅ "Application key set successfully"
  - ✅ "Config cached successfully"
  - ✅ "Route cached successfully"
  - ✅ "Starting Apache server"
- [ ] Visit your app URL - should load without errors
- [ ] Test email verification (OTP system)
- [ ] Test user registration/login

---

## Troubleshooting

### Issue: "500 Server Error" after deploy
**Solution:** Check Deploy Logs for specific error. Common causes:
- Missing `APP_KEY` → Set in environment variables
- Wrong database credentials → Double-check DB settings
- Cache issues → Render auto-runs `php artisan config:cache`

### Issue: "Database connection failed"
**Solution:**
1. Use **internal hostname** from Render DB dashboard
2. Verify port is `5432` (PostgreSQL default)
3. Check DB name/username/password match exactly

### Issue: Email verification not working
**Solution:**
1. Verify Gmail app password is correct (not regular password)
2. Check `MAIL_FROM_ADDRESS` matches `MAIL_USERNAME`
3. Ensure `QUEUE_CONNECTION=sync` for immediate email sending

### Issue: Sessions not persisting
**Solution:**
1. Ensure `SESSION_DRIVER=file`
2. Check `SESSION_DOMAIN` is empty or matches your domain
3. Verify cookies are being set (check browser DevTools)

---

## Quick Copy-Paste Template

Use this complete set in Render Environment tab:

```env
# Core App
APP_NAME=StudEats
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:cVdpd4/yHnZtTDWQB+yi351zUDmvNHf/j5pPEgTM4a4=
APP_URL=https://your-render-app.onrender.com

# Logging
LOG_CHANNEL=errorlog
LOG_LEVEL=error

# Cache & Session (FILE-BASED - NO DB DEPENDENCY)
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_LIFETIME=120

# Database (PostgreSQL from Render)
DB_CONNECTION=pgsql
DB_HOST=your-internal-host.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=your_db_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=johnalbertandal5@gmail.com
MAIL_PASSWORD=fovl fkzb btdz ezvk
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=johnalbertandal5@gmail.com
MAIL_FROM_NAME=StudEats

# API (Optional)
NUTRITION_API_URL=https://api.nal.usda.gov/fdc/v1
NUTRITION_API_KEY=Qs1e7LNogSHAQI1GBnHA9vuWx2OwzJceO4FHhEdP

# Filesystem
FILESYSTEM_DISK=local
BROADCAST_CONNECTION=log
```

---

## Next Steps After Environment Variables

1. **Save all environment variables** in Render
2. **Trigger manual deploy** from Render dashboard
3. **Monitor deploy logs** for success messages
4. **Test the application** at your Render URL
5. **Check email verification** with real email
6. **Review error logs** if issues persist

---

## Important Notes

🔹 **Always use `file` driver initially** - Avoid DB dependency loops
🔹 **Use internal DB hostname** - Faster and more reliable
🔹 **Keep `APP_DEBUG=false`** in production - Security best practice
🔹 **Use `sync` queue** - Immediate job processing without workers
🔹 **Verify Gmail app password** - Regular password won't work

---

## Support Resources

- **Render Docs:** https://render.com/docs/environment-variables
- **Laravel Config:** https://laravel.com/docs/12.x/configuration
- **StudEats Docs:** See `docs/` folder for feature guides
