#!/bin/bash
# Laravel Cloud Custom Deploy Script
# Place this in your repository root and configure Laravel Cloud to use it

echo "🚀 StudEats Laravel Cloud Deployment"
echo "===================================="

# Function to run commands safely
safe_artisan() {
    echo "Running: php artisan $1"
    if php artisan $1; then
        echo "✅ Success: $1"
    else
        echo "⚠️ Failed: $1 (continuing...)"
    fi
}

# Step 1: Environment verification
echo ""
echo "📋 Environment Verification"
echo "---------------------------"
echo "PHP Version: $(php --version | head -n 1)"
echo "Laravel Version: $(php artisan --version)"
echo "App Environment: $(php artisan tinker --execute='echo config("app.env");' 2>/dev/null || echo 'production')"

# Step 2: Clear caches safely
echo ""
echo "🧹 Clearing Application Caches"
echo "------------------------------"
safe_artisan "config:clear"
safe_artisan "cache:clear" 
safe_artisan "route:clear"

# Handle view cache specially
echo "Clearing view cache..."
if [ -d "$(php artisan tinker --execute='echo config("view.compiled");' 2>/dev/null)" ]; then
    safe_artisan "view:clear"
else
    echo "Creating view cache directory..."
    mkdir -p "$(php artisan tinker --execute='echo config("view.compiled");' 2>/dev/null)"
    chmod 755 "$(php artisan tinker --execute='echo config("view.compiled");' 2>/dev/null)"
    echo "✅ View cache directory created"
fi

# Step 3: Ensure storage structure
echo ""
echo "📁 Storage Structure Setup"
echo "-------------------------"
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p storage/app/public
chmod -R 755 storage/
echo "✅ Storage directories verified"

# Step 4: Database operations
echo ""
echo "🗄️ Database Operations"
echo "---------------------"
echo "Testing database connection..."
if php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database OK'; } catch(Exception \$e) { echo 'Database Error: ' . \$e->getMessage(); exit(1); }" 2>/dev/null; then
    echo "✅ Database connection successful"
    
    echo "Running migrations..."
    safe_artisan "migrate --force --no-interaction"
    
    echo "Seeding essential data..."
    safe_artisan "db:seed --class=AdminSeeder --force"
    safe_artisan "db:seed --class=PdriReferenceSeeder --force"
else
    echo "❌ Database connection failed - check environment variables"
    exit 1
fi

# Step 5: Storage link
echo ""
echo "🔗 Storage Link"
echo "---------------"
safe_artisan "storage:link --force"

# Step 6: Production optimization
echo ""
echo "⚡ Production Optimization"
echo "------------------------"
safe_artisan "config:cache"
safe_artisan "route:cache"

# Only cache views if directory exists and is writable
if [ -w "$(php artisan tinker --execute='echo config("view.compiled");' 2>/dev/null)" ]; then
    safe_artisan "view:cache"
else
    echo "⚠️ Skipping view cache - directory not writable"
fi

# Step 7: Health check
echo ""
echo "🏥 Health Check"
echo "---------------"
echo "Checking critical components..."

# Check session configuration for 419 error fix
SESSION_DOMAIN=$(php artisan tinker --execute='echo config("session.domain") ?: "null";' 2>/dev/null)
SESSION_SAME_SITE=$(php artisan tinker --execute='echo config("session.same_site") ?: "null";' 2>/dev/null)
echo "Session Domain: $SESSION_DOMAIN"
echo "Session SameSite: $SESSION_SAME_SITE"

if [ "$SESSION_DOMAIN" = ".laravel.cloud" ] && [ "$SESSION_SAME_SITE" = "Lax" ]; then
    echo "✅ Session configuration correct for Laravel Cloud"
else
    echo "⚠️ Session configuration may need adjustment"
    echo "   Required: SESSION_DOMAIN=.laravel.cloud, SESSION_SAME_SITE=Lax"
fi

# Check key application components
USERS=$(php artisan tinker --execute='echo App\Models\User::count();' 2>/dev/null || echo "0")
RECIPES=$(php artisan tinker --execute='echo App\Models\Recipe::count();' 2>/dev/null || echo "0")
echo "Users in database: $USERS"
echo "Recipes in database: $RECIPES"

# Step 8: Final status
echo ""
echo "✅ DEPLOYMENT COMPLETE"
echo "====================="
echo ""
echo "🌐 Application URL: https://studeats.laravel.cloud"
echo "🔧 Admin Login: https://studeats.laravel.cloud/admin/login"
echo ""
echo "📧 Default Admin Credentials:"
echo "   Email: admin@studeats.com"
echo "   Password: admin123"
echo ""
echo "⚠️ IMPORTANT REMINDERS:"
echo "   1. Change admin password immediately after first login"
echo "   2. Verify session environment variables if 419 errors occur"
echo "   3. Monitor application logs for any issues"
echo ""
echo "🎉 StudEats is ready for use!"