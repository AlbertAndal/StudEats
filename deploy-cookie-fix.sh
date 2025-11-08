#!/bin/bash
# Quick deployment script for cookie domain fix

echo "🔧 Cookie Domain Fix - Laravel Cloud Deployment"
echo "================================================"
echo ""

# Check if we're in the right directory
if [ ! -f "laravel-cloud.json" ]; then
    echo "❌ Error: laravel-cloud.json not found. Run this script from the project root."
    exit 1
fi

# Show what will be deployed
echo "📋 Changes to deploy:"
echo "  - SESSION_DOMAIN: null → .laravel.cloud"
echo "  - Enhanced post-deploy cache clearing"
echo ""

# Confirm deployment
read -p "🚀 Ready to commit and push? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Deployment cancelled."
    exit 1
fi

echo ""
echo "📦 Staging changes..."
git add laravel-cloud.json post-deploy-laravel-cloud.sh

echo "💬 Creating commit..."
git commit -m "Fix: Set SESSION_DOMAIN to .laravel.cloud for cookie compatibility

- Update laravel-cloud.json: SESSION_DOMAIN null → .laravel.cloud
- Enhanced post-deploy script with config cache refresh
- Fixes cookie rejection errors on Laravel Cloud deployment
- Resolves: XSRF-TOKEN, studeats-session invalid domain errors"

echo "🚀 Pushing to Laravel Cloud..."
git push origin main

echo ""
echo "✅ Deployment initiated!"
echo ""
echo "📊 Next steps:"
echo "  1. Monitor deployment: Laravel Cloud Dashboard"
echo "  2. Set environment variables in dashboard (if not already set):"
echo "     SESSION_DOMAIN=.laravel.cloud (NO quotes)"
echo "  3. After deployment, verify:"
echo "     https://studeats.laravel.cloud/debug-cookies"
echo "  4. Test login:"
echo "     https://studeats.laravel.cloud/admin/login"
echo ""
echo "📖 Full guide: COOKIE-DOMAIN-DEPLOYMENT.md"
echo ""
