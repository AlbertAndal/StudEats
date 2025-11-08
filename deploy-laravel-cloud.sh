#!/bin/bash
# Laravel Cloud Deployment Script
echo "=== Laravel Cloud Deployment ==="

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Seed PDRI reference data (ignore if already exists)
echo "Seeding PDRI reference data..."
php artisan db:seed --class=PdriReferenceSeeder --force || echo "PDRI data already exists or seeding skipped"

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force || echo "Storage link already exists"

# Reset admin password using seeder (will update if exists)
echo "Setting up admin account..."
php artisan db:seed --class=AdminSeeder --force || echo "Admin setup skipped"

# Clear and cache config
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Deployment Complete ==="
echo "Admin credentials: admin@studeats.com / admin123"
