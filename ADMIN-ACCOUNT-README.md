# StudEats Admin Account Documentation

## 🔐 Default Admin Credentials

Upon deployment, a default admin account is automatically created with the following credentials:

```
Email:    admin@studeats.com
Password: admin123
```

### Admin Login URL
```
https://your-domain.com/admin/login
```

For local development:
```
http://127.0.0.1:8000/admin/login
```

---

## ⚠️ CRITICAL SECURITY NOTICE

**YOU MUST CHANGE THE DEFAULT PASSWORD IMMEDIATELY AFTER FIRST LOGIN!**

The default credentials are:
- Publicly documented
- Easily guessable
- A significant security risk if left unchanged

---

## 📋 Admin Account Details

| Property | Value |
|----------|-------|
| **Name** | StudEats Admin |
| **Email** | admin@studeats.com |
| **Role** | Super Admin |
| **Permissions** | Full system access |
| **Status** | Active |
| **Email Verified** | Yes |

---

## 🔄 How to Change Your Password

### Method 1: Through the Admin Dashboard
1. Log in with default credentials
2. Navigate to **Profile** or **Settings**
3. Click **Change Password**
4. Enter current password: `admin123`
5. Enter and confirm new secure password
6. Click **Save Changes**

### Method 2: Using Artisan Command (Server Access)
```bash
php artisan tinker

# In tinker:
$admin = \App\Models\User::where('email', 'admin@studeats.com')->first();
$admin->password = 'YOUR_NEW_SECURE_PASSWORD';
$admin->save();
exit
```

---

## 🛡️ Password Requirements

Your new password should:
- Be at least 8 characters long
- Include uppercase and lowercase letters
- Include numbers
- Include special characters (!@#$%^&*)
- NOT be a common word or phrase
- NOT be the same as the default password

### ✅ Good Password Examples:
- `MyS3cure@dminP@ss2025`
- `StudEats!Admin#2024$`
- `Adm1n$tr0ngP@ssw0rd`

### ❌ Bad Password Examples:
- `admin123` (default - MUST change!)
- `password`
- `123456`
- `admin`

---

## 🔒 Admin Security Features

### 1. Login Attempt Logging
All admin login attempts are logged with:
- IP address
- Timestamp
- Success/failure status
- Browser information

### 2. Account Suspension
The admin account can be suspended by:
- Another super admin
- System security triggers
- Manual intervention

### 3. Email Verification
The admin email is pre-verified, but you can:
- Change the email address
- Re-verify if needed

### 4. Session Management
- Sessions expire after 120 minutes of inactivity
- Multiple sessions are allowed
- Active sessions can be viewed and revoked

---

## 📊 Admin Capabilities

### User Management
- ✅ View all users
- ✅ Edit user profiles
- ✅ Suspend/activate accounts
- ✅ Delete users
- ✅ Reset user passwords
- ✅ Verify user emails

### Content Management
- ✅ Manage recipes
- ✅ Manage meal plans
- ✅ Manage ingredients
- ✅ Update nutritional information
- ✅ Moderate user content

### System Management
- ✅ View system logs
- ✅ Monitor admin activities
- ✅ Manage application settings
- ✅ View security reports
- ✅ Access analytics

### Security Monitoring
- ✅ View login attempts
- ✅ Monitor failed authentications
- ✅ Track admin activities
- ✅ Review security alerts

---

## 🚀 Deployment Information

### Automatic Creation
The admin account is created automatically during deployment via:
```bash
php artisan db:seed --class=AdminSeeder
```

### Manual Creation (if needed)
If the account wasn't created automatically:
```bash
# SSH into your server
cd /path/to/studeats
php artisan db:seed --class=AdminSeeder --force
```

### Verification
To verify the admin account exists:
```bash
php artisan tinker

# In tinker:
\App\Models\User::where('email', 'admin@studeats.com')
    ->first(['id', 'name', 'email', 'role', 'is_active']);
exit
```

---

## 🔧 Troubleshooting

### Cannot Login with Default Credentials

**Problem:** "Invalid admin credentials" error

**Solutions:**
1. Verify you're using the admin login URL (`/admin/login`)
2. Check that the account exists:
   ```bash
   php artisan tinker
   \App\Models\User::where('email', 'admin@studeats.com')->exists();
   ```
3. Reset the password:
   ```bash
   php artisan tinker
   $admin = \App\Models\User::where('email', 'admin@studeats.com')->first();
   $admin->password = 'admin123';
   $admin->save();
   ```
4. Clear application cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

### Account is Suspended

**Problem:** "Your admin account has been suspended"

**Solutions:**
1. Contact another super admin to reactivate
2. Or use database access:
   ```bash
   php artisan tinker
   $admin = \App\Models\User::where('email', 'admin@studeats.com')->first();
   $admin->is_active = true;
   $admin->save();
   ```

### Email Not Verified

**Problem:** Redirected to email verification

**Solutions:**
```bash
php artisan tinker
$admin = \App\Models\User::where('email', 'admin@studeats.com')->first();
$admin->email_verified_at = now();
$admin->save();
```

---

## 📝 Best Practices

### 1. Change Default Password Immediately
- Do this within the first 5 minutes of deployment
- Use a password manager to generate a strong password
- Store the new password securely

### 2. Create Additional Admin Accounts
- Don't share the main admin account
- Create individual admin accounts for team members
- Use appropriate role levels (admin vs super_admin)

### 3. Regular Security Audits
- Review admin login logs monthly
- Check for suspicious activities
- Update passwords quarterly

### 4. Enable Two-Factor Authentication (if available)
- Add an extra layer of security
- Use authenticator apps (Google Authenticator, Authy)

### 5. Limit Admin Access
- Use principle of least privilege
- Grant admin access only when necessary
- Revoke access when no longer needed

---

## 🆘 Emergency Access Recovery

If you lose access to the admin account:

### Option 1: Database Direct Access
```sql
-- Connect to your database
UPDATE users 
SET password = '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5Y5c1yDqC2nme' -- 'admin123'
WHERE email = 'admin@studeats.com';
```

### Option 2: Create New Super Admin
```bash
php artisan tinker

\App\Models\User::create([
    'name' => 'Emergency Admin',
    'email' => 'emergency@studeats.com',
    'password' => 'emergency123',
    'email_verified_at' => now(),
    'role' => 'super_admin',
    'is_active' => true,
]);
```

---

## 📞 Support

For additional support:
- Check application logs: `storage/logs/laravel.log`
- Review admin logs in the dashboard
- Contact system administrator
- Reference Laravel documentation

---

## 📄 Related Documentation

- [User Management Guide](docs/admin-dashboard-guide.md)
- [Security Best Practices](docs/security-guide.md)
- [Deployment Guide](RAILWAY-DEPLOYMENT.md)
- [Troubleshooting Guide](docs/)

---

**Last Updated:** November 4, 2025  
**Version:** 1.0  
**Maintained By:** StudEats Development Team
