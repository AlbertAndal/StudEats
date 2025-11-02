#!/bin/bash

set -e

echo "=== Render Build Script for StudEats ==="

# Install PHP dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node.js dependencies
echo "Installing NPM dependencies..."
npm ci --include=dev

# Build frontend assets
echo "Building frontend assets..."
npm run build

# Clean up node_modules to save space
echo "Cleaning up build dependencies..."
rm -rf node_modules
npm cache clean --force

# Create necessary Laravel directories
echo "Setting up Laravel directories..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache

echo "=== Build completed successfully ==="