# StudEats AI Coding Instructions

StudEats is a Laravel 12 meal planning application with email verification, user management, and BMI-based nutrition recommendations.

## Core Architecture & Domain Model

**Primary Entities:** `User` → `MealPlan` → `Meal` → `NutritionalInfo`
- Users have meal plans scheduled by date/type (breakfast, lunch, dinner)
- Advanced user profile includes BMI calculation, dietary preferences, calorie adjustments
- Email verification uses OTP system with 5-minute expiration (`OtpService` + `EmailVerificationOtp`)
- Admin system with role-based access (`admin`, `super_admin`) and activity logging

**Key Services:**
- `OtpService`: Email verification with rate limiting (5 attempts/hour)
- `EmailService`: Notification dispatch and welcome emails

## Development Workflow & Tools

**Laravel Boost MCP Integration:**
- Use `mcp_laravel-boost_*` tools for database queries, tinker, docs search
- Run `php artisan boost:mcp` via `.vscode/mcp.json` configuration
- Prefer `mcp_laravel-boost_database-query` over raw SQL investigation
- Use `mcp_laravel-boost_search-docs` for version-specific Laravel/package documentation

**Development Commands:**
```bash
composer run dev  # Starts server, queue, pail logs, vite concurrently
composer run test # Config clear + PHPUnit
vendor/bin/pint --dirty  # Code formatting (required before commits)
```

**Queue Management:**
- Email verification uses queued jobs - restart with `php artisan queue:restart`
- Check queue status via built-in `composer run dev` or manual `queue:work`

## Specific Implementation Patterns

**User Model Extensions:**
- BMI calculation methods: `calculateBMI()`, `getBMICategory()`, `getCalorieMultiplier()`
- Timezone-aware methods: `getCurrentTimeInTimezone()`, `getCurrentDateInTimezone()`
- Role checking: `isAdmin()`, `isSuperAdmin()`
- Account management: `suspend()`, `activate()`

**Model Relationships:**
```php
// User meal plans with date scoping
$user->mealPlansForDate('2025-09-18');
$user->weeklyMealPlans(Carbon::parse('2025-09-15'));
```

**OTP Verification Pattern:**
- Atomic transactions in `EmailVerificationController::verifyOtp()`
- 300-second expiration with proper error messaging
- Rate limiting prevents abuse (5 attempts/hour per email)

**Frontend Stack:**
- Tailwind CSS v4 with `@import "tailwindcss"` (not v3 directives)
- Blade templates in `resources/views/` organized by feature
- Vite bundling with TailwindCSS plugin and React components

**Middleware & Routes:**
- Custom middleware aliases in `bootstrap/app.php`: `admin`, `verified`
- Email verification routes accessible to guests and authenticated users
- Admin routes under `/admin` prefix with role-based protection

## Critical Conventions

**Database Patterns:**
- Use `casts()` method in models (not `$casts` property)
- Eager loading for N+1 prevention: `with(['meal.nutritionalInfo'])`
- Scoped queries: `scopeForDate()`, `scopeCompleted()`

**Authentication Flow:**
- Registration → OTP email → verification → dashboard
- Session-based pending verification via `pending_verification_user_id`
- Custom `MustVerifyEmail` implementation with OTP system

**Error Handling:**
- Comprehensive logging in services with context arrays
- Rate limiting with specific error messages
- Graceful degradation in email service failures

## Testing & Debugging

**Test Structure:**
- Feature tests in `tests/Feature/` (primary)
- Use model factories for data setup
- Email notifications tested via `EmailNotificationTest.php`

**Debugging Tools:**
- Laravel Boost tinker integration for live debugging
- Browser logs accessible via `mcp_laravel-boost_browser-logs`
- Database introspection with boost tools

**Common Issues:**
- Vite manifest errors → run `npm run build` or `composer run dev`
- OTP expiration → check queue processing and email delivery
- BMI calculations require height/weight in proper units (cm/kg vs ft/lbs)

<laravel-boost-guidelines>
[Previous Laravel Boost Guidelines content remains unchanged]
</laravel-boost-guidelines>