#!/bin/bash

set -e

echo "=== StudEats Render Startup ==="

# Show environment info
echo "PHP Version: $(php --version | head -n 1)"
echo "Laravel Version: $(php artisan --version 2>&1 || echo 'Laravel not ready')"
echo "Environment: ${APP_ENV:-production}"
echo "Port: ${PORT:-8000}"

# Clear any cached config to use environment variables
echo "Clearing application caches..."
php artisan config:clear --no-interaction 2>&1 || echo "Config clear skipped"
php artisan cache:clear --no-interaction 2>&1 || echo "Cache clear skipped"
php artisan view:clear --no-interaction 2>&1 || echo "View clear skipped"
php artisan route:clear --no-interaction 2>&1 || echo "Route clear skipped"

# Generate app key if needed (though should be set in environment)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating application key..."
    php artisan key:generate --force --no-interaction
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force --no-interaction 2>&1 || {
    echo "Migration failed - this might be expected on first deployment"
    echo "Check your database connection settings in Render dashboard"
}

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

echo "=== Starting Laravel Application ==="
echo "Application will be available on: http://0.0.0.0:${PORT:-8000}"

# Start the Laravel development server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000} --no-interaction