# Cookie Domain Fix - Quick Deploy (PowerShell)
# Run this script to deploy the cookie domain fix to Laravel Cloud

Write-Host "🔧 Cookie Domain Fix - Laravel Cloud Deployment" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Check if we're in the right directory
if (-not (Test-Path "laravel-cloud.json")) {
    Write-Host "❌ Error: laravel-cloud.json not found. Run this script from the project root." -ForegroundColor Red
    exit 1
}

# Show what will be deployed
Write-Host "📋 Changes to deploy:" -ForegroundColor Yellow
Write-Host "  - SESSION_DOMAIN: null → .laravel.cloud"
Write-Host "  - Enhanced post-deploy cache clearing"
Write-Host ""

# Confirm deployment
$confirmation = Read-Host "🚀 Ready to commit and push? (y/N)"
if ($confirmation -ne 'y' -and $confirmation -ne 'Y') {
    Write-Host "❌ Deployment cancelled." -ForegroundColor Red
    exit 0
}

Write-Host ""
Write-Host "📦 Staging changes..." -ForegroundColor Green
git add laravel-cloud.json post-deploy-laravel-cloud.sh COOKIE-DOMAIN-DEPLOYMENT.md

Write-Host "💬 Creating commit..." -ForegroundColor Green
git commit -m "Fix: Set SESSION_DOMAIN to .laravel.cloud for cookie compatibility

- Update laravel-cloud.json: SESSION_DOMAIN null → .laravel.cloud
- Enhanced post-deploy script with config cache refresh
- Fixes cookie rejection errors on Laravel Cloud deployment
- Resolves: XSRF-TOKEN, studeats-session invalid domain errors"

Write-Host "🚀 Pushing to Laravel Cloud..." -ForegroundColor Green
git push origin main

Write-Host ""
Write-Host "✅ Deployment initiated!" -ForegroundColor Green
Write-Host ""
Write-Host "📊 Next steps:" -ForegroundColor Cyan
Write-Host "  1. Monitor deployment: Laravel Cloud Dashboard"
Write-Host "  2. Set environment variables in dashboard (if not already set):"
Write-Host "     SESSION_DOMAIN=.laravel.cloud (NO quotes)" -ForegroundColor Yellow
Write-Host "  3. After deployment, verify:"
Write-Host "     https://studeats.laravel.cloud/debug-cookies"
Write-Host "  4. Test login:"
Write-Host "     https://studeats.laravel.cloud/admin/login"
Write-Host ""
Write-Host "📖 Full guide: COOKIE-DOMAIN-DEPLOYMENT.md" -ForegroundColor Cyan
Write-Host ""
