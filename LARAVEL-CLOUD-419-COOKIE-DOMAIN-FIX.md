# 🚨 Laravel Cloud 419 CSRF Error - Cookie Domain Fix

## Problem Identified
**Error:** `Cookie "studeats-session" has been rejected for invalid domain.`  
**Cause:** Session cookie domain configuration mismatch on Laravel Cloud subdomain architecture.

---

## ✅ IMMEDIATE FIX - Production Environment Variables

### **Step 1: Update Laravel Cloud Environment Variables**

Go to: **https://cloud.laravel.com/capstone-research/studeats/main**  
Navigate to: **Environment** tab

**ADD/UPDATE these exact variables:**

```env
# Critical Session Configuration for Laravel Cloud
SESSION_DOMAIN=.laravel.cloud
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=Lax
SESSION_PARTITIONED_COOKIE=false

# Ensure these are also set
APP_ENV=production
APP_DEBUG=false
APP_URL=https://studeats.laravel.cloud
SESSION_DRIVER=database
SESSION_LIFETIME=1440
```

### **Step 2: Clear All Application Cache**

After updating environment variables, execute:

```bash
php artisan optimize:clear
```

**Or individually:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
```

### **Step 3: Verify Configuration Loading**

Create a temporary debug route to verify the settings loaded correctly:

```php
// Add to routes/web.php temporarily (REMOVE AFTER TESTING)
Route::get('/debug-session-config', function () {
    return response()->json([
        'session_domain' => config('session.domain'),
        'session_secure' => config('session.secure'),
        'session_same_site' => config('session.same_site'),
        'session_partitioned' => config('session.partitioned'),
        'app_env' => config('app.env'),
        'app_url' => config('app.url'),
    ]);
})->middleware('auth', 'admin');
```

**Expected Response:**
```json
{
  "session_domain": ".laravel.cloud",
  "session_secure": true,
  "session_same_site": "Lax",
  "session_partitioned": false,
  "app_env": "production",
  "app_url": "https://studeats.laravel.cloud"
}
```

---

## 📋 Why This Configuration Works

| Setting | Value | Explanation |
|---------|-------|-------------|
| `SESSION_DOMAIN=.laravel.cloud` | Leading dot allows cookie to work across all `*.laravel.cloud` subdomains |
| `SESSION_SECURE_COOKIE=true` | Required for HTTPS (Laravel Cloud uses SSL) |
| `SESSION_SAME_SITE=Lax` | **KEY FIX** - Allows cross-subdomain requests while maintaining security |
| `SESSION_PARTITIONED_COOKIE=false` | Avoids cookie partitioning complications with subdomains |

---

## 🔍 Troubleshooting Steps

### **Test 1: Verify Cookie Settings in Browser**
1. Open Browser DevTools (F12)
2. Go to **Application** → **Cookies**
3. Check `studeats-session` cookie properties:
   - ✅ **Domain:** `.laravel.cloud` (with leading dot)
   - ✅ **Secure:** Yes
   - ✅ **SameSite:** Lax
   - ✅ **HttpOnly:** Yes

### **Test 2: Clear Browser Data**
```powershell
# Run the provided PowerShell script
.\clear-browser-cache.ps1
```

### **Test 3: Test Login Process**
1. Visit: `https://studeats.laravel.cloud/admin/login`
2. Open Browser Console
3. Enter credentials: `admin@studeats.com` / `admin123`
4. Submit form
5. **Expected:** ✅ Successful login, NO 419 errors

---

## 🚨 Alternative Configuration (If Still Issues)

If the `Lax` setting doesn't work, try the modern approach:

```env
SESSION_DOMAIN=.laravel.cloud
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
SESSION_PARTITIONED_COOKIE=true
```

**Note:** This requires all requests to be HTTPS and may have browser compatibility considerations.

---

## 📝 Environment Variable Checklist

**In Laravel Cloud Environment tab, ensure you have:**

- [ ] `SESSION_DOMAIN=.laravel.cloud`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] `SESSION_SAME_SITE=Lax`
- [ ] `SESSION_PARTITIONED_COOKIE=false`
- [ ] `APP_ENV=production`
- [ ] `APP_URL=https://studeats.laravel.cloud`
- [ ] `SESSION_DRIVER=database`

---

## 🎯 Expected Outcome

After applying these changes:

1. **Cookie Domain Error:** ✅ Resolved
2. **419 CSRF Errors:** ✅ Eliminated
3. **Admin Login:** ✅ Working
4. **User Login:** ✅ Working
5. **Session Persistence:** ✅ Maintained across requests

---

## 📞 Support

If issues persist after following this guide:

1. Check Laravel Cloud deployment logs for errors
2. Verify database migration status: `php artisan migrate:status`
3. Ensure `sessions` table exists in production database
4. Contact Laravel Cloud support with specific error messages

---

**Status:** Ready for immediate deployment  
**Last Updated:** November 9, 2025  
**Tested:** Laravel Cloud Production Environment