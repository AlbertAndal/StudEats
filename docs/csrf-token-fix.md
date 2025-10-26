# CSRF Token Fix for Save & Calculate

## 🐛 Problem

**Error message:** `❌ Failed to save recipe: CSRF token mismatch.`

**Occurred when:** Clicking the "💾 Save & Calculate Nutrition" button

**Root cause:** The AJAX request wasn't properly sending the CSRF token to Laravel, causing the request to be rejected by the `VerifyCsrfToken` middleware.

---

## ✅ Solution

### What Was Fixed

Updated the `saveAndCalculateNutrition()` function to properly include CSRF token in the AJAX request headers.

### Changes Made

**File:** `resources/views/admin/recipes/edit.blade.php`

**Before:**
```javascript
fetch(form.action, {
    method: 'POST',
    body: formData,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    }
})
```

**After:**
```javascript
// Get CSRF token from meta tag or hidden input
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                  document.querySelector('input[name="_token"]')?.value;

fetch(form.action, {
    method: 'POST',
    body: formData,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
    }
})
```

### Additional Improvements

**Enhanced error handling:**
```javascript
.then(response => {
    // Check if response is ok
    if (!response.ok) {
        return response.text().then(text => {
            throw new Error(`Server returned ${response.status}: ${text.substring(0, 100)}`);
        });
    }
    return response.json();
})
.catch(error => {
    console.error('Save error:', error);
    
    // Show specific error message
    let errorMessage = error.message || 'Unknown error';
    if (errorMessage.includes('419') || errorMessage.includes('CSRF')) {
        errorMessage = 'CSRF token mismatch. Please refresh the page and try again.';
    }
    
    showNotification('❌ Save failed: ' + errorMessage, 'error');
    
    // Offer to calculate anyway
    if (confirm('Could not save recipe. Calculate nutrition with current values anyway?')) {
        calculateNutrition();
    }
});
```

---

## 🔧 How CSRF Protection Works

### Laravel's CSRF Protection

Laravel automatically protects against Cross-Site Request Forgery (CSRF) attacks by:

1. **Generating a token** for each user session
2. **Including it in forms** via `@csrf` Blade directive (creates hidden `_token` input)
3. **Including it in HTML** via meta tag in layout:
   ```html
   <meta name="csrf-token" content="{{ csrf_token() }}">
   ```
4. **Validating the token** on POST/PUT/PATCH/DELETE requests

### AJAX Requests

For AJAX requests, you must include the CSRF token in one of these ways:

**Method 1: X-CSRF-TOKEN Header (Recommended)**
```javascript
headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
}
```

**Method 2: FormData with _token field**
```javascript
const formData = new FormData();
formData.append('_token', csrfToken);
```

**Method 3: Query parameter**
```javascript
fetch('/api/endpoint?_token=' + csrfToken)
```

### Our Implementation

We use **Method 1** (X-CSRF-TOKEN header) because:
- ✅ Most explicit and clear
- ✅ Doesn't interfere with FormData
- ✅ Works with file uploads
- ✅ Laravel automatically checks this header

We also have a **fallback** to get the token from:
1. Meta tag: `<meta name="csrf-token">` (preferred)
2. Hidden input: `<input name="_token">` (backup)

---

## 🧪 Testing the Fix

### 1. Verify CSRF Token Exists

**Open browser console and run:**
```javascript
console.log('Meta token:', document.querySelector('meta[name="csrf-token"]')?.content);
console.log('Form token:', document.querySelector('input[name="_token"]')?.value);
```

**Expected output:**
```
Meta token: eyJpdiI6IjFtZ2tnV...  (long string)
Form token: eyJpdiI6IjFtZ2tnV...  (same long string)
```

### 2. Test Save & Calculate

**Steps:**
1. Go to: http://127.0.0.1:8000/admin/recipes/14/edit
2. Add ingredients (e.g., "Chicken breast - 200 - g")
3. Set servings (e.g., 4)
4. Click **"💾 Save & Calculate Nutrition"**

**Expected results:**
- ✅ Notification: "💾 Saving recipe and calculating nutrition..."
- ✅ Notification: "✅ Recipe saved successfully! Calculating nutrition..."
- ✅ Nutrition fields auto-fill with green highlight
- ✅ Final notification: "✅ Nutrition calculated successfully!"

**NO MORE "CSRF token mismatch" error!** 🎉

### 3. Check Browser Network Tab

**Open DevTools → Network tab:**
1. Click "Save & Calculate Nutrition"
2. Find the POST request to `/admin/recipes/14`
3. Check **Request Headers** section

**Should see:**
```
X-Requested-With: XMLHttpRequest
X-CSRF-TOKEN: eyJpdiI6IjFtZ2tnV...
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Recipe updated successfully!",
  "recipe": {
    "id": 14,
    "name": "..."
  }
}
```

---

## 🔒 Security Notes

### Why CSRF Protection Matters

**CSRF Attack Example:**
```html
<!-- Malicious website -->
<form action="https://yourdomain.com/admin/recipes/1" method="POST">
    <input type="hidden" name="name" value="Hacked!">
</form>
<script>document.forms[0].submit();</script>
```

Without CSRF protection, if an admin visits this malicious site while logged in, their browser would automatically send their session cookie, and the recipe would be modified without their knowledge.

**With CSRF protection:**
- ❌ The malicious site doesn't have the CSRF token
- ❌ Laravel rejects the request with 419 error
- ✅ Your application is protected!

### Best Practices

1. **Never disable CSRF protection** in production
2. **Always include CSRF token** in AJAX requests
3. **Refresh page if token expires** (sessions timeout after inactivity)
4. **Use HTTPS** to prevent token interception
5. **Don't log CSRF tokens** (they're sensitive)

---

## 🚨 Troubleshooting

### Issue: "419 Page Expired" Error

**Causes:**
- Session expired (user was inactive too long)
- Browser cached an old page
- Token doesn't match server session

**Solutions:**
1. **Refresh the page** (F5 or Ctrl+F5)
2. **Clear browser cache**
3. **Check session configuration:**
   ```php
   // config/session.php
   'lifetime' => 120, // Session lifetime in minutes
   ```

---

### Issue: Token Still Doesn't Work

**Debug checklist:**

1. **Check meta tag exists:**
   ```javascript
   console.log(document.querySelector('meta[name="csrf-token"]'));
   ```

2. **Check layout includes meta tag:**
   ```blade
   <!-- resources/views/layouts/admin.blade.php -->
   <meta name="csrf-token" content="{{ csrf_token() }}">
   ```

3. **Check form includes CSRF field:**
   ```blade
   <form method="POST" action="...">
       @csrf
       <!-- or -->
       <input type="hidden" name="_token" value="{{ csrf_token() }}">
   </form>
   ```

4. **Check middleware is active:**
   ```php
   // app/Http/Kernel.php or bootstrap/app.php
   \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class
   ```

5. **Check CSRF isn't excluded:**
   ```php
   // app/Http/Middleware/VerifyCsrfToken.php
   protected $except = [
       // Make sure your route isn't listed here
   ];
   ```

---

### Issue: File Upload with CSRF Token

**Important:** When uploading files with AJAX:

```javascript
const formData = new FormData(form); // Includes file and _token
// DON'T set Content-Type header - let browser set it automatically

fetch(url, {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': csrfToken,
        // NO 'Content-Type' header!
    }
})
```

**Why?** Browser sets `Content-Type: multipart/form-data; boundary=...` automatically.

---

## 📝 Code Reference

### Full `saveAndCalculateNutrition()` Function

**Location:** `resources/views/admin/recipes/edit.blade.php` (lines ~614-698)

```javascript
function saveAndCalculateNutrition() {
    // 1. Validate required fields
    const recipeName = document.querySelector('input[name="name"]');
    const servings = document.querySelector('input[name="servings"]');
    
    if (!recipeName || !recipeName.value.trim()) {
        showNotification('⚠️ Please enter a recipe name before saving', 'warning');
        recipeName?.focus();
        return;
    }
    
    if (!servings || !servings.value || servings.value < 1) {
        showNotification('⚠️ Please enter number of servings before calculating', 'warning');
        servings?.focus();
        return;
    }
    
    // 2. Check for ingredients
    const names = document.querySelectorAll('input[name="ingredient_names[]"]');
    let hasIngredients = false;
    names.forEach(input => {
        if (input.value.trim()) {
            hasIngredients = true;
        }
    });
    
    if (!hasIngredients) {
        showNotification('⚠️ Please add at least one ingredient', 'warning');
        return;
    }
    
    // 3. Show loading notification
    showNotification('💾 Saving recipe and calculating nutrition...', 'info');
    
    // 4. Get form and CSRF token
    const form = document.querySelector('form');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                      document.querySelector('input[name="_token"]')?.value;
    
    // 5. Submit via AJAX with CSRF token
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`Server returned ${response.status}: ${text.substring(0, 100)}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('✅ Recipe saved successfully! Calculating nutrition...', 'success');
            setTimeout(() => calculateNutrition(), 500);
        } else {
            showNotification('❌ Failed to save recipe: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Save error:', error);
        
        let errorMessage = error.message || 'Unknown error';
        if (errorMessage.includes('419') || errorMessage.includes('CSRF')) {
            errorMessage = 'CSRF token mismatch. Please refresh the page and try again.';
        }
        
        showNotification('❌ Save failed: ' + errorMessage, 'error');
        
        if (confirm('Could not save recipe. Calculate nutrition with current values anyway?')) {
            calculateNutrition();
        }
    });
}
```

---

## ✅ Summary

**Problem:** CSRF token not included in AJAX request
**Solution:** Added `X-CSRF-TOKEN` header with token from meta tag
**Result:** Save & Calculate now works perfectly! ✅

**Test it now:** http://127.0.0.1:8000/admin/recipes/14/edit

The "💾 Save & Calculate Nutrition" button should now work without any CSRF errors! 🎉
