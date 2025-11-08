#!/bin/bash

# Railway deployment script for Laravel
echo "Starting Railway deployment..."

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci

# Build assets
npm run build

# Clear and cache Laravel
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations (be careful with this in production)
php artisan migrate --force

# Seed PDRI reference data (ignore if already exists)
php artisan db:seed --class=PdriReferenceSeeder --force || echo "PDRI data already exists"

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
php artisan storage:link

echo "Deployment complete!"