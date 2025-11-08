# StudEats Cookie Domain Fix - Pre-Deployment Verification
# PowerShell version for Windows environments

Write-Host "`n🔍 StudEats Cookie Domain Fix - Pre-Deployment Verification" -ForegroundColor Cyan
Write-Host "============================================================`n" -ForegroundColor Cyan

$ErrorCount = 0
$WarningCount = 0

# Check 1: laravel-cloud.json should NOT have SESSION_DOMAIN
Write-Host "✓ Checking laravel-cloud.json..." -ForegroundColor White
$laravelCloudContent = Get-Content "laravel-cloud.json" -Raw
if ($laravelCloudContent -match '"SESSION_DOMAIN"')
{
    Write-Host "✗ FAIL: SESSION_DOMAIN found in laravel-cloud.json" -ForegroundColor Red
    Write-Host "  Expected: SESSION_DOMAIN should be removed" -ForegroundColor Yellow
    Write-Host "  Action: Remove SESSION_DOMAIN from environment section" -ForegroundColor Yellow
    $ErrorCount++
}
else
{
    Write-Host "✓ PASS: SESSION_DOMAIN not in laravel-cloud.json" -ForegroundColor Green
}

# Check 2: config/session.php should have null fallback
Write-Host "✓ Checking config/session.php..." -ForegroundColor White
$sessionConfigContent = Get-Content "config\session.php" -Raw
if ($sessionConfigContent -match "env\('SESSION_DOMAIN'\)")
{
    Write-Host "✓ PASS: config/session.php has SESSION_DOMAIN handling" -ForegroundColor Green
}
else
{
    Write-Host "⚠ WARN: config/session.php missing SESSION_DOMAIN config" -ForegroundColor Yellow
    $WarningCount++
}

# Check 3: .env.example should have SESSION_DOMAIN=null
Write-Host "✓ Checking .env.example..." -ForegroundColor White
$envExampleContent = Get-Content ".env.example" -Raw
if ($envExampleContent -match "SESSION_DOMAIN=null")
{
    Write-Host "✓ PASS: .env.example has SESSION_DOMAIN=null" -ForegroundColor Green
}
else
{
    Write-Host "⚠ WARN: .env.example missing SESSION_DOMAIN=null" -ForegroundColor Yellow
    $WarningCount++
}

# Check 4: Deployment script has config:clear
Write-Host "✓ Checking post-deploy-laravel-cloud.sh..." -ForegroundColor White
if (Test-Path "post-deploy-laravel-cloud.sh")
{
    $deployContent = Get-Content "post-deploy-laravel-cloud.sh" -Raw
    if ($deployContent -match "config:clear")
    {
        Write-Host "✓ PASS: Deployment script includes config:clear" -ForegroundColor Green
    }
    else
    {
        Write-Host "✗ FAIL: Deployment script missing config:clear" -ForegroundColor Red
        $ErrorCount++
    }
}
else
{
    Write-Host "⚠ WARN: post-deploy-laravel-cloud.sh not found" -ForegroundColor Yellow
    $WarningCount++
}

# Check 5: Verify other session settings
Write-Host "✓ Checking session security settings..." -ForegroundColor White
$SessionSettingsOK = $true

if ($laravelCloudContent -notmatch '"SESSION_SECURE_COOKIE": "true"')
{
    Write-Host "⚠ WARN: SESSION_SECURE_COOKIE not set to true" -ForegroundColor Yellow
    $SessionSettingsOK = $false
    $WarningCount++
}

if ($laravelCloudContent -notmatch '"SESSION_SAME_SITE": "lax"')
{
    Write-Host "⚠ WARN: SESSION_SAME_SITE not set to lax" -ForegroundColor Yellow
    $SessionSettingsOK = $false
    $WarningCount++
}

if ($SessionSettingsOK)
{
    Write-Host "✓ PASS: Session security settings configured" -ForegroundColor Green
}

# Check 6: Documentation exists
Write-Host "✓ Checking documentation..." -ForegroundColor White
if (Test-Path "COOKIE-SESSION-GUIDE.md")
{
    Write-Host "✓ PASS: COOKIE-SESSION-GUIDE.md exists" -ForegroundColor Green
}
else
{
    Write-Host "⚠ WARN: COOKIE-SESSION-GUIDE.md not found" -ForegroundColor Yellow
    $WarningCount++
}

# Summary
Write-Host "`n============================================================" -ForegroundColor Cyan
Write-Host "Verification Summary" -ForegroundColor Cyan
Write-Host "============================================================`n" -ForegroundColor Cyan

if ($ErrorCount -eq 0 -and $WarningCount -eq 0)
{
    Write-Host "✓ ALL CHECKS PASSED" -ForegroundColor Green
    Write-Host "`nConfiguration is ready for deployment!`n" -ForegroundColor White
    Write-Host "Next steps:" -ForegroundColor Cyan
    Write-Host "1. Commit changes: git add -A; git commit -m 'Fix: Cookie domain auto-detection'" -ForegroundColor White
    Write-Host "2. Push to remote: git push origin main" -ForegroundColor White
    Write-Host "3. Deploy via Laravel Cloud dashboard" -ForegroundColor White
    Write-Host "4. Clear browser cookies after deployment" -ForegroundColor White
    Write-Host "5. Test login at https://studeats.laravel.cloud`n" -ForegroundColor White
    exit 0
}
elseif ($ErrorCount -eq 0)
{
    Write-Host "⚠ PASSED WITH WARNINGS: $WarningCount warning(s)" -ForegroundColor Yellow
    Write-Host "`nConfiguration should work, but consider addressing warnings.`n" -ForegroundColor White
    exit 0
}
else
{
    Write-Host "✗ FAILED: $ErrorCount error(s), $WarningCount warning(s)" -ForegroundColor Red
    Write-Host "`nPlease fix errors before deploying to production.`n" -ForegroundColor White
    exit 1
}
