#!/bin/bash

# Exit on any error
set -e

echo "Starting StudEats application setup..."

# Wait for database to be ready
echo "Waiting for database connection..."
while ! php artisan migrate:status &> /dev/null; do
    echo "Database not ready, waiting..."
    sleep 2
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
    php artisan config:cache --no-interaction
    php artisan route:cache --no-interaction
    php artisan view:cache --no-interaction
fi

echo "StudEats application setup completed!"

# Execute the main command
exec "$@"