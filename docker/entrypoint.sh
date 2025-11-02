#!/bin/bash

# Exit on any error
set -e

echo "Starting StudEats application setup..."

# Parse DATABASE_URL if available (Railway format)
if [ -n "$DATABASE_URL" ]; then
    echo "Parsing DATABASE_URL for Railway deployment..."
    # Extract components from DATABASE_URL
    # Format: mysql://username:password@host:port/database
    export DB_CONNECTION=mysql
    export DB_HOST=$(echo $DATABASE_URL | sed -n 's/.*@\([^:]*\):.*/\1/p')
    export DB_PORT=$(echo $DATABASE_URL | sed -n 's/.*:\([0-9]*\)\/.*/\1/p')
    export DB_DATABASE=$(echo $DATABASE_URL | sed -n 's/.*\/\(.*\)/\1/p')
    export DB_USERNAME=$(echo $DATABASE_URL | sed -n 's/.*:\/\/\([^:]*\):.*/\1/p')
    export DB_PASSWORD=$(echo $DATABASE_URL | sed -n 's/.*:\/\/[^:]*:\([^@]*\)@.*/\1/p')
    echo "Database configuration parsed from DATABASE_URL"
fi

# Wait for database to be ready with timeout
echo "Waiting for database connection..."
TIMEOUT=30
COUNTER=0
while ! php artisan migrate:status &> /dev/null; do
    if [ $COUNTER -ge $TIMEOUT ]; then
        echo "Database connection timeout after ${TIMEOUT} seconds, continuing anyway..."
        break
    fi
    echo "Database not ready, waiting... ($COUNTER/$TIMEOUT)"
    sleep 2
    COUNTER=$((COUNTER + 2))
done

# Generate application key if not exists
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate --no-interaction
fi

# Clear caches
echo "Clearing application caches..."
php artisan config:clear --no-interaction
php artisan cache:clear --no-interaction
php artisan view:clear --no-interaction
php artisan route:clear --no-interaction

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force --no-interaction

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

# Execute the main command
exec "$@"