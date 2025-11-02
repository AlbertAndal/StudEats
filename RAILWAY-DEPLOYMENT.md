# Railway Deployment Guide for StudEats

## Required Environment Variables in Railway

Make sure these are set in your Railway service settings:

### Application
```
APP_NAME=StudEats
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:cVdpd4/yHnZtTDWQB+yi351zUDmvNHf/j5pPEgTM4a4=
APP_URL=https://your-railway-domain.railway.app
```

### Database
Railway automatically provides `DATABASE_URL` when you add a MySQL service.
You can also set these manually:
```
DB_CONNECTION=mysql
DB_HOST=gondola.proxy.rlwy.net
DB_PORT=48957
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Sessions & Cache
```
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Mail (Optional)
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME=StudEats
```

### API Keys (Optional)
```
NUTRITION_API_URL=https://api.nal.usda.gov/fdc/v1
NUTRITION_API_KEY=your_api_key
```

### Logging
```
LOG_CHANNEL=errorlog
LOG_LEVEL=error
```

## Deployment Steps

1. **Connect Repository**
   - Link your GitHub StudEats-9 repository
   - Select `main` branch

2. **Add MySQL Database**
   - Add MySQL service in Railway
   - It will auto-generate DATABASE_URL

3. **Configure Environment Variables**
   - Add all variables listed above
   - Make sure APP_KEY is set

4. **Deploy**
   - Railway will auto-deploy from StudEats-9 repository
   - Check logs for any errors

## Troubleshooting

### If deployment fails:

1. **Check Deploy Logs** - Look for:
   - ✅ "Database connection successful"
   - ✅ "Migrations completed successfully"
   - ✅ "Starting Laravel server"

2. **Common Issues:**
   - Database not connected: Add MySQL service first
   - APP_KEY missing: Set in environment variables
   - Port binding: Railway automatically sets PORT

3. **Health Check Failures:**
   - Wait 30-60 seconds after deployment
   - Server needs time to start and run migrations
   - Check if DATABASE_URL is set correctly

### Viewing Logs

Railway Deploy Logs will show:
- 🚀 Starting message
- 📦 Config clearing
- 🔍 Database test results
- 🗄️ Migration status
- 🌐 Server start confirmation

### Manual Commands (if needed)

SSH into Railway and run:
```bash
# Clear cache
php artisan config:clear

# Check database
php artisan db:show

# Run migrations
php artisan migrate --force

# Check routes
php artisan route:list
```

## Success Indicators

When deployment succeeds, you'll see:
1. ✅ Build completed
2. ✅ Migrations completed
3. ✅ Server started on port $PORT
4. ✅ Health check passing (if configured)

Your app should be accessible at your Railway domain!
