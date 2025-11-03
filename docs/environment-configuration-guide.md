# Production Environment Configuration for PostgreSQL Migration

## Step-by-Step Environment Setup

### 1. Local Development (.env)

```env
APP_NAME=StudEats
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:cVdpd4/yHnZtTDWQB+yi351zUDmvNHf/j5pPEgTM4a4=
APP_URL=http://127.0.0.1:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

# LOCAL MYSQL (Current - Keep as backup)
DB_CONNECTION_BACKUP=mysql
DB_HOST_BACKUP=127.0.0.1
DB_PORT_BACKUP=3306
DB_DATABASE_BACKUP=studeats
DB_USERNAME_BACKUP=root
DB_PASSWORD_BACKUP=

# POSTGRESQL (New - For local testing)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=studeats_postgres
DB_USERNAME=postgres
DB_PASSWORD=your_password
DATABASE_URL=postgresql://postgres:your_password@127.0.0.1:5432/studeats_postgres

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=johnalbertandal5@gmail.com
MAIL_PASSWORD="fovl fkzb btdz ezvk"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=johnalbertandal5@gmail.com
MAIL_FROM_NAME=StudEats

NUTRITION_API_URL=https://api.nal.usda.gov/fdc/v1
NUTRITION_API_KEY=Qs1e7LNogSHAQI1GBnHA9vuWx2OwzJceO4FHhEdP

VITE_APP_NAME=StudEats
```

### 2. Staging Environment (Render - For Testing)

**Navigate to**: Render Dashboard → Environment Variables

```env
# Application
APP_NAME=StudEats-Staging
APP_ENV=staging
APP_DEBUG=false
APP_KEY=[Generate with: php artisan key:generate --show]
APP_URL=https://studeats-staging.onrender.com

# Logging
LOG_CHANNEL=errorlog
LOG_LEVEL=info
LOG_STDERR_FORMATTER=Monolog\Formatter\JsonFormatter

# Database - PostgreSQL (Render Managed)
DB_CONNECTION=pgsql
DB_HOST=[From Render Dashboard - Internal hostname]
DB_PORT=5432
DB_DATABASE=studeats_staging
DB_USERNAME=[From Render Dashboard]
DB_PASSWORD=[From Render Dashboard]
DATABASE_URL=[Full connection string from Render]

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=johnalbertandal5@gmail.com
MAIL_PASSWORD="fovl fkzb btdz ezvk"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=johnalbertandal5@gmail.com
MAIL_FROM_NAME="StudEats Staging"

# External APIs
NUTRITION_API_URL=https://api.nal.usda.gov/fdc/v1
NUTRITION_API_KEY=Qs1e7LNogSHAQI1GBnHA9vuWx2OwzJceO4FHhEdP

# Performance
FILESYSTEM_DISK=local
BROADCAST_CONNECTION=log
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
```

### 3. Production Environment (Render - Live)

**Navigate to**: Render Dashboard → Environment Variables

```env
# Application
APP_NAME=StudEats
APP_ENV=production
APP_DEBUG=false
APP_KEY=[Generate unique key - NEVER reuse staging key]
APP_URL=https://studeats.onrender.com

# Logging
LOG_CHANNEL=errorlog
LOG_LEVEL=warning
LOG_STDERR_FORMATTER=Monolog\Formatter\JsonFormatter

# Database - PostgreSQL (Render Managed)
DB_CONNECTION=pgsql
DB_HOST=[From Render Dashboard - Internal hostname]
DB_PORT=5432
DB_DATABASE=studeats_production
DB_USERNAME=[From Render Dashboard]
DB_PASSWORD=[From Render Dashboard]
DATABASE_URL=[Full connection string from Render]

# Performance Tuning
DB_TIMEOUT=5
DB_CONNECT_TIMEOUT=5
DB_CHARSET=utf8
DB_SSLMODE=require

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=true
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=johnalbertandal5@gmail.com
MAIL_PASSWORD="fovl fkzb btdz ezvk"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=johnalbertandal5@gmail.com
MAIL_FROM_NAME=StudEats

# External APIs
NUTRITION_API_URL=https://api.nal.usda.gov/fdc/v1
NUTRITION_API_KEY=Qs1e7LNogSHAQI1GBnHA9vuWx2OwzJceO4FHhEdP

# Performance & Security
FILESYSTEM_DISK=local
BROADCAST_CONNECTION=log
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

# Asset URL (if using CDN)
ASSET_URL=https://studeats.onrender.com

# Trusted Proxies
TRUSTED_PROXIES=*
TRUSTED_HOSTS="studeats.onrender.com,*.onrender.com"
```

### 4. Setting Up Render PostgreSQL Database

#### Via Render Dashboard:

1. **Create Database**:
   - Go to: https://dashboard.render.com/new/database
   - Click "New +" → "PostgreSQL"
   - Configuration:
     - **Name**: `studeats-production-db`
     - **Database**: `studeats`
     - **User**: (auto-generated)
     - **Region**: `Oregon` (or closest to your users)
     - **PostgreSQL Version**: `16`
     - **Plan**: 
       - Free tier: For testing
       - Starter ($7/mo): Minimum for production
       - Standard ($20/mo): Recommended for production
   
2. **Get Connection Details**:
   - After creation, go to database page
   - Copy:
     - **Internal Database URL** (for Render services)
     - **External Database URL** (for local testing)
     - **Host**, **Port**, **Database**, **Username**, **Password**

3. **Configure Environment Variables**:
   - Go to your web service
   - Navigate to "Environment" tab
   - Add all variables from section 3 above
   - Use **Internal Database URL** for `DATABASE_URL`

#### Via Render CLI (Alternative):

```bash
# Install Render CLI
npm install -g @render/cli

# Login
render login

# Create PostgreSQL database
render database create \
  --name studeats-production-db \
  --plan starter \
  --region oregon \
  --version 16
```

### 5. Environment Variable Security Best Practices

#### DO:
✅ Use different `APP_KEY` for each environment
✅ Rotate database passwords regularly
✅ Use internal connection strings on Render
✅ Enable SSL/TLS for database connections
✅ Keep secrets in Render dashboard (not in code)
✅ Use environment-specific API keys when possible

#### DON'T:
❌ Never commit `.env` files to Git
❌ Never reuse production credentials in staging
❌ Never hardcode credentials in code
❌ Never share credentials via email/chat
❌ Never use `APP_DEBUG=true` in production
❌ Never expose database ports publicly

### 6. Verifying Configuration

#### Local Testing:

```bash
# Test PostgreSQL connection
php artisan tinker
>>> DB::connection('pgsql')->select('SELECT version()');
>>> DB::connection('pgsql')->select('SELECT current_database()');

# Run migrations
php artisan migrate --database=pgsql

# Run tests
php artisan test --filter=DatabaseMigrationTest
```

#### Staging/Production Testing:

```bash
# SSH into Render (if available) or use Render Shell
render shell -s [service-id]

# Test connection
php artisan tinker
>>> DB::connection()->select('SELECT version()');

# Check environment
php artisan config:show database

# Verify migrations
php artisan migrate:status
```

### 7. Render Build Configuration

Update `render-build.sh`:

```bash
#!/bin/bash
set -e

echo "=== Building StudEats ==="

# Install dependencies
composer install --no-dev --optimize-autoloader

# Build assets
npm ci
npm run build

# Set up Laravel
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

chmod -R 755 storage bootstrap/cache

echo "=== Build Complete ==="
```

Update `render-start.sh`:

```bash
#!/bin/bash
set -e

echo "=== Starting StudEats ==="

# Wait for database to be ready
echo "Waiting for database..."
sleep 5

# Run migrations
php artisan migrate --force --no-interaction

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start queue worker in background
php artisan queue:work --daemon &

# Start Apache
apache2-foreground
```

### 8. Database Configuration Optimization

Update `config/database.php`:

```php
'pgsql' => [
    'driver' => 'pgsql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'studeats'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => env('DB_CHARSET', 'utf8'),
    'prefix' => '',
    'prefix_indexes' => true,
    'search_path' => 'public',
    'sslmode' => env('DB_SSLMODE', 'require'),
    
    'options' => extension_loaded('pdo_pgsql') ? [
        PDO::ATTR_TIMEOUT => env('DB_TIMEOUT', 5),
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ] : [],
],
```

### 9. Connection String Formats

**PostgreSQL Connection Strings**:

```
# Full format
postgresql://username:password@hostname:port/database?sslmode=require

# Render internal (use this in production)
postgresql://user:pass@dpg-xxxxx-a.oregon-postgres.render.com/database_xxxx

# Render external (for local testing only)
postgresql://user:pass@dpg-xxxxx-a.oregon-postgres.render.com:5432/database_xxxx

# Local development
postgresql://postgres:password@localhost:5432/studeats_postgres
```

### 10. Monitoring Configuration

Add to `.env` (optional but recommended):

```env
# Monitoring & Analytics
TELESCOPE_ENABLED=false  # Enable only in staging
DEBUGBAR_ENABLED=false   # Never in production

# Error Tracking (if using Sentry, etc.)
SENTRY_LARAVEL_DSN=
SENTRY_TRACES_SAMPLE_RATE=0.2

# Performance
QUERY_LOG_ENABLED=false
QUERY_LOG_SLOW_THRESHOLD=1000  # Log queries > 1 second
```

### 11. Rollback Configuration

Keep MySQL connection available for emergency rollback:

```env
# In .env (keep as backup)
DB_CONNECTION_MYSQL=mysql
DB_HOST_MYSQL=your-mysql-host
DB_PORT_MYSQL=3306
DB_DATABASE_MYSQL=studeats
DB_USERNAME_MYSQL=user
DB_PASSWORD_MYSQL=pass
```

Then you can quickly switch:

```bash
# Rollback to MySQL
php artisan config:clear
# Update .env: DB_CONNECTION=mysql
php artisan config:cache
php artisan queue:restart
```

### 12. Health Check Endpoint

Add to `routes/web.php`:

```php
Route::get('/health', function () {
    try {
        DB::connection()->select('SELECT 1');
        return response()->json([
            'status' => 'ok',
            'database' => 'connected',
            'timestamp' => now()->toIso8601String(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'database' => 'disconnected',
            'error' => $e->getMessage(),
        ], 503);
    }
});
```

Configure Render health check:
- Path: `/health`
- Expected Status: `200`
- Interval: 30 seconds

---

## Quick Reference Commands

```bash
# Generate new app key
php artisan key:generate --show

# Test database connection
php artisan db:ping

# View current configuration
php artisan config:show database

# Clear all caches
php artisan optimize:clear

# Cache configuration
php artisan optimize

# Run migrations
php artisan migrate --force

# Rollback migrations
php artisan migrate:rollback

# Check migration status
php artisan migrate:status

# Run tests
php artisan test

# Create database backup
php artisan db:backup

# Import/Export data
php artisan db:migrate-to-postgresql export
php artisan db:migrate-to-postgresql import --validate
```
