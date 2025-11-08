# Cookie Domain Fix - Laravel Cloud Deployment Guide

## Problem Summary
Cookie rejection errors preventing login on `https://studeats.laravel.cloud`:
- `XSRF-TOKEN` rejected for invalid domain
- `studeats-session` rejected for invalid domain
- CSRF token cookies rejected for invalid domain
- Cloudflare `__cf_bm` cookie rejected

## Root Cause
`SESSION_DOMAIN=null` doesn't work with Laravel Cloud's subdomain architecture (`*.laravel.cloud`). Browsers require valid domain for HTTPS cookies.

## Solution Applied
✅ Updated `laravel-cloud.json` with `SESSION_DOMAIN=.laravel.cloud`
✅ Enhanced post-deploy script to refresh session configuration
✅ Maintained `SESSION_SAME_SITE=lax` for best compatibility

---

## Deployment Steps

### Step 1: Commit and Push Changes
```bash
# Stage the configuration changes
git add laravel-cloud.json post-deploy-laravel-cloud.sh

# Commit with descriptive message
git commit -m "Fix: Set SESSION_DOMAIN to .laravel.cloud for cookie compatibility"

# Push to main branch (triggers Laravel Cloud deployment)
git push origin main
```

### Step 2: Set Environment Variables in Laravel Cloud Dashboard

**Navigate to:** Laravel Cloud Dashboard → StudEats Project → Environment Variables

**Add/Update these variables:**
```
SESSION_DOMAIN=.laravel.cloud
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SESSION_PARTITIONED_COOKIE=false
SESSION_LIFETIME=240
APP_URL=https://studeats.laravel.cloud
```

**Important:** Make sure there are NO quotes around `.laravel.cloud` in the dashboard!

### Step 3: Wait for Deployment to Complete

Monitor the deployment in Laravel Cloud Dashboard. The `post-deploy-laravel-cloud.sh` script will:
- Clear all caches
- Run migrations
- Seed admin user
- Cache optimized config with new SESSION_DOMAIN
- Refresh config cache to ensure session settings are active

### Step 4: Verify Cookie Configuration

**Option A: Use Debug Route**
```
Visit: https://studeats.laravel.cloud/debug-cookies
```

**Expected Output:**
```json
{
  "session_config": {
    "cookie_name": "studeats-session",
    "domain": ".laravel.cloud",  // ✅ Should NOT be null
    "secure": true,
    "same_site": "lax",
    "partitioned": false
  }
}
```

**Option B: Browser DevTools**
1. Open https://studeats.laravel.cloud/login
2. Open DevTools (F12) → Application/Storage → Cookies
3. Check cookie domain shows `.laravel.cloud` with leading dot

### Step 5: Test Login Flow

**Test User Login:**
```
URL: https://studeats.laravel.cloud/login
Create account or use existing credentials
```

**Test Admin Login:**
```
URL: https://studeats.laravel.cloud/admin/login
Email: admin@studeats.com
Password: admin123
```

**Expected Behavior:**
- ✅ Cookies accepted without domain errors
- ✅ CSRF token validation works
- ✅ Session persists after login
- ✅ Dashboard accessible after login

---

## Troubleshooting

### If Cookies Still Rejected After Deployment

**Clear Config Cache Manually:**
```bash
# SSH into Laravel Cloud or use Tinker
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

**Verify Environment Variables:**
```bash
# Check if SESSION_DOMAIN is properly set
php artisan tinker
>>> config('session.domain')
// Should return: ".laravel.cloud"
```

### If SameSite=Lax Doesn't Work

**Try Alternative Configuration:**

Update in Laravel Cloud Dashboard:
```
SESSION_SAME_SITE=none
SESSION_PARTITIONED_COOKIE=true
```

Then clear caches and test again.

### Cloudflare `__cf_bm` Cookie Issues

This is separate from Laravel session cookies. If still seeing `__cf_bm` rejection:

1. **Cloudflare Dashboard** → Your Domain → Security → Bots
2. Configure **Bot Fight Mode** settings
3. Whitelist `studeats.laravel.cloud` in bot management rules
4. Consider disabling "Challenge Passage" for your domain

**Note:** `__cf_bm` is Cloudflare's bot management cookie - Laravel config won't affect it.

---

## Configuration Details

### Updated Files

**`laravel-cloud.json` (Line 25):**
```json
"SESSION_DOMAIN": ".laravel.cloud"
```

**`post-deploy-laravel-cloud.sh`:**
Added final config cache refresh to ensure session settings are active.

### Why `.laravel.cloud` with Leading Dot?

The leading dot (`.`) creates a wildcard domain that works for:
- ✅ `studeats.laravel.cloud` (your main app)
- ✅ `api.studeats.laravel.cloud` (if you add API subdomain)
- ✅ Any future subdomains you create

Without the dot, cookies would only work on the exact domain specified.

### Security Settings Explained

- **`SESSION_SECURE_COOKIE=true`**: Cookies only sent over HTTPS (required for production)
- **`SESSION_SAME_SITE=lax`**: Allows cookies on same-site navigation (better UX than `strict`)
- **`SESSION_PARTITIONED_COOKIE=false`**: Standard cookie behavior (not partitioned by top-level site)

---

## Validation Checklist

After deployment, verify:

- [ ] Git changes pushed to main branch
- [ ] Laravel Cloud deployment completed successfully
- [ ] Environment variables set in dashboard (no quotes on domain value)
- [ ] Config cache cleared and rebuilt
- [ ] `/debug-cookies` shows `domain: ".laravel.cloud"`
- [ ] Browser DevTools shows cookies with correct domain
- [ ] Login page loads without cookie rejection errors
- [ ] User login works and redirects to dashboard
- [ ] Admin login works and redirects to admin panel
- [ ] Session persists across page navigation
- [ ] CSRF token validation works on forms

---

## Quick Commands Reference

```bash
# Local testing (should NOT use .laravel.cloud domain)
php artisan config:show session

# Deploy to Laravel Cloud
git push origin main

# Manual cache clear (if needed via SSH/Tinker)
php artisan config:clear && php artisan cache:clear && php artisan config:cache

# Check current session domain
php artisan tinker
>>> config('session.domain')

# View debug information
curl https://studeats.laravel.cloud/debug-cookies
```

---

## Success Indicators

✅ **Cookies Accepted**: No "rejected for invalid domain" errors in browser console
✅ **Login Works**: Can successfully log in and access dashboard
✅ **Session Persists**: Don't get logged out on page refresh
✅ **CSRF Valid**: Forms submit without 419 errors
✅ **Debug Route Shows Correct Config**: Domain is `.laravel.cloud` not `null`

---

## Notes

- **Local Development**: Keep `SESSION_DOMAIN=null` in local `.env` file (correct for localhost)
- **Production Only**: `.laravel.cloud` domain setting only applies to Laravel Cloud deployment
- **Cloudflare Issues**: Separate from Laravel - requires Cloudflare dashboard configuration
- **Alternative Config**: If `lax` doesn't work, try `none` + `partitioned=true` combination

---

**Last Updated:** November 9, 2025
**Status:** Ready for deployment
**Estimated Deploy Time:** 3-5 minutes
