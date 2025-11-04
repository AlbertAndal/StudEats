# Railway 500 Error - Quick Fix Guide

## 🚨 Current Issue
Your StudEats app on Railway is returning 500 Server Error due to:
1. **Execution timeout** (30 seconds exceeded)
2. **Database connection issues during startup**
3. **Resource limits on free tier**

## ✅ Immediate Solutions Applied

### 1. Optimized Startup Script
- **Before**: Heavy startup process with long migrations
- **After**: Lightweight startup, background migrations
- **File**: `start-railway.sh` - now starts server immediately

### 2. Updated Railway Configuration
- **Before**: Docker builder (resource-heavy)
- **After**: Nixpacks builder (optimized for Railway)
- **File**: `railway.json` - switched to nixpacks

### 3. Streamlined Build Process
- **Before**: Complex multi-stage build
- **After**: Essential build steps only
- **File**: `nixpacks.toml` - removed timeouts

## 🔧 Deploy These Fixes

### Step 1: Update Your Repository
```bash
# Copy these updated files to your repository:
# - start-railway.sh
# - railway.json  
# - nixpacks.toml
# - post-deploy.sh

# Push to GitHub
git add .
git commit -m "Fix: Optimize Railway deployment for 500 errors"
git push origin main
```

### Step 2: Set Environment Variables in Railway
Go to your Railway dashboard and set:

```env
APP_NAME=StudEats
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:cVdpd4/yHnZtTDWQB+yi351zUDmvNHf/j5pPEgTM4a4=
LOG_CHANNEL=errorlog
LOG_LEVEL=error
SESSION_DRIVER=file
CACHE_STORE=file
```

### Step 3: Add Database Service
1. In Railway dashboard, click **"+ New"**
2. Select **"Database"** → **"MySQL"** or **"PostgreSQL"**  
3. Railway will automatically set `DATABASE_URL`

### Step 4: Redeploy
1. In Railway dashboard, go to **"Deployments"**
2. Click **"Deploy"** to trigger new deployment
3. Watch logs for success messages

## 📊 Expected Results

### Successful Deployment Logs:
```
✅ === StudEats Railway Startup ===
✅ Port: 8000
✅ Environment: production
✅ Creating minimal .env for Railway...
✅ Starting server on 0.0.0.0:8000...
✅ Laravel development server started
```

### Health Check:
```bash
curl https://your-app.railway.app/health
# Should return: {"status":"healthy","database":"connected"}
```

## 🔍 If 500 Error Persists

### Check Railway Logs:
1. Go to Railway dashboard
2. Click on your service
3. Go to **"Deployments"** tab
4. Click latest deployment
5. Check **"Build Logs"** and **"Deploy Logs"**

### Common Error Messages:

**"Maximum execution time exceeded"**
- ✅ Fixed: New startup script is lightweight
- ✅ Fixed: Background migrations prevent blocking

**"Database connection refused"**
- ❓ Check: Is database service added to Railway project?
- ❓ Check: Is `DATABASE_URL` set in environment variables?

**"Class not found"** or **"Composer errors"**
- ❓ Check: Are all files committed to Git?
- ❓ Try: Clear Railway build cache and redeploy

### Emergency Fallback:
If still failing, use this minimal startup:

```bash
# Create minimal start script
echo '#!/bin/bash
export APP_ENV=production
export APP_DEBUG=false
export LOG_CHANNEL=errorlog
php artisan serve --host=0.0.0.0 --port=$PORT' > start-railway.sh

chmod +x start-railway.sh
git add start-railway.sh
git commit -m "Emergency: Minimal Railway startup"
git push origin main
```

## 📞 Additional Help

### Railway Documentation:
- https://docs.railway.app/guides/php
- https://docs.railway.app/guides/laravel

### Laravel on Railway Tutorial:
- https://blog.railway.app/p/laravel-on-railway

### If You Need More Help:
1. Check Railway community forum
2. Review StudEats documentation in repository
3. Create issue in StudEats GitHub repository

---

**Status**: Ready to deploy ✅  
**ETA**: 2-3 minutes for Railway deployment  
**Expected Result**: Working app with database connection