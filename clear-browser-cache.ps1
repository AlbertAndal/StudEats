# StudEats Browser Cache Clearing Script for Windows
# This script helps clear browser cache and cookies that may cause 419 errors

Write-Host "StudEats - Browser Cache Clearing Tool" -ForegroundColor Green
Write-Host "=======================================" -ForegroundColor Green
Write-Host ""

# Function to close browser processes
function Close-BrowserProcesses {
    param([string]$BrowserName, [string[]]$ProcessNames)
    
    Write-Host "Closing $BrowserName processes..." -ForegroundColor Yellow
    
    foreach ($process in $ProcessNames) {
        $runningProcesses = Get-Process -Name $process -ErrorAction SilentlyContinue
        if ($runningProcesses) {
            Write-Host "  Closing $process processes..." -ForegroundColor Cyan
            $runningProcesses | Stop-Process -Force
            Start-Sleep -Seconds 2
        }
    }
}

# Function to clear Firefox cache
function Clear-FirefoxCache {
    Write-Host "Clearing Firefox cache and cookies..." -ForegroundColor Yellow
    
    # Close Firefox
    Close-BrowserProcesses "Firefox" @("firefox")
    
    # Firefox profile paths
    $firefoxProfiles = @(
        "$env:APPDATA\Mozilla\Firefox\Profiles",
        "$env:LOCALAPPDATA\Mozilla\Firefox\Profiles"
    )
    
    foreach ($profilePath in $firefoxProfiles) {
        if (Test-Path $profilePath) {
            $profiles = Get-ChildItem -Path $profilePath -Directory
            foreach ($profile in $profiles) {
                $cachePath = Join-Path $profile.FullName "cache2"
                $cookiesPath = Join-Path $profile.FullName "cookies.sqlite"
                $sessionPath = Join-Path $profile.FullName "sessionstore.jsonlz4"
                
                # Clear cache
                if (Test-Path $cachePath) {
                    Remove-Item -Path $cachePath -Recurse -Force -ErrorAction SilentlyContinue
                    Write-Host "  Cleared cache for profile: $($profile.Name)" -ForegroundColor Cyan
                }
                
                # Clear cookies
                if (Test-Path $cookiesPath) {
                    Remove-Item -Path $cookiesPath -Force -ErrorAction SilentlyContinue
                    Write-Host "  Cleared cookies for profile: $($profile.Name)" -ForegroundColor Cyan
                }
                
                # Clear session storage
                if (Test-Path $sessionPath) {
                    Remove-Item -Path $sessionPath -Force -ErrorAction SilentlyContinue
                    Write-Host "  Cleared session storage for profile: $($profile.Name)" -ForegroundColor Cyan
                }
            }
        }
    }
}

# Function to clear Chrome cache
function Clear-ChromeCache {
    Write-Host "Clearing Chrome cache and cookies..." -ForegroundColor Yellow
    
    # Close Chrome
    Close-BrowserProcesses "Chrome" @("chrome", "chrome.exe")
    
    $chromePaths = @(
        "$env:LOCALAPPDATA\Google\Chrome\User Data\Default",
        "$env:LOCALAPPDATA\Google\Chrome\User Data\Profile 1"
    )
    
    foreach ($chromePath in $chromePaths) {
        if (Test-Path $chromePath) {
            $cacheFiles = @(
                "Cache",
                "Code Cache",
                "Cookies",
                "Cookies-journal",
                "Local Storage",
                "Session Storage",
                "Web Data",
                "Web Data-journal"
            )
            
            foreach ($file in $cacheFiles) {
                $fullPath = Join-Path $chromePath $file
                if (Test-Path $fullPath) {
                    Remove-Item -Path $fullPath -Recurse -Force -ErrorAction SilentlyContinue
                    Write-Host "  Cleared $file" -ForegroundColor Cyan
                }
            }
        }
    }
}

# Function to clear Edge cache
function Clear-EdgeCache {
    Write-Host "Clearing Edge cache and cookies..." -ForegroundColor Yellow
    
    # Close Edge
    Close-BrowserProcesses "Edge" @("msedge")
    
    $edgePath = "$env:LOCALAPPDATA\Microsoft\Edge\User Data\Default"
    
    if (Test-Path $edgePath) {
        $cacheFiles = @(
            "Cache",
            "Code Cache",
            "Cookies",
            "Cookies-journal",
            "Local Storage",
            "Session Storage",
            "Web Data",
            "Web Data-journal"
        )
        
        foreach ($file in $cacheFiles) {
            $fullPath = Join-Path $edgePath $file
            if (Test-Path $fullPath) {
                Remove-Item -Path $fullPath -Recurse -Force -ErrorAction SilentlyContinue
                Write-Host "  Cleared $file" -ForegroundColor Cyan
            }
        }
    }
}

# Function to clear DNS cache
function Clear-DNSCache {
    Write-Host "Clearing DNS cache..." -ForegroundColor Yellow
    try {
        ipconfig /flushdns | Out-Null
        Write-Host "  DNS cache cleared successfully" -ForegroundColor Cyan
    } catch {
        Write-Host "  Failed to clear DNS cache" -ForegroundColor Red
    }
}

# Main script execution
Write-Host "This script will clear browser cache and cookies for localhost to resolve 419 errors." -ForegroundColor White
Write-Host "Make sure all browsers are closed before proceeding." -ForegroundColor Yellow
Write-Host ""

$confirmation = Read-Host "Do you want to proceed? (y/n)"

if ($confirmation -eq 'y' -or $confirmation -eq 'Y' -or $confirmation -eq 'yes') {
    Write-Host ""
    Write-Host "Starting cache clearing process..." -ForegroundColor Green
    Write-Host ""
    
    # Clear different browsers
    $browsers = Read-Host "Which browsers to clear? (a=all, f=firefox, c=chrome, e=edge)"
    
    switch ($browsers.ToLower()) {
        'a' {
            Clear-FirefoxCache
            Clear-ChromeCache
            Clear-EdgeCache
        }
        'f' { Clear-FirefoxCache }
        'c' { Clear-ChromeCache }
        'e' { Clear-EdgeCache }
        default {
            Write-Host "Invalid option. Clearing all browsers..." -ForegroundColor Yellow
            Clear-FirefoxCache
            Clear-ChromeCache
            Clear-EdgeCache
        }
    }
    
    # Clear DNS cache
    Clear-DNSCache
    
    Write-Host ""
    Write-Host "Cache clearing completed!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Additional steps to resolve 419 errors:" -ForegroundColor Yellow
    Write-Host "1. Restart your browsers" -ForegroundColor White
    Write-Host "2. Navigate to http://127.0.0.1:8000/admin/login" -ForegroundColor White
    Write-Host "3. If issues persist, run: php troubleshoot-session.php" -ForegroundColor White
    Write-Host "4. Consider disabling Firefox Enhanced Tracking Protection for localhost" -ForegroundColor White
    Write-Host ""
    
} else {
    Write-Host "Cache clearing cancelled." -ForegroundColor Red
}

Write-Host "Press any key to exit..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")