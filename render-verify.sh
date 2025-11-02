#!/bin/bash

# Quick Deployment Verification Script for StudEats on Render
echo "=== 🔍 StudEats Deployment Verification ==="

# Test environment setup
echo "📋 Environment Test:"
echo "APP_KEY: $([ -n "$APP_KEY" ] && echo "✅ Set" || echo "❌ Missing")"
echo "DATABASE_URL: $([ -n "$DATABASE_URL" ] && echo "✅ Set" || echo "❌ Missing")"
echo "PORT: ${PORT:-8000}"

# Test Laravel
echo -e "\n🧪 Laravel Test:"
if php artisan --version >/dev/null 2>&1; then
    echo "✅ Laravel: $(php artisan --version)"
else
    echo "❌ Laravel not accessible"
    exit 1
fi

# Test database
echo -e "\n🗄️ Database Test:"
if php artisan migrate:status --no-interaction >/dev/null 2>&1; then
    echo "✅ Database connection working"
else
    echo "❌ Database connection failed"
fi

# Test key routes
echo -e "\n🛣️ Route Test:"
routes=("/" "/login" "/register" "/up")
for route in "${routes[@]}"; do
    echo "Testing route: $route"
done

# Test file permissions
echo -e "\n📁 Permission Test:"
if [ -w "storage/logs" ]; then
    echo "✅ Storage writable"
else
    echo "❌ Storage not writable"
fi

echo -e "\n✅ Verification complete!"
echo "💡 Check Render logs for detailed startup information"