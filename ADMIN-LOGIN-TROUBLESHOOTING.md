# Admin Login Troubleshooting Guide

**Issue:** Invalid admin credentials at https://studeats.laravel.cloud/admin/login  
**Credentials:** admin@studeats.com / admin123  
**Error Message:** "Invalid admin credentials. Please check your email and password."

---

## Quick Diagnosis

The admin account exists and the password is correct (verified locally). The "Invalid credentials" error on production likely means:

1. **Deployment not complete** - Config cache hasn't been rebuilt yet
2. **Database connectivity issue** - Can't query the user from database
3. **Session/Cookie issue** - Related to our cookie domain fix
4. **Old config cached** - SESSION_DOMAIN still set incorrectly

---

## Step-by-Step Troubleshooting

### Step 1: Check Deployment Status

**Do this first:**
1. Go to Laravel Cloud dashboard
2. Check if deployment of commit `f3d0d03` has completed
3. Look for any errors in deployment logs

**Expected:**
- Status: ✅ Complete
- Last line should mention config:cache or route:cache running

**If stuck:**
- Deployment may have timed out or failed
- Check Laravel Cloud notifications for errors
- Try triggering manual deployment

---

### Step 2: Verify the Cookie Domain Fix Deployed

Since the fix was just pushed, verify it took effect:

**Access via browser DevTools:**
1. Open https://studeats.laravel.cloud/admin/login
2. Press F12 (DevTools)
3. Go to Application → Cookies → studeats.laravel.cloud

**Look for:**
- `Domain`: Should show `studeats.laravel.cloud` (NO leading dot like `.laravel.cloud`)
- `Secure`: Should be `true`
- `SameSite`: Should be `Lax`

**If showing `.laravel.cloud`:**
- Old config is still cached
- Run: Manual deployment or SSH into server
- Execute: `php artisan config:clear; php artisan config:cache`

---

### Step 3: Clear Browser Cache & Cookies

Sometimes old cookies interfere:

**Option A: Clear everything**
```javascript
// Paste in browser console at https://studeats.laravel.cloud/admin/login
document.cookie.split(";").forEach(function(c) {
  document.cookie = c.trim().split("=")[0] + 
    "=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;domain=.laravel.cloud";
});

// Then hard refresh
location.reload(true);
```

**Option B: Manual**
1. Open Settings → Privacy → Clear browsing data
2. Select "All time" and "Cookies and other site data"
3. Clear
4. Return to https://studeats.laravel.cloud/admin/login

---

### Step 4: Test Database Connection

If deployment completed but still failing:

**Verify database connectivity via Artisan:**

```bash
# If you have SSH access to Laravel Cloud
php artisan tinker

# Then run:
>>> $admin = \App\Models\User::where('email', 'admin@studeats.com')->first();
>>> $admin; // Should show the admin user
>>> Hash::check('admin123', $admin->password); // Should return true
```

**If connection fails:**
- Database credentials wrong in `.env`
- Database server down
- Network issue between app and database

---

### Step 5: Check Application Logs

**Access logs via Laravel Cloud:**
1. Dashboard → Recent logs
2. Search for "admin" or "login"  
3. Look for specific error messages

**Common errors:**
- `SQLSTATE[HY000]` → Database connection failed
- `CSRF token mismatch` → Session not working
- `Invalid credentials` → Password check failed or user not found

---

### Step 6: Manual Admin Password Reset

If still failing, reset the admin password via direct database access or artisan:

```bash
# SSH to Laravel Cloud or run this locally first, then push
php artisan tinker

>>> use Illuminate\Support\Facades\Hash;
>>> $admin = \App\Models\User::where('email', 'admin@studeats.com')->first();
>>> $admin->update(['password' => Hash::make('NewPassword123!')]);
>>> exit;
```

Then try logging in with new password.

---

### Step 7: Emergency Reset Script

If all else fails, use the emergency reset:

**If you have access to `public/admin-reset.php` or `public/create-admin.php`:**

1. Navigate to https://studeats.laravel.cloud/admin-reset.php
2. Fill in new admin credentials
3. Submit
4. Delete the file after use (security risk!)

**Note:** You may need to enable this route if it's disabled. Check `routes/web.php`

---

## Common Issues & Solutions

### "Invalid admin credentials" after deployment

**Most likely cause:** Old config cached with `SESSION_DOMAIN=.laravel.cloud`

**Solution:**
```bash
# SSH or run these commands
php artisan config:clear
php artisan config:cache
```

Then clear browser cookies and try again.

---

### "Invalid credentials" but password is correct locally

**Cause:** Admin user doesn't exist in production database or database isn't connected

**Solution:**
1. Verify deployment completed and migrations ran
2. Check database: `php artisan migrate --force`
3. Seed admin: `php artisan db:seed --class=AdminSeeder --force`

---

### Login page won't load / 419 error

**Cause:** CSRF token issue (related to cookie domain fix)

**Solution:**
1. Hard refresh (Ctrl+Shift+R)
2. Clear browser cookies completely
3. Check if cookies are being sent with the Domain attribute correct
4. Verify deployment completed

---

### Page loads but form submission hangs

**Cause:** Session storage or database query timing out

**Solution:**
1. Check database connection
2. Check `sessions` table exists: `php artisan session:table --no-interaction`
3. Ensure session table migration ran
4. Restart queue if needed

---

## Debug Checklist

Before contacting support, verify all of these:

- [ ] Deployment completed successfully (check logs)
- [ ] Config cache was rebuilt (`config:cache` ran)
- [ ] Browser cookies cleared completely
- [ ] Hard refresh done (Ctrl+Shift+R)
- [ ] Email address is exactly: `admin@studeats.com`
- [ ] Password is exactly: `admin123` (or new password if changed)
- [ ] Database is connected (run tinker test)
- [ ] Admin user exists in database
- [ ] HTTPS is being used (not HTTP)
- [ ] No 419 CSRF errors in console

---

## Testing the Cookie Domain Fix

To verify the fix is working correctly:

```javascript
// In browser console on https://studeats.laravel.cloud/admin/login
console.log('Cookies:', document.cookie);
console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').content);

// Check cookie details in DevTools:
// Application → Cookies → studeats.laravel.cloud
// Domain should be: studeats.laravel.cloud (NOT .laravel.cloud)
```

---

## If All Else Fails

1. **Check deployment logs** - Most errors are visible there
2. **Restart the app** - Via Laravel Cloud dashboard
3. **Manual redeploy** - Push a new commit to trigger fresh deployment
4. **Check database** - Ensure migrations ran: `php artisan migrate:status`
5. **Reset admin** - Use emergency reset script if available
6. **Contact Laravel Cloud support** - If infrastructure issue

---

## Expected Behavior After Fix

✅ **Login page loads** → Cookies have correct domain
✅ **Form submits** → CSRF token accepted, session stored
✅ **Admin dashboard** → User authenticated and session persists
✅ **No 419 errors** → Cookie domain matching works
✅ **No "invalid credentials"** → Database connected and user found

---

## Additional Resources

- **Cookie Domain Fix Guide:** `COOKIE-SESSION-GUIDE.md`
- **Deployment Checklist:** `DEPLOY-CHECKLIST.md`
- **Laravel Cloud Docs:** https://laravel.com/docs/laravel-cloud
- **Session Configuration:** `config/session.php`

---

**Last Updated:** November 9, 2025  
**Related Commits:** `f3d0d03` (Cookie domain auto-detection)
