#!/bin/bash

echo "=== StudEats Railway Startup ==="
echo "Port: ${PORT:-8000}"
echo "Environment: ${APP_ENV:-production}"

# Check if .env exists
if [ -f .env ]; then
    echo "✓ .env file found"
else
    echo "✗ .env file NOT found - creating minimal one"
    echo "APP_KEY=" > .env
fi

# Clear any cached config to use Railway environment variables
echo "Clearing config cache..."
php artisan config:clear 2>&1 | head -5 || echo "Config clear skipped"

# Show Laravel version
echo "Laravel version:"
php artisan --version 2>&1 || echo "Could not get version"

# Start the server
echo "Starting server on 0.0.0.0:${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
