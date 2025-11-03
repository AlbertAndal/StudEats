# Render Database Connection Fix

## Problem Identified

Your Render deployment is failing with:
```
SQLSTATE[08006] [7] could not translate host name "dpg-xxxxx-oregon-postgres.render.com" 
to address: Name or service not known
```

**Root Cause:** The PostgreSQL database is not properly connected to your web service in Render.

---

## Solution: Connect PostgreSQL Database

### Step 1: Check if Database Exists

1. Go to **Render Dashboard**: https://dashboard.render.com
2. Look in the left sidebar for "PostgreSQL" section
3. Check if database **"studeats-db"** exists

### Step 2A: If Database EXISTS

1. Click on your **Web Service** (studeats)
2. Go to **"Environment"** tab
3. Look for `DATABASE_URL` variable
4. **If missing**, click "Add Environment Variable":
   - Key: `DATABASE_URL`
   - Value: Click "Add from Database" → Select `studeats-db`
5. Click **"Save Changes"**
6. Go to **"Manual Deploy"** tab → Click **"Deploy latest commit"**

### Step 2B: If Database DOES NOT EXIST

1. Click **"New +"** button (top right)
2. Select **"PostgreSQL"**
3. Configure database:
   - **Name:** `studeats-db`
   - **Database:** `studeats`
   - **User:** `studeats_user`
   - **Region:** `Oregon` (same as web service)
   - **Plan:** `Free` (or higher for production)
4. Click **"Create Database"**
5. Wait 2-3 minutes for database to provision
6. Once **"Available"** status appears, go back to **Step 2A**

### Step 3: Verify Connection

After redeployment:

1. Go to your web service **"Logs"** tab
2. Look for these success messages:
   ```
   ✅ Detected PostgreSQL database
   ✅ Database connection successful
   ✅ Migrations completed
   ```
3. Visit your deployed app URL
4. Try registering a new account to test database writes

---

## Changes Made in This Fix

### 1. Updated `render.yaml`
```yaml
envVars:
  - key: DATABASE_URL
    fromDatabase:
      name: studeats-db
      property: connectionString
```
**Why:** Automatically links the PostgreSQL database connection string to your web service.

### 2. Changed Cache/Session Drivers
```yaml
- key: CACHE_STORE
  value: database  # Changed from 'file'
- key: SESSION_DRIVER
  value: database  # Changed from 'file'
```
**Why:** File-based cache doesn't work reliably in containerized Render environments. Database-backed cache/sessions are more stable.

### 3. Enhanced Error Logging in `render-start.sh`
- Better database connection error detection
- Clear troubleshooting steps in logs
- Graceful degradation if database unavailable

---

## Verification Checklist

After deploying the fix:

- [ ] PostgreSQL database `studeats-db` shows "Available" status
- [ ] Web service environment has `DATABASE_URL` set from database
- [ ] Deployment logs show "✅ Database connection successful"
- [ ] Deployment logs show "✅ Migrations completed"
- [ ] Homepage loads without errors
- [ ] Login page loads without errors
- [ ] Registration creates new users successfully
- [ ] No "could not translate host name" errors in logs

---

## Environment Variables Summary

Your web service should have these automatically set:

| Variable | Value | Source |
|----------|-------|--------|
| `DB_CONNECTION` | `pgsql` | render.yaml |
| `DATABASE_URL` | `postgresql://user:pass@host:5432/db` | Auto from database |
| `CACHE_STORE` | `database` | render.yaml |
| `SESSION_DRIVER` | `database` | render.yaml |
| `APP_KEY` | (your existing key) | Render dashboard |

---

## Common Issues & Solutions

### Issue: "Database not found"
**Solution:** Make sure database name is exactly `studeats-db` (with hyphen, not underscore)

### Issue: "Connection timeout"
**Solution:** Ensure database and web service are in the same region (Oregon)

### Issue: "Authentication failed"
**Solution:** Use `DATABASE_URL` from database (don't manually set credentials)

### Issue: "502 Bad Gateway"
**Solution:** Wait 1-2 minutes after deploy for service to fully start

### Issue: "Migrations failed"
**Solution:** 
1. Go to web service **"Shell"** tab
2. Run: `php artisan migrate:status`
3. Run: `php artisan migrate --force`

---

## Database Migration from MySQL

If you have existing MySQL data you want to migrate:

1. **Export from local MySQL:**
   ```bash
   php database/scripts/export-mysql-data.php
   ```

2. **Upload to Render Shell:**
   - Use the Shell tab in Render dashboard
   - Copy export files to `/tmp/`

3. **Import to PostgreSQL:**
   ```bash
   php database/scripts/import-postgresql-data.php
   ```

See `DATABASE-MIGRATION-README.md` for detailed migration instructions.

---

## Need Help?

1. Check Render logs for specific error messages
2. Verify all environment variables are set correctly
3. Ensure database is in "Available" state
4. Test database connection via Render Shell: `php artisan tinker`

**Render Documentation:**
- PostgreSQL: https://render.com/docs/databases
- Environment Variables: https://render.com/docs/configure-environment-variables
- Troubleshooting: https://render.com/docs/troubleshooting-deploys

---

**Status:** Fixed in commit - Ready for deployment  
**Date:** November 3, 2025  
**Priority:** CRITICAL - Required for application to function
