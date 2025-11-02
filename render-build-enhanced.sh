#!/bin/bash

# Enhanced Render Build Script for StudEats with Better Error Handling
set -e  # Exit on any error during build

echo "=== 🚀 StudEats Render Build Script v2.0 ==="
echo "Timestamp: $(date)"

# Function to log with timestamp
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

# Show detailed build environment
log "📋 Build Environment Information:"
echo "Node Version: $(node --version 2>&1 || echo '❌ Node not available')"
echo "NPM Version: $(npm --version 2>&1 || echo '❌ NPM not available')"
echo "PHP Version: $(php --version | head -n 1 2>&1 || echo '❌ PHP not available')"
echo "Composer Version: $(composer --version 2>&1 || echo '❌ Composer not available')"
echo "Working Directory: $(pwd)"
echo "Available Memory: $(free -h 2>&1 | grep Mem || echo 'Memory info not available')"

# Check critical files
log "🔍 Checking Critical Files:"
critical_files=("composer.json" "package.json" "artisan" "public/index.php")
for file in "${critical_files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file exists"
    else
        echo "❌ $file missing - BUILD WILL FAIL"
        exit 1
    fi
done

# Install PHP dependencies with better error handling
log "📦 Installing Composer Dependencies..."
if composer install --no-dev --optimize-autoloader --no-interaction --verbose; then
    log "✅ Composer dependencies installed successfully"
else
    log "❌ Composer install failed"
    echo "Available memory:"
    free -h 2>/dev/null || echo "Memory info unavailable"
    echo "Composer version:"
    composer --version
    exit 1
fi

# Verify vendor directory
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    log "❌ Vendor directory or autoload.php missing after composer install"
    exit 1
fi

# Install Node.js dependencies and build frontend
if [ -f "package.json" ]; then
    log "🎨 Installing NPM Dependencies..."
    
    # Clear npm cache first
    npm cache clean --force 2>/dev/null || true
    
    # Install with retries
    retry_count=0
    max_retries=3
    
    while [ $retry_count -lt $max_retries ]; do
        if npm ci --include=dev --verbose; then
            log "✅ NPM dependencies installed successfully"
            break
        else
            retry_count=$((retry_count + 1))
            log "⚠️ NPM install attempt $retry_count failed"
            if [ $retry_count -lt $max_retries ]; then
                log "🔄 Retrying NPM install..."
                sleep 5
            else
                log "❌ NPM install failed after $max_retries attempts"
                exit 1
            fi
        fi
    done
    
    # Build frontend assets
    log "🏗️ Building Frontend Assets..."
    if npm run build; then
        log "✅ Frontend assets built successfully"
        
        # Verify build output
        if [ -d "public/build" ]; then
            log "✅ Build directory created: public/build"
            ls -la public/build/ | head -5
        else
            log "⚠️ No public/build directory found after build"
        fi
        
        # Clean up node_modules to save space (important for free tier)
        log "🧹 Cleaning up build dependencies..."
        rm -rf node_modules
        npm cache clean --force 2>/dev/null || true
        log "✅ Build dependencies cleaned up"
    else
        log "❌ Frontend build failed"
        echo "Build logs:"
        cat npm-debug.log 2>/dev/null || echo "No npm debug log available"
        exit 1
    fi
else
    log "⚠️ No package.json found, skipping frontend build"
fi

# Create and set up Laravel directories with proper permissions
log "📁 Setting up Laravel Directories..."
directories=(
    "storage/framework/cache/data"
    "storage/framework/sessions"
    "storage/framework/views" 
    "storage/logs"
    "storage/app/public"
    "bootstrap/cache"
)

for dir in "${directories[@]}"; do
    mkdir -p "$dir"
    if [ -d "$dir" ]; then
        echo "✅ Created: $dir"
    else
        echo "❌ Failed to create: $dir"
        exit 1
    fi
done

# Set proper permissions
log "🔒 Setting Directory Permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework

# Verify Laravel installation integrity
log "🔍 Verifying Laravel Installation:"
checks=(
    "artisan:Laravel artisan command"
    "public/index.php:Public entry point"
    "vendor/autoload.php:Composer autoloader"
    "app/Console/Kernel.php:Console kernel"
    "app/Http/Kernel.php:HTTP kernel"
)

for check in "${checks[@]}"; do
    file="${check%%:*}"
    description="${check##*:}"
    if [ -f "$file" ]; then
        echo "✅ $description: $file"
    else
        echo "❌ Missing $description: $file"
        exit 1
    fi
done

# Test PHP syntax
log "🧪 Testing PHP Syntax..."
if php -l artisan >/dev/null 2>&1; then
    log "✅ PHP syntax check passed"
else
    log "❌ PHP syntax errors detected"
    php -l artisan
    exit 1
fi

# Display build summary
log "📊 Build Summary:"
echo "Storage directory size: $(du -sh storage 2>/dev/null || echo 'Unknown')"
echo "Vendor directory size: $(du -sh vendor 2>/dev/null || echo 'Unknown')"
echo "Public directory contents:"
ls -la public/ | head -10

# Final verification
log "✅ Build completed successfully!"
log "📋 Build artifacts ready for deployment"

echo "=== 🎉 StudEats Build Complete ==="