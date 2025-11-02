#!/bin/bash

# Enhanced Render Startup Script for StudEats with Comprehensive Error Handling
set +e  # Don't exit on errors initially - we want to debug issues

echo "=== 🚀 StudEats Render Startup Script v2.0 ==="
echo "Timestamp: $(date)"

# Function to log with timestamp and color
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

error() {
    echo "❌ [$(date '+%Y-%m-%d %H:%M:%S')] ERROR: $1"
}

success() {
    echo "✅ [$(date '+%Y-%m-%d %H:%M:%S')] SUCCESS: $1"
}

warn() {
    echo "⚠️ [$(date '+%Y-%m-%d %H:%M:%S')] WARNING: $1"
}

# Show comprehensive environment info
log "📋 Environment Information:"
echo "PHP Version: $(php --version | head -n 1)"
echo "Laravel Version: $(php artisan --version 2>&1 || echo 'Laravel not accessible')"
echo "Environment: ${APP_ENV:-not-set}"
echo "Debug Mode: ${APP_DEBUG:-not-set}"
echo "Port: ${PORT:-8000}"
echo "Current Directory: $(pwd)"
echo "User: $(whoami 2>/dev/null || echo 'unknown')"
echo "Memory: $(free -h 2>&1 | grep Mem || echo 'Memory info not available')"

# Check critical environment variables
log "🔍 Checking Critical Environment Variables:"
critical_vars=("APP_KEY" "APP_ENV" "DB_CONNECTION")
missing_vars=()

for var in "${critical_vars[@]}"; do
    if [ -n "${!var}" ]; then
        if [ "$var" = "APP_KEY" ]; then
            echo "✅ $var: [REDACTED - ${#APP_KEY} characters]"
        else
            echo "✅ $var: ${!var}"
        fi
    else
        echo "❌ $var: NOT SET"
        missing_vars+=("$var")
    fi
done

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    warn "APP_KEY not set - attempting to generate..."
    if php artisan key:generate --force --no-interaction --show 2>/dev/null; then
        success "APP_KEY generated successfully"
        export APP_KEY=$(php artisan key:generate --force --no-interaction --show 2>/dev/null)
    else
        error "Failed to generate APP_KEY - this will cause application failure"
    fi
fi

# Database configuration
log "🗄️ Database Configuration:"
if [ -n "$DATABASE_URL" ]; then
    log "DATABASE_URL detected: ${DATABASE_URL:0:30}..."
    
    # Parse DATABASE_URL for different database types
    if [[ "$DATABASE_URL" == postgres* ]]; then
        export DB_CONNECTION=pgsql
        success "PostgreSQL database detected"
    elif [[ "$DATABASE_URL" == mysql* ]]; then
        export DB_CONNECTION=mysql
        success "MySQL database detected"
    else
        warn "Unknown database type in DATABASE_URL"
        export DB_CONNECTION=pgsql  # Default to PostgreSQL for Render
    fi
    
    # Parse database components (for debugging)
    export DB_HOST=$(echo $DATABASE_URL | sed -n 's/.*@\\([^:]*\\):.*/\\1/p' 2>/dev/null || echo 'parse-failed')
    export DB_PORT=$(echo $DATABASE_URL | sed -n 's/.*:\\([0-9]*\\)\\/.*/\\1/p' 2>/dev/null || echo 'parse-failed')
    export DB_DATABASE=$(echo $DATABASE_URL | sed -n 's/.*\\/\\([^?]*\\).*/\\1/p' 2>/dev/null || echo 'parse-failed')
    
    log "Parsed database config:"
    echo "  DB_CONNECTION: $DB_CONNECTION"
    echo "  DB_HOST: ${DB_HOST:0:20}..."
    echo "  DB_PORT: $DB_PORT"
    echo "  DB_DATABASE: $DB_DATABASE"
else
    warn "No DATABASE_URL provided - using manual configuration"
    echo "  DB_CONNECTION: ${DB_CONNECTION:-not-set}"
    echo "  DB_HOST: ${DB_HOST:-not-set}"
    echo "  DB_DATABASE: ${DB_DATABASE:-not-set}"
fi

# Clear Laravel caches to ensure fresh start
log "🧹 Clearing Application Caches..."
cache_commands=(
    "config:clear"
    "cache:clear"
    "view:clear"
    "route:clear"
)

for cmd in "${cache_commands[@]}"; do
    if php artisan $cmd --no-interaction 2>&1; then
        echo "✅ $cmd completed"
    else
        warn "$cmd failed, continuing anyway"
    fi
done

# Test Laravel accessibility
log "🧪 Testing Laravel Installation..."
if php artisan --version >/dev/null 2>&1; then
    success "Laravel is accessible"
    echo "Laravel Version: $(php artisan --version)"
else
    error "Laravel is not accessible - checking installation"
    
    # Attempt to fix with composer
    if composer install --no-dev --optimize-autoloader --no-interaction 2>&1; then
        log "Composer install completed, retesting Laravel..."
        if php artisan --version >/dev/null 2>&1; then
            success "Laravel now accessible after composer install"
        else
            error "Laravel still not accessible after composer install"
        fi
    else
        error "Composer install also failed"
    fi
fi

# Create storage symlink
log "🔗 Creating Storage Symlink..."
if php artisan storage:link --no-interaction 2>&1; then
    success "Storage symlink created"
elif [ -L "public/storage" ]; then
    log "Storage symlink already exists"
else
    warn "Storage symlink creation failed"
fi

# Test database connection
log "🔌 Testing Database Connection..."
db_test_result=$(php artisan migrate:status --no-interaction 2>&1)
if echo "$db_test_result" | grep -q "Migration name\\|No migrations found"; then
    success "Database connection successful"
    
    # Run migrations
    log "📊 Running Database Migrations..."
    if php artisan migrate --force --no-interaction 2>&1; then
        success "Database migrations completed"
    else
        warn "Database migrations failed - application may not work correctly"
        echo "Migration output:"
        php artisan migrate --force --no-interaction 2>&1 | tail -10
    fi
else
    error "Database connection failed"
    echo "Connection test output:"
    echo "$db_test_result"
    warn "Application will likely fail due to database issues"
fi

# Setup application for production
if [ "$APP_ENV" = "production" ]; then
    log "🏭 Configuring for Production..."
    
    # Cache configuration for better performance
    production_commands=(
        "config:cache"
        "route:cache"
        "view:cache"
    )
    
    for cmd in "${production_commands[@]}"; do
        if php artisan $cmd --no-interaction 2>&1; then
            echo "✅ $cmd completed"
        else
            warn "$cmd failed, performance may be affected"
        fi
    done
fi

# Create required directories if missing
log "📁 Ensuring Required Directories..."
required_dirs=(
    "storage/framework/cache/data"
    "storage/framework/sessions"
    "storage/framework/views"
    "storage/logs"
    "storage/app/public"
)

for dir in "${required_dirs[@]}"; do
    if [ ! -d "$dir" ]; then
        mkdir -p "$dir"
        chmod 755 "$dir"
        echo "✅ Created: $dir"
    fi
done

# Set final permissions
chmod -R 755 storage bootstrap/cache 2>/dev/null || warn "Permission setting failed"

# Final environment check
log "📋 Final Environment Check:"
echo "APP_ENV: ${APP_ENV:-not-set}"
echo "APP_DEBUG: ${APP_DEBUG:-not-set}"
echo "APP_KEY: $([ -n "$APP_KEY" ] && echo "✅ Set (${#APP_KEY} chars)" || echo "❌ Missing")"
echo "DB_CONNECTION: ${DB_CONNECTION:-not-set}"
echo "CACHE_STORE: ${CACHE_STORE:-not-set}"
echo "SESSION_DRIVER: ${SESSION_DRIVER:-not-set}"
echo "MAIL_MAILER: ${MAIL_MAILER:-not-set}"

# Test critical routes
log "🧪 Testing Critical Application Routes..."
if php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
echo 'App bootstrap test: OK\n';
" 2>/dev/null; then
    success "Application bootstrap test passed"
else
    error "Application bootstrap test failed"
fi

# Show final status
log "📊 Startup Summary:"
echo "Working Directory: $(pwd)"
echo "PHP Memory Limit: $(php -r 'echo ini_get(\"memory_limit\");')"
echo "Storage Writable: $([ -w "storage/logs" ] && echo "✅ Yes" || echo "❌ No")"
echo "Public Directory: $([ -d "public" ] && echo "✅ Exists" || echo "❌ Missing")"

# Display warnings for common issues
if [ -z "$APP_KEY" ]; then
    error "🚨 CRITICAL: APP_KEY is missing - application will fail!"
fi

if [ -z "$DATABASE_URL" ] && [ -z "$DB_HOST" ]; then
    error "🚨 CRITICAL: No database configuration found!"
fi

# Start the server
log "🚀 Starting Laravel Application Server..."
echo "Application will be available on: http://0.0.0.0:${PORT:-8000}"
echo "Health check available at: http://0.0.0.0:${PORT:-8000}/up"

# Enable strict error handling for server start
set -e

# Start the Laravel server with proper error handling
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}" --no-interaction