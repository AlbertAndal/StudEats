#!/bin/bash

# Database Deployment Script for Laravel Cloud
# This script runs all pending migrations and seeds the PDRI reference data

echo "🚀 Starting database deployment..."

# Run migrations
echo "📦 Running database migrations..."
php artisan migrate --force

# Seed PDRI reference data
echo "🌱 Seeding PDRI reference data..."
php artisan db:seed --class=PdriReferenceSeeder --force

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "✅ Database deployment completed successfully!"
