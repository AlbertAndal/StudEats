#!/bin/bash

# Don't exit on errors initially - we want to debug issues
set +e

echo "=== StudEats Render Startup Debug ==="

# Show environment info
echo "PHP Version: $(php --version | head -n 1)"
echo "Node Version: $(node --version 2>&1 || echo 'Node not available')"
echo "Environment: ${APP_ENV:-production}"
echo "Port: ${PORT:-8000}"
echo "Current directory: $(pwd)"
echo "Files in current directory:"
ls -la

# Check if Laravel is ready
echo "Checking Laravel installation..."
if php artisan --version 2>&1; then
    echo "✅ Laravel is accessible"
else
    echo "❌ Laravel is not accessible - checking composer install"
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Show database configuration
echo "Database configuration:"
echo "DATABASE_URL: ${DATABASE_URL:0:50}..." 
echo "DB_CONNECTION: ${DB_CONNECTION:-not-set}"
echo "DB_HOST: ${DB_HOST:-not-set}"
echo "DB_PORT: ${DB_PORT:-not-set}"
echo "DB_DATABASE: ${DB_DATABASE:-not-set}"

# Parse DATABASE_URL for Render
if [ -n "$DATABASE_URL" ]; then
    echo "Parsing DATABASE_URL..."
    if [[ "$DATABASE_URL" == postgres* ]]; then
        export DB_CONNECTION=pgsql
        echo "✅ Detected PostgreSQL database"
    else
        export DB_CONNECTION=mysql
        echo "✅ Detected MySQL database"
    fi
    
    # Extract database components
    export DB_HOST=$(echo $DATABASE_URL | sed -n 's/.*@\([^:]*\):.*/\1/p')
    export DB_PORT=$(echo $DATABASE_URL | sed -n 's/.*:\([0-9]*\)\/.*/\1/p')
    export DB_DATABASE=$(echo $DATABASE_URL | sed -n 's/.*\/\([^?]*\).*/\1/p')
    export DB_USERNAME=$(echo $DATABASE_URL | sed -n 's/.*:\/\/\([^:]*\):.*/\1/p')
    export DB_PASSWORD=$(echo $DATABASE_URL | sed -n 's/.*:\/\/[^:]*:\([^@]*\)@.*/\1/p')
    
    echo "Parsed database config:"
    echo "DB_CONNECTION: $DB_CONNECTION"
    echo "DB_HOST: $DB_HOST"
    echo "DB_PORT: $DB_PORT"
    echo "DB_DATABASE: $DB_DATABASE"
    echo "DB_USERNAME: $DB_USERNAME"
fi

# Clear any cached config to use environment variables
echo "Clearing application caches..."
php artisan config:clear --no-interaction 2>&1 || echo "⚠️ Config clear skipped"
php artisan cache:clear --no-interaction 2>&1 || echo "⚠️ Cache clear skipped"
php artisan view:clear --no-interaction 2>&1 || echo "⚠️ View clear skipped"
php artisan route:clear --no-interaction 2>&1 || echo "⚠️ Route clear skipped"

# Check APP_KEY
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "⚠️ APP_KEY not set - generating one..."
    php artisan key:generate --force --no-interaction || echo "❌ Key generation failed"
else
    echo "✅ APP_KEY is set"
fi

# Test database connection
echo "Testing database connection..."
CONNECTION_TEST=$(php artisan migrate:status --no-interaction 2>&1)
if echo "$CONNECTION_TEST" | grep -q "could not translate host name\|could not find driver\|Connection refused"; then
    echo "❌ CRITICAL: Database connection failed"
    echo "Error details: $CONNECTION_TEST"
    echo ""
    echo "🔧 TROUBLESHOOTING STEPS:"
    echo "1. Go to Render Dashboard: https://dashboard.render.com"
    echo "2. Verify PostgreSQL database 'studeats-db' is created and active"
    echo "3. Check that DATABASE_URL is automatically set from database connection"
    echo "4. If database doesn't exist, create one with name 'studeats-db'"
    echo "5. Redeploy the web service after database is ready"
    echo ""
    echo "⚠️ Starting server anyway (will fail on database operations)"
elif php artisan migrate:status --no-interaction 2>&1; then
    echo "✅ Database connection successful"
    
    # Run database migrations
    echo "Running database migrations..."
    if php artisan migrate --force --no-interaction 2>&1; then
        echo "✅ Migrations completed"
    else
        echo "⚠️ Migrations failed - continuing anyway"
    fi
else
    echo "⚠️ Database connection status unclear - continuing"
fi

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --no-interaction 2>&1 || echo "Storage link already exists"

# Cache configuration for production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration for production..."
    php artisan config:cache --no-interaction 2>&1 || echo "Config cache failed, continuing..."
    php artisan route:cache --no-interaction 2>&1 || echo "Route cache failed, continuing..."
    php artisan view:cache --no-interaction 2>&1 || echo "View cache failed, continuing..."
fi

# Final check before starting server
echo "Final environment check:"
echo "APP_ENV: ${APP_ENV:-not-set}"
echo "APP_DEBUG: ${APP_DEBUG:-not-set}"
echo "APP_KEY present: $([ -n "$APP_KEY" ] && echo "✅ Yes" || echo "❌ No")"
echo "CACHE_STORE: ${CACHE_STORE:-not-set}"
echo "SESSION_DRIVER: ${SESSION_DRIVER:-not-set}"

# Create storage directories if they don't exist
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
chmod -R 755 storage

echo "=== Starting Laravel Application ==="
echo "Application will be available on: http://0.0.0.0:${PORT:-8000}"

# Start background queue worker for email processing
echo "Starting background queue worker..."
nohup php artisan queue:work --daemon --timeout=60 --memory=128 --tries=3 --delay=3 > /tmp/queue.log 2>&1 &
QUEUE_PID=$!
echo "Queue worker started with PID: $QUEUE_PID"

# Create a simple queue status check
nohup bash -c '
while true; do
    if ! kill -0 $1 2>/dev/null; then
        echo "Queue worker died, restarting..."
        php artisan queue:work --daemon --timeout=60 --memory=128 --tries=3 --delay=3 > /tmp/queue.log 2>&1 &
        QUEUE_PID=$!
        echo "Queue worker restarted with PID: $QUEUE_PID"
    fi
    sleep 30
done
' -- $QUEUE_PID &

# Enable error exit now that we're starting the server
set -e

# Start the Laravel development server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000} --no-interaction