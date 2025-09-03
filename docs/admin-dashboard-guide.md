# StudEats Admin Dashboard Access Guide

## 🔐 Admin Account Credentials

I've created admin accounts for you to access the StudEats admin dashboard:

### Admin Account
- **Email**: `admin@studeats.com`
- **Password**: `admin123`
- **Role**: Admin

### Super Admin Account
- **Email**: `superadmin@studeats.com`
- **Password**: `superadmin123`
- **Role**: Super Admin

## 🚀 How to Access the Admin Dashboard

### Step 1: Start the Laravel Server
```bash
cd c:\xampp\htdocs\StudEats
php artisan serve
```

This will start the server at `http://localhost:8000`

### Step 2: Login to the Application
1. Open your browser and go to `http://localhost:8000`
2. Click on "Login" or go directly to `http://localhost:8000/login`
3. Enter the admin credentials:
   - Email: `admin@studeats.com`
   - Password: `admin123`

### Step 3: Access Admin Dashboard
Once logged in, navigate to the admin dashboard:
- **Direct URL**: `http://localhost:8000/admin`
- **Or**: Look for admin navigation links in the interface

## 📊 Admin Dashboard Features

The admin dashboard includes:

### 📈 Dashboard Overview
- **User Statistics**: Total users, active users, suspended users
- **Meal Statistics**: Total meals, featured meals
- **Recent Registrations**: New users in the last 7 days
- **User Growth Chart**: 30-day user registration trends
- **Top Meals**: Most popular meals by meal plan usage
- **Recent Activities**: Latest admin actions

### 👥 User Management (`/admin/users`)
- View all users
- User details and profiles
- Suspend/activate user accounts
- Reset user passwords
- Update user roles
- Delete user accounts

### 🍽️ Recipe Management (`/admin/recipes`)
- View all recipes/meals
- Create new recipes
- Edit existing recipes
- Toggle featured status
- Delete recipes

### 🏥 System Health (`/admin/system-health`)
- Database connection status
- Storage health monitoring
- Memory usage tracking
- Disk usage statistics

## 🔧 Admin Features Available

### User Management Actions
- **View Users**: Browse all registered users
- **User Details**: View detailed user profiles
- **Account Control**: Suspend or activate user accounts
- **Password Reset**: Reset user passwords
- **Role Management**: Update user roles (admin, super_admin, user)
- **Account Deletion**: Remove user accounts

### Recipe/Meal Management
- **Browse Recipes**: View all meals and recipes in the system
- **Create Recipes**: Add new meals with detailed information
- **Edit Recipes**: Modify existing meal information
- **Featured Control**: Mark meals as featured or remove featured status
- **Recipe Deletion**: Remove recipes from the system

### System Monitoring
- **Health Checks**: Monitor system components
- **Resource Usage**: Track memory and disk usage
- **Database Status**: Check database connectivity
- **Performance Metrics**: View system performance data

## 🛡️ Security Features

### Admin Middleware Protection
- **Authentication Required**: Must be logged in
- **Admin Role Required**: Only users with admin or super_admin roles can access
- **Active Account Required**: Suspended accounts are automatically logged out
- **Access Control**: 403 error for non-admin users

### Role Hierarchy
1. **Super Admin** (`super_admin`): Full system access
2. **Admin** (`admin`): Full administrative access
3. **User** (`user`): Regular user access only

## 📋 Admin Routes Available

```
/admin                          - Admin Dashboard
/admin/users                    - User Management
/admin/users/{user}             - User Details
/admin/users/{user}/suspend     - Suspend User
/admin/users/{user}/activate    - Activate User
/admin/users/{user}/reset-password - Reset Password
/admin/users/{user}/role        - Update Role
/admin/recipes                  - Recipe Management
/admin/recipes/create           - Create Recipe
/admin/recipes/{recipe}         - Recipe Details
/admin/recipes/{recipe}/edit    - Edit Recipe
/admin/recipes/{recipe}/toggle-featured - Toggle Featured
/admin/system-health            - System Health API
```

## 🎯 Quick Start Steps

1. **Start Server**: `php artisan serve`
2. **Open Browser**: Go to `http://localhost:8000`
3. **Login**: Use `admin@studeats.com` / `admin123`
4. **Access Admin**: Navigate to `http://localhost:8000/admin`

## 📱 Admin Dashboard Interface

The admin dashboard features:
- **Modern Design**: Clean, responsive interface
- **Statistics Cards**: Color-coded metric cards
- **Charts & Graphs**: Visual data representation
- **Activity Logs**: Recent admin actions tracking
- **Quick Actions**: Easy access to common tasks
- **System Monitoring**: Real-time health indicators

## 🔍 Troubleshooting

### If you can't access the admin dashboard:

1. **Check Login**: Ensure you're logged in with admin credentials
2. **Verify Role**: Confirm the user has `admin` or `super_admin` role
3. **Account Status**: Ensure the account is active (`is_active = true`)
4. **Clear Cache**: Run `php artisan cache:clear` if needed
5. **Check Routes**: Verify admin routes are properly registered

### Common Issues:
- **403 Forbidden**: User doesn't have admin role
- **Redirect to Login**: User not authenticated
- **Account Suspended**: Admin account has been deactivated

## 📈 Data Overview

The dashboard provides insights into:
- **User Growth**: Track registration trends
- **Meal Popularity**: See which Filipino dishes are most popular
- **System Performance**: Monitor application health
- **Admin Activities**: Track administrative actions
- **Usage Statistics**: Understand user engagement

Your StudEats admin dashboard is now fully functional with comprehensive Filipino meal data and robust user management capabilities!