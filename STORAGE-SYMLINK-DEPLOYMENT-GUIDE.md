# Storage Symlink Deployment Guide

## Problem

Images stored in `storage/app/public/` are not accessible after deployment because the symbolic link from `public/storage` to `storage/app/public` doesn't exist on the deployment server.

## Quick Fix (Web-Based)

### Step 1: Check Symlink Status
Visit: `https://yourdomain.com/verify-symlink`

This will show:
- Whether the symlink exists
- If it points to the correct location
- Sample images in storage
- Test image URL generation

### Step 2: Create Symlink
Visit: `https://yourdomain.com/create-symlink`

This will:
- Check for existing `public/storage` directory
- Remove incorrect symlinks
- Create proper symlink to `storage/app/public`
- Verify the configuration

### Step 3: Manual Directory Removal (if needed)
If you see the message "A public/storage directory exists but is not a symlink":

1. Connect to your server via FTP/SSH
2. Delete the `public/storage` folder (not the `storage/app/public` folder!)
3. Revisit `https://yourdomain.com/create-symlink`

## Alternative: Terminal/Artisan Method

If you have SSH access to your deployment server:

```bash
# Method 1: Delete and recreate
rm -rf public/storage
php artisan storage:link

# Method 2: Force recreation
php artisan storage:link --force
```

## Deployment Script Integration

Add to your deployment scripts (e.g., `post-deploy.sh`):

```bash
#!/bin/bash

# Remove existing symlink/directory
rm -rf public/storage

# Create fresh symlink
php artisan storage:link --force

# Verify symlink
if [ -L public/storage ]; then
    echo "✅ Storage symlink created successfully"
    ls -la public/storage
else
    echo "❌ Failed to create storage symlink"
    exit 1
fi
```

## Why This Happens

1. **Development Environment:**
   - You run `php artisan storage:link` locally
   - Creates `public/storage` → `storage/app/public` symlink
   - You might commit `public/storage` as a directory (wrong!)

2. **Deployment Environment:**
   - If `public/storage` was committed as a directory, Git recreates it as a directory
   - Symlink doesn't exist, breaking image access
   - Deployment scripts may not run `storage:link` automatically

## Prevention

### Add to `.gitignore`
```gitignore
/public/storage
/storage/*.key
/storage/app/public/*
!/storage/app/public/.gitignore
```

### Add to `composer.json`
```json
{
    "scripts": {
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ],
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force"
        ],
        "post-install-cmd": [
            "@php artisan storage:link --ansi"
        ]
    }
}
```

## Verification Checklist

- [ ] `public/storage` is a symlink (not a directory)
- [ ] Symlink points to correct path: `../storage/app/public`
- [ ] `storage/app/public/meals/` contains image files
- [ ] Images are accessible at `https://yourdomain.com/storage/meals/image.jpg`
- [ ] Meal model `image_url` attribute generates correct URLs
- [ ] File permissions are correct (644 for files, 755 for directories)

## Troubleshooting

### Images Still Not Showing

1. **Check File Permissions:**
```bash
chmod -R 755 storage/app/public
chmod -R 644 storage/app/public/meals/*
```

2. **Verify Storage Disk Configuration:**
Check `config/filesystems.php`:
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

3. **Test Direct File Access:**
```bash
# Create test file
echo "test" > storage/app/public/test.txt

# Try accessing via web
curl https://yourdomain.com/storage/test.txt
```

4. **Check Web Server Configuration:**
Ensure your web server (Nginx/Apache) serves files from `public/` directory.

### Laravel Cloud Specific

Laravel Cloud may use custom storage handling. Check:
- Storage disk configuration in Laravel Cloud dashboard
- Environment variable `FILESYSTEM_DISK`
- Custom storage routes in `routes/web.php`

## Routes Added

- `GET /create-symlink` - Create storage symlink via web
- `GET /verify-symlink` - Check symlink status and configuration

**Security Note:** These routes are public for deployment convenience. Consider adding authentication or removing them after successful deployment.

## Related Files

- `routes/web.php` - Symlink creation routes
- `config/filesystems.php` - Storage disk configuration
- `app/Models/Meal.php` - Image URL attribute generation
- `.gitignore` - Prevents committing symlinks/storage files
- Deployment scripts with `storage:link` commands
