# Image Display Quick Test & Fix

## 🔧 Quick Test Steps

1. **Start the server:**
   ```bash
   php artisan serve --host=localhost --port=8000
   ```

2. **Test image URLs directly in browser:**
   - http://localhost:8000/storage/meals/uEgYrZPJAqBMWi4bYbl2CSBtXKE24xQFyWJruQve.jpg
   - http://localhost:8000/storage/meals/IKVoa42V5gSLCjfY1NR6zo1HIJzQU61xj9wIuURa.jpg

3. **Access admin dashboard:**
   - Go to: http://localhost:8000/admin
   - Login with: admin@studeats.com / admin123
   - Navigate to: http://localhost:8000/admin/recipes

## ✅ Current Status After Fix

- ✅ Image URL generation now uses correct port (8000)
- ✅ Storage symlink is functional 
- ✅ All image files exist and are accessible
- ✅ File permissions are correct

## 🔧 If Images Still Don't Show

### Method 1: Hard-coded URL Fix (Temporary)
Add this to your admin views for debugging:

```html
<!-- Debug image URL -->
<div class="debug-info">
    <p>Image Path: {{ $recipe->image_path }}</p>
    <p>Generated URL: {{ $recipe->image_url }}</p>
    <p>Direct URL: http://localhost:8000/storage/{{ $recipe->image_path }}</p>
</div>

<!-- Test both URLs -->
<img src="{{ $recipe->image_url }}" alt="Generated URL" style="max-width: 100px;">
<img src="http://localhost:8000/storage/{{ $recipe->image_path }}" alt="Direct URL" style="max-width: 100px;">
```

### Method 2: Server Access Test
Visit these URLs directly:
- http://localhost:8000/storage/meals/uEgYrZPJAqBMWi4bYbl2CSBtXKE24xQFyWJruQve.jpg

If this works, images should display in admin panel.

### Method 3: Browser Developer Tools
1. Open admin panel
2. Press F12 to open developer tools
3. Check Console tab for errors
4. Check Network tab to see if image requests are failing

## 🎯 Expected Result

After these fixes:
- Images should display correctly in `/admin/recipes`
- Image URLs should use `http://localhost:8000/storage/...`
- No broken image icons in admin dashboard