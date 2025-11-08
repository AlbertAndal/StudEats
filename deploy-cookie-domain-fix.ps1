# Quick Deploy Script for Cookie Domain Fix (PowerShell)
# Commits changes and pushes to remote for Laravel Cloud deployment

Write-Host "`n🚀 StudEats Cookie Domain Fix - Quick Deploy" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""

# Check if there are changes to commit
$gitStatus = git status -s
if ([string]::IsNullOrWhiteSpace($gitStatus))
{
    Write-Host "✓ No changes to commit. Already up to date!" -ForegroundColor Green
    exit 0
}

Write-Host "📋 Changes to be committed:" -ForegroundColor Yellow
git status -s
Write-Host ""

# Add all changes
Write-Host "📦 Staging changes..." -ForegroundColor White
git add -A

# Commit with descriptive message
Write-Host "💾 Committing changes..." -ForegroundColor White
$commitMessage = @"
Fix: Implement cookie domain auto-detection for Laravel Cloud

- Remove SESSION_DOMAIN from laravel-cloud.json to enable auto-detection
- Update .env.example with comprehensive session configuration guidance
- Create COOKIE-SESSION-GUIDE.md consolidating all cookie/session docs
- Add verification scripts (verify-cookie-fix.sh, verify-cookie-fix.ps1)
- Document deprecated files in .github/DEPRECATED-DOCS.md

This resolves 419 CSRF Token Mismatch errors by allowing Laravel to
automatically detect the request host instead of using an explicit
.laravel.cloud domain that browsers reject due to PSL rules.

Fixes: Cookie rejection errors, 419 CSRF errors, login failures
See: COOKIE-SESSION-GUIDE.md for complete documentation
"@

git commit -m $commitMessage

Write-Host ""
Write-Host "✓ Changes committed successfully!" -ForegroundColor Green
Write-Host ""

# Push to remote
Write-Host "🌐 Pushing to origin/main..." -ForegroundColor White
git push origin main

Write-Host ""
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host "✅ Deployment initiated!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Monitor Laravel Cloud dashboard for deployment progress" -ForegroundColor White
Write-Host "2. After deployment completes, clear browser cookies" -ForegroundColor White
Write-Host "3. Test login at https://studeats.laravel.cloud" -ForegroundColor White
Write-Host "4. Verify no 419 errors in logs" -ForegroundColor White
Write-Host ""
Write-Host "See COOKIE-DOMAIN-FIX-SUMMARY.md for post-deployment verification." -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""
