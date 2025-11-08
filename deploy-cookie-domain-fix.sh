#!/usr/bin/env bash

#
# Quick Deploy Script for Cookie Domain Fix
# Commits changes and pushes to remote for Laravel Cloud deployment
#

set -e

echo "🚀 StudEats Cookie Domain Fix - Quick Deploy"
echo "============================================="
echo ""

# Check if there are changes to commit
if [[ -z $(git status -s) ]]; then
    echo "✓ No changes to commit. Already up to date!"
    exit 0
fi

echo "📋 Changes to be committed:"
git status -s
echo ""

# Add all changes
echo "📦 Staging changes..."
git add -A

# Commit with descriptive message
echo "💾 Committing changes..."
git commit -m "Fix: Implement cookie domain auto-detection for Laravel Cloud

- Remove SESSION_DOMAIN from laravel-cloud.json to enable auto-detection
- Update .env.example with comprehensive session configuration guidance
- Create COOKIE-SESSION-GUIDE.md consolidating all cookie/session docs
- Add verification scripts (verify-cookie-fix.sh, verify-cookie-fix.ps1)
- Document deprecated files in .github/DEPRECATED-DOCS.md

This resolves 419 CSRF Token Mismatch errors by allowing Laravel to
automatically detect the request host instead of using an explicit
.laravel.cloud domain that browsers reject due to PSL rules.

Fixes: Cookie rejection errors, 419 CSRF errors, login failures
See: COOKIE-SESSION-GUIDE.md for complete documentation"

echo ""
echo "✓ Changes committed successfully!"
echo ""

# Push to remote
echo "🌐 Pushing to origin/main..."
git push origin main

echo ""
echo "============================================="
echo "✅ Deployment initiated!"
echo ""
echo "Next steps:"
echo "1. Monitor Laravel Cloud dashboard for deployment progress"
echo "2. After deployment completes, clear browser cookies"
echo "3. Test login at https://studeats.laravel.cloud"
echo "4. Verify no 419 errors in logs"
echo ""
echo "See COOKIE-DOMAIN-FIX-SUMMARY.md for post-deployment verification."
echo "============================================="
