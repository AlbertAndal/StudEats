#!/bin/bash

# Don't exit on errors during build - we want to see what fails
set +e

echo "=== Render Build Script for StudEats ==="

# Show build environment
echo "Build environment:"
echo "PHP Version: $(php --version | head -n 1)"
echo "Node Version: $(node --version 2>&1 || echo 'Node not available')"
echo "NPM Version: $(npm --version 2>&1 || echo 'NPM not available')"
echo "Composer Version: $(composer --version 2>&1 || echo 'Composer not available')"

# Install PHP dependencies
echo "Installing Composer dependencies..."
if composer install --no-dev --optimize-autoloader --no-interaction; then
    echo "✅ Composer install successful"
else
    echo "❌ Composer install failed"
    exit 1
fi

# Check if package.json exists
if [ -f "package.json" ]; then
    # Install Node.js dependencies
    echo "Installing NPM dependencies..."
    if npm ci --include=dev; then
        echo "✅ NPM install successful"
        
        # Build frontend assets
        echo "Building frontend assets..."
        if npm run build; then
            echo "✅ Frontend build successful"
            
            # Clean up node_modules to save space
            echo "Cleaning up build dependencies..."
            rm -rf node_modules
            npm cache clean --force || echo "⚠️ NPM cache clean failed"
        else
            echo "❌ Frontend build failed"
            echo "Continuing without frontend assets..."
        fi
    else
        echo "❌ NPM install failed"
        echo "Continuing without frontend assets..."
    fi
else
    echo "⚠️ No package.json found, skipping frontend build"
fi

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

# Check if key files exist
echo "Checking Laravel installation:"
echo "- artisan: $([ -f artisan ] && echo "✅ Present" || echo "❌ Missing")"
echo "- public/index.php: $([ -f public/index.php ] && echo "✅ Present" || echo "❌ Missing")"
echo "- vendor/autoload.php: $([ -f vendor/autoload.php ] && echo "✅ Present" || echo "❌ Missing")"

echo "=== Build completed ==="