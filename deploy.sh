#!/bin/bash

# Laravel deployment script for Laravel Cloud
echo "Starting Laravel Cloud deployment..."

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

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Seed PDRI reference data (ignore if already exists)
echo "Seeding PDRI reference data..."
php artisan db:seed --class=PdriReferenceSeeder --force || echo "PDRI data already exists"

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force || echo "Storage link already exists"

# Set up admin account
echo "Setting up admin account..."
php artisan db:seed --class=AdminSeeder --force || echo "Admin setup skipped"

# Cache for production
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Laravel Cloud deployment complete!"
echo "Admin credentials: admin@studeats.com / admin123"