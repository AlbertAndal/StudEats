# Admin Login Troubleshooting Guide

## 📋 Overview

This guide provides comprehensive troubleshooting steps for admin authentication issues in StudEats.

---

## ✅ Authentication Flow Analysis

### **Normal Admin Login Flow:**

```
1. User visits /admin/login
2. User enters credentials (email + password)
3. CSRF token validation
4. LoginRequest validates input
5. AuthController::adminLogin() processes:
   ├─ Credentials verification
   ├─ Role check (admin or super_admin)
   ├─ Active status check
   └─ Session regeneration
6. Redirect to /admin dashboard
```

---

## 🔍 Common Issues & Solutions

### **Issue 1: 419 Page Expired Error**

**Cause:** CSRF token mismatch due to Laravel Cloud session configuration

**Solution:** Ensure environment variables are set correctly:

```env
SESSION_DOMAIN=.laravel.cloud
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
SESSION_PARTITIONED_COOKIE=true
SESSION_DRIVER=database
APP_URL=https://studeats.laravel.cloud
```

**Verification:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

See `LARAVEL-CLOUD-419-FIX-COMPLETE.md` for full details.

---

### **Issue 2: "Invalid admin credentials" Error**

**Possible Causes:**
1. Incorrect password
2. User account doesn't exist
3. Password hash mismatch

**Diagnostic Steps:**

```bash
# Step 1: Check if admin user exists
php artisan tinker
>>> $admin = \App\Models\User::where('email', 'admin@studeats.com')->first();
>>> $admin ? 'Exists' : 'Not found';

# Step 2: Verify role
>>> $admin->role;  // Should be 'admin' or 'super_admin'

# Step 3: Check active status
>>> $admin->is_active;  // Should be true

# Step 4: Verify email verified
>>> $admin->email_verified_at;  // Should have a date

# Step 5: Test password
>>> \Hash::check('admin123', $admin->password);  // Should return true
```

**Fix:**

```bash
# Reset admin password
php artisan tinker
>>> $admin = \App\Models\User::where('email', 'admin@studeats.com')->first();
>>> $admin->update(['password' => 'admin123']);
>>> $admin->update(['is_active' => true, 'email_verified_at' => now()]);
```

---

### **Issue 3: "Access denied. Admin privileges required"**

**Cause:** User role is not 'admin' or 'super_admin'

**Fix:**

```bash
php artisan tinker
>>> $user = \App\Models\User::where('email', 'YOUR_EMAIL')->first();
>>> $user->update(['role' => 'super_admin']);
```

---

### **Issue 4: "Your admin account has been suspended"**

**Cause:** `is_active` flag is set to false

**Fix:**

```bash
php artisan tinker
>>> $admin = \App\Models\User::where('email', 'YOUR_EMAIL')->first();
>>> $admin->update(['is_active' => true, 'suspended_at' => null, 'suspended_reason' => null]);
```

---

### **Issue 5: Rate Limiting (Too Many Attempts)**

**Cause:** More than 5 failed login attempts

**Fix:**

```bash
# Clear rate limit cache
php artisan cache:clear

# Or wait for the rate limit to expire (usually 1 minute)
```

**Check Rate Limit:**

```bash
php artisan tinker
>>> \RateLimiter::clear('admin@studeats.com|127.0.0.1');
```

---

## 🧪 Testing Admin Authentication

### **Test 1: Manual Login Test**

1. Visit: `http://localhost:8000/admin/login` (or your domain)
2. Enter credentials:
   - Email: `admin@studeats.com`
   - Password: `admin123`
3. Click "Sign In"
4. Expected: Redirect to `/admin` dashboard

### **Test 2: Automated Testing**

Run the test suite:

```bash
# Run all admin authentication tests
php artisan test --filter AdminAuthenticationTest

# Run specific test
php artisan test --filter test_admin_can_login_with_valid_credentials
```

### **Test 3: Database Verification**

```bash
php artisan tinker
>>> $admin = \App\Models\User::where('email', 'admin@studeats.com')->first();
>>> [
    'exists' => $admin ? true : false,
    'email' => $admin?->email,
    'role' => $admin?->role,
    'is_admin' => $admin?->isAdmin(),
    'is_active' => $admin?->is_active,
    'email_verified' => $admin?->hasVerifiedEmail(),
    'password_hash_valid' => $admin ? strlen($admin->password) === 60 : false,
];
```

---

## 📊 Admin Account Requirements

For a user to successfully log into the admin panel, they must meet ALL criteria:

| Requirement | Column | Expected Value |
|-------------|--------|----------------|
| Valid email | `email` | Valid email format |
| Correct password | `password` | Bcrypt hash (60 chars) |
| Admin role | `role` | `'admin'` or `'super_admin'` |
| Active account | `is_active` | `true` (1) |
| Verified email | `email_verified_at` | Not NULL |

---

## 🔧 Creating New Admin Account

### **Method 1: Using Tinker (Recommended)**

```bash
php artisan tinker
>>> \App\Models\User::create([
    'name' => 'New Admin',
    'email' => 'newadmin@studeats.com',
    'password' => 'securepassword',  # Auto-hashed
    'role' => 'admin',
    'is_active' => true,
    'email_verified_at' => now(),
    'timezone' => 'Asia/Manila',
]);
```

### **Method 2: Using Emergency Admin Route**

1. Visit: `http://yourdomain.com/emergency-create-admin`
2. Fill in the form
3. Submit
4. **IMPORTANT:** Delete the route after use for security

### **Method 3: Database Seeder**

```bash
php artisan db:seed --class=AdminUserSeeder
```

---

## 🛡️ Security Considerations

### **Password Requirements:**
- Minimum 6 characters (enforced by validation)
- Automatically hashed using bcrypt
- Never stored in plain text

### **Session Security:**
- Sessions regenerated on login
- CSRF protection enabled
- HttpOnly cookies
- Secure cookies in production

### **Rate Limiting:**
- Maximum 5 failed attempts per IP/email combination
- 1-minute cooldown after exceeding limit
- Automatic throttle key clearing on successful login

---

## 📝 Logging & Debugging

### **Enable Detailed Logging:**

```php
// In AuthController::adminLogin(), logging is already enabled:
Log::info('Admin login successful', [
    'admin_id' => $user->id,
    'email' => $user->email,
    'role' => $user->role,
    'ip' => $request->ip(),
]);

Log::warning('Failed admin login attempt', [
    'email' => $credentials['email'],
    'ip' => $request->ip(),
]);
```

### **Check Logs:**

```bash
# View latest logs
tail -f storage/logs/laravel.log

# Search for admin login attempts
grep "admin login" storage/logs/laravel.log
```

---

## 🔄 Session Troubleshooting

### **Clear All Sessions:**

```bash
php artisan cache:clear
php artisan session:clear  # If available
```

### **Verify Session Configuration:**

```bash
php artisan tinker
>>> config('session.driver');     // Should be 'database'
>>> config('session.domain');     // Should be '.laravel.cloud' in production
>>> config('session.secure');     // Should be true in production
>>> config('session.same_site');  // Should be 'none' for Laravel Cloud
```

### **Check Sessions Table:**

```sql
-- View active sessions
SELECT * FROM sessions ORDER BY last_activity DESC LIMIT 5;

-- Clear old sessions
DELETE FROM sessions WHERE last_activity < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 DAY));
```

---

## 🚨 Emergency Access

If you're completely locked out:

### **Option 1: Emergency Admin Reset Route**

Visit: `http://yourdomain.com/emergency-reset-admin`

This will:
- Reset password for `admin@studeats.com` to `admin123`
- Ensure account is active
- Verify email
- Set role to `super_admin`

**⚠️ SECURITY WARNING:** Remove this route in production!

### **Option 2: Direct Database Update**

```sql
-- Reset admin account
UPDATE users 
SET 
    password = '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5pKpEUOjl8nTK', -- 'admin123'
    is_active = 1,
    email_verified_at = NOW(),
    role = 'super_admin'
WHERE email = 'admin@studeats.com';
```

### **Option 3: Create New Admin via Command Line**

```bash
php artisan tinker
>>> $admin = new \App\Models\User;
>>> $admin->name = 'Emergency Admin';
>>> $admin->email = 'emergency@studeats.com';
>>> $admin->password = 'temppass123';  # Auto-hashed
>>> $admin->role = 'super_admin';
>>> $admin->is_active = true;
>>> $admin->email_verified_at = now();
>>> $admin->timezone = 'Asia/Manila';
>>> $admin->save();
>>> echo "Admin created: {$admin->email}";
```

---

## ✅ Quick Diagnostic Checklist

Run through this checklist when troubleshooting:

- [ ] Admin user exists in database
- [ ] Email address is correct
- [ ] Password is correct (test with tinker)
- [ ] `role` is 'admin' or 'super_admin'
- [ ] `is_active` is true
- [ ] `email_verified_at` is not null
- [ ] No rate limiting active
- [ ] CSRF token is valid (no 419 errors)
- [ ] Session configuration is correct
- [ ] Cache has been cleared
- [ ] Browser cookies are enabled
- [ ] Using correct login URL: `/admin/login`

---

## 📚 Related Documentation

- [LARAVEL-CLOUD-419-FIX-COMPLETE.md](LARAVEL-CLOUD-419-FIX-COMPLETE.md) - CSRF/Session fix
- [ADMIN-ACCOUNT-README.md](ADMIN-ACCOUNT-README.md) - Admin account management
- [tests/Feature/AdminAuthenticationTest.php](tests/Feature/AdminAuthenticationTest.php) - Test suite

---

## 🆘 Still Having Issues?

If you've tried everything and still can't log in:

1. **Check Laravel logs:** `storage/logs/laravel.log`
2. **Check web server logs:** Look for HTTP 500 errors
3. **Run tests:** `php artisan test --filter AdminAuthenticationTest`
4. **Verify database connection:** `php artisan db:show`
5. **Check environment:** `php artisan about`

---

**Last Updated:** November 8, 2025  
**Version:** 1.0  
**Tested On:** Laravel 12, PHP 8.3
