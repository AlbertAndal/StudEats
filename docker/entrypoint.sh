#!/bin/bash

# Exit on any error
set -e

echo "Starting StudEats application setup..."

# Parse DATABASE_URL if available (Render PostgreSQL format)
if [ -n "$DATABASE_URL" ]; then
    echo "DATABASE_URL is set, Laravel will use it directly"
    # Don't override - let Laravel's database config use the DATABASE_URL directly
    # This is more reliable than parsing it manually
fi

# Wait for database to be ready with timeout
echo "Waiting for database connection..."
TIMEOUT=15
COUNTER=0
until php artisan migrate:status &> /dev/null || [ $COUNTER -ge $TIMEOUT ]; do
    echo "Database not ready, waiting... ($COUNTER/$TIMEOUT)"
    sleep 1
    COUNTER=$((COUNTER + 1))
done

if [ $COUNTER -ge $TIMEOUT ]; then
    echo "Database connection timeout after ${TIMEOUT} seconds, continuing anyway..."
else
    echo "Database connected successfully"
fi

# Handle .env file for Railway deployment
if [ -n "$DATABASE_URL" ]; then
    # Railway deployment - environment variables come from Railway
    echo "Railway deployment detected - using environment variables"
    if [ ! -f .env ]; then
        echo "Creating minimal .env file for Railway..."
        echo "APP_ENV=production" > .env
        echo "APP_DEBUG=false" >> .env
        echo "LOG_CHANNEL=errorlog" >> .env
    fi
else
    # Local or other deployment
    if [ ! -f .env ]; then
        echo "Creating .env file from example..."
        cp .env.example .env
    fi
    
    # Generate app key if not set
    if ! grep -q "APP_KEY=base64:" .env; then
        echo "Generating application key..."
        php artisan key:generate --no-interaction
    fi
fi

# Clear caches
echo "Clearing application caches..."
php artisan config:clear --no-interaction

# Run database migrations BEFORE cache operations
echo "Running database migrations..."
php artisan migrate --force --no-interaction || {
    echo "Migration failed, but continuing..."
    echo "This might be expected if this is the first deployment"
}

# Now clear other caches after migrations
php artisan cache:clear --no-interaction || echo "Cache clear failed, continuing..."
php artisan view:clear --no-interaction
php artisan route:clear --no-interaction

# Create storage link
echo "Creating storage link..."
php artisan storage:link --no-interaction || true

# Cache configuration for production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration for production..."
    php artisan config:cache --no-interaction || echo "Config cache failed, continuing..."
    php artisan route:cache --no-interaction || echo "Route cache failed, continuing..."
    php artisan view:cache --no-interaction || echo "View cache failed, continuing..."
fi

# Start queue worker in background if in production
if [ "$APP_ENV" = "production" ] && [ -n "$DATABASE_URL" ]; then
    echo "Starting queue worker in background..."
    php artisan queue:work --daemon --tries=3 --timeout=60 &
fi

echo "StudEats application setup completed!"

# Start the Laravel server on the assigned port
PORT=${PORT:-8000}
echo "Starting Laravel server on 0.0.0.0:$PORT"
echo "Environment: ${APP_ENV:-production}"
echo "Debug mode: ${APP_DEBUG:-false}"

# Show some debug info
php --version
php artisan --version 2>&1 || echo "Laravel not ready"

exec php artisan serve --host=0.0.0.0 --port=$PORT --no-interaction