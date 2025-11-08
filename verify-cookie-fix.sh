#!/usr/bin/env bash

#
# Pre-Deployment Verification Script for Cookie Domain Fix
# Run this before deploying to Laravel Cloud to verify configuration
#

set -e

echo "🔍 StudEats Cookie Domain Fix - Pre-Deployment Verification"
echo "============================================================"
echo ""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

ERRORS=0
WARNINGS=0

# Check 1: laravel-cloud.json should NOT have SESSION_DOMAIN
echo "✓ Checking laravel-cloud.json..."
if grep -q '"SESSION_DOMAIN"' laravel-cloud.json 2>/dev/null; then
    echo -e "${RED}✗ FAIL: SESSION_DOMAIN found in laravel-cloud.json${NC}"
    echo "  Expected: SESSION_DOMAIN should be removed"
    echo "  Action: Remove SESSION_DOMAIN from environment section"
    ((ERRORS++))
else
    echo -e "${GREEN}✓ PASS: SESSION_DOMAIN not in laravel-cloud.json${NC}"
fi

# Check 2: config/session.php should have null fallback
echo "✓ Checking config/session.php..."
if grep -q "env('SESSION_DOMAIN') ?: null" config/session.php; then
    echo -e "${GREEN}✓ PASS: config/session.php has null fallback${NC}"
else
    echo -e "${YELLOW}⚠ WARN: config/session.php missing null fallback${NC}"
    echo "  Expected: 'domain' => env('SESSION_DOMAIN') ?: null,"
    ((WARNINGS++))
fi

# Check 3: .env.example should have SESSION_DOMAIN=null
echo "✓ Checking .env.example..."
if grep -q "SESSION_DOMAIN=null" .env.example; then
    echo -e "${GREEN}✓ PASS: .env.example has SESSION_DOMAIN=null${NC}"
else
    echo -e "${YELLOW}⚠ WARN: .env.example missing SESSION_DOMAIN=null${NC}"
    ((WARNINGS++))
fi

# Check 4: Deployment script has config:clear
echo "✓ Checking post-deploy-laravel-cloud.sh..."
if [ -f "post-deploy-laravel-cloud.sh" ]; then
    if grep -q "config:clear" post-deploy-laravel-cloud.sh; then
        echo -e "${GREEN}✓ PASS: Deployment script includes config:clear${NC}"
    else
        echo -e "${RED}✗ FAIL: Deployment script missing config:clear${NC}"
        ((ERRORS++))
    fi
else
    echo -e "${YELLOW}⚠ WARN: post-deploy-laravel-cloud.sh not found${NC}"
    ((WARNINGS++))
fi

# Check 5: Verify other session settings
echo "✓ Checking session security settings..."
SESSION_SETTINGS_OK=true

if ! grep -q '"SESSION_SECURE_COOKIE": "true"' laravel-cloud.json; then
    echo -e "${YELLOW}⚠ WARN: SESSION_SECURE_COOKIE not set to true${NC}"
    SESSION_SETTINGS_OK=false
    ((WARNINGS++))
fi

if ! grep -q '"SESSION_SAME_SITE": "lax"' laravel-cloud.json; then
    echo -e "${YELLOW}⚠ WARN: SESSION_SAME_SITE not set to lax${NC}"
    SESSION_SETTINGS_OK=false
    ((WARNINGS++))
fi

if [ "$SESSION_SETTINGS_OK" = true ]; then
    echo -e "${GREEN}✓ PASS: Session security settings configured${NC}"
fi

# Check 6: Documentation exists
echo "✓ Checking documentation..."
if [ -f "COOKIE-SESSION-GUIDE.md" ]; then
    echo -e "${GREEN}✓ PASS: COOKIE-SESSION-GUIDE.md exists${NC}"
else
    echo -e "${YELLOW}⚠ WARN: COOKIE-SESSION-GUIDE.md not found${NC}"
    ((WARNINGS++))
fi

# Summary
echo ""
echo "============================================================"
echo "Verification Summary"
echo "============================================================"

if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo -e "${GREEN}✓ ALL CHECKS PASSED${NC}"
    echo ""
    echo "Configuration is ready for deployment!"
    echo ""
    echo "Next steps:"
    echo "1. Commit changes: git add -A && git commit -m 'Fix: Cookie domain auto-detection'"
    echo "2. Push to remote: git push origin main"
    echo "3. Deploy via Laravel Cloud dashboard"
    echo "4. Clear browser cookies after deployment"
    echo "5. Test login at https://studeats.laravel.cloud"
    exit 0
elif [ $ERRORS -eq 0 ]; then
    echo -e "${YELLOW}⚠ PASSED WITH WARNINGS: $WARNINGS warning(s)${NC}"
    echo ""
    echo "Configuration should work, but consider addressing warnings."
    exit 0
else
    echo -e "${RED}✗ FAILED: $ERRORS error(s), $WARNINGS warning(s)${NC}"
    echo ""
    echo "Please fix errors before deploying to production."
    exit 1
fi
