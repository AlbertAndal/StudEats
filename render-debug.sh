#!/bin/bash

echo "=== StudEats Render Troubleshooting ==="

# Check PHP
echo "1. PHP Check:"
php --version || echo "❌ PHP not available"

# Check Laravel
echo -e "\n2. Laravel Check:"
php artisan --version 2>&1 || echo "❌ Laravel not accessible"

# Check database
echo -e "\n3. Database Check:"
echo "DATABASE_URL present: $([ -n "$DATABASE_URL" ] && echo "✅ Yes" || echo "❌ No")"
if [ -n "$DATABASE_URL" ]; then
    if [[ "$DATABASE_URL" == postgres* ]]; then
        echo "Database type: PostgreSQL"
    elif [[ "$DATABASE_URL" == mysql* ]]; then
        echo "Database type: MySQL"
    else
        echo "Database type: Unknown"
    fi
fi

# Check environment variables
echo -e "\n4. Environment Variables:"
echo "APP_KEY: $([ -n "$APP_KEY" ] && echo "✅ Set" || echo "❌ Missing")"
echo "APP_ENV: ${APP_ENV:-❌ Not set}"
echo "CACHE_STORE: ${CACHE_STORE:-❌ Not set}"
echo "SESSION_DRIVER: ${SESSION_DRIVER:-❌ Not set}"
echo "PORT: ${PORT:-❌ Not set}"

# Check file structure
echo -e "\n5. File Structure:"
echo "Current directory: $(pwd)"
echo "Key files present:"
echo "- .env: $([ -f .env ] && echo "✅ Yes" || echo "❌ No")"
echo "- composer.json: $([ -f composer.json ] && echo "✅ Yes" || echo "❌ No")"
echo "- artisan: $([ -f artisan ] && echo "✅ Yes" || echo "❌ No")"
echo "- public/index.php: $([ -f public/index.php ] && echo "✅ Yes" || echo "❌ No")"

# Check vendor directory
echo "- vendor/: $([ -d vendor ] && echo "✅ Yes" || echo "❌ No")"
if [ -d vendor ]; then
    echo "  - autoload.php: $([ -f vendor/autoload.php ] && echo "✅ Yes" || echo "❌ No")"
fi

# Check storage permissions
echo -e "\n6. Storage Permissions:"
if [ -d storage ]; then
    echo "Storage directory: ✅ Exists"
    echo "Storage permissions: $(ls -ld storage | cut -d' ' -f1)"
    echo "Storage writable: $([ -w storage ] && echo "✅ Yes" || echo "❌ No")"
else
    echo "Storage directory: ❌ Missing"
fi

# Try basic Laravel commands
echo -e "\n7. Laravel Commands Test:"
php artisan config:clear --no-interaction 2>&1 | head -2
php artisan route:list --compact 2>&1 | head -3 || echo "Route list failed"

echo -e "\n=== End Troubleshooting ==="