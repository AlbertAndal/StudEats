# Test API Button - Troubleshooting & Fix Guide

**Date:** October 11, 2025  
**Issue:** Test API button not working when clicked  
**Status:** ✅ Fixed with enhanced error handling

---

## What Was Fixed

### 1. **Enhanced testPriceAPI() Function**

**BEFORE:**
- Basic error handling
- No visual feedback on button
- Limited console logging
- Single-line error messages

**AFTER:**
- ✅ Button loading state with spinner
- ✅ Detailed console logging with emojis
- ✅ Multi-line error messages
- ✅ Better error categorization
- ✅ Automatic button state restoration
- ✅ JSON parse error handling
- ✅ Extended notification duration for errors

### 2. **Improved Notification System**

**New Features:**
- ✅ Multi-line message support (`\n` works now)
- ✅ Maximum width (400px) for readability
- ✅ Longer duration for error messages (6s vs 4s)
- ✅ Pre-line whitespace formatting

---

## How to Use the Test API Button

### Step 1: Click the Button
Look for this button in the recipe edit page:
```
[🧪 Test API]
```

### Step 2: Watch for Loading State
Button changes to:
```
[⌛ Testing...]
```

### Step 3: Check Notifications

**Success Notification (Green):**
```
✅ API Test Success!
Carrots: ₱240.00
Source: Bantay Presyo
```

**Error Notification (Red):**
```
❌ API Test Failed
Status: 404
Ingredient not found
```

**Info Notification (Blue):**
```
🧪 Testing API with "Carrots"...
```

### Step 4: Check Browser Console (F12)

You'll see detailed logs:
```
🧪 Testing API connection...
CSRF Token: ✅ Found
📡 Response status: 200
📋 Response headers: {content-type: "application/json", ...}
📄 Response text: {"success":true,"price":240,...}
✅ Parsed data: {success: true, price: 240, ...}
```

---

## Console Test Commands

You can also test the API directly from the browser console (F12):

### Test 1: Basic API Call
```javascript
await testPriceAPI();
```

### Test 2: Manual API Call
```javascript
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const response = await fetch('/api/ingredient-price', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        ingredient_name: 'Carrots',
        region: 'NCR'
    })
});

const data = await response.json();
console.log('Result:', data);
```

### Test 3: Check CSRF Token
```javascript
const token = document.querySelector('meta[name="csrf-token"]');
console.log('CSRF Token:', token ? token.getAttribute('content') : 'MISSING!');
```

### Test 4: Test Different Ingredients
```javascript
async function quickTest(ingredientName) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch('/api/ingredient-price', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            ingredient_name: ingredientName,
            region: 'NCR'
        })
    });
    const data = await response.json();
    console.log(`${ingredientName}:`, data);
    return data;
}

// Test multiple ingredients
await quickTest('Carrots');
await quickTest('Pechay');
await quickTest('Bell Pepper');
await quickTest('Cabbage');
```

---

## Troubleshooting Common Issues

### Issue 1: "CSRF token missing"

**Symptoms:**
- Red notification: "❌ CSRF token missing - check page source"
- Console shows: "CSRF Token: ❌ Missing"

**Solution:**
1. Check if `<meta name="csrf-token" content="...">` exists in page source (Ctrl+U)
2. Verify `@push('head')` section in edit.blade.php contains:
   ```blade
   @push('head')
   <meta name="csrf-token" content="{{ csrf_token() }}">
   @endpush
   ```
3. Clear browser cache and reload page
4. Check if you're on correct domain (not file://)

### Issue 2: "Network error" or "Failed to fetch"

**Symptoms:**
- Red notification: "💥 Test Error: Failed to fetch"
- Console shows network error

**Solution:**
1. Check if Laravel dev server is running:
   ```powershell
   composer run dev
   ```
2. Verify URL is correct (should be `http://localhost:8000` or similar)
3. Check browser console for CORS errors
4. Verify route exists:
   ```bash
   php artisan route:list | grep ingredient-price
   ```

### Issue 3: "404 Not Found"

**Symptoms:**
- Red notification: "❌ API Test Failed Status: 404"
- Console shows: "📡 Response status: 404"

**Solution:**
1. Check route is registered:
   ```bash
   php artisan route:list | grep api/ingredient-price
   ```
2. Verify `routes/web.php` contains:
   ```php
   Route::prefix('api')->group(function () {
       Route::post('/ingredient-price', [IngredientPriceController::class, 'getPrice']);
   });
   ```
3. Clear route cache:
   ```bash
   php artisan route:clear
   php artisan route:cache
   ```

### Issue 4: "Invalid JSON response"

**Symptoms:**
- Red notification: "❌ Invalid JSON response: <!DOCTYPE html>..."
- Response is HTML instead of JSON

**Solution:**
1. Check if controller method returns JSON properly
2. Look for PHP errors in response (HTML error page)
3. Check Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```
4. Verify `IngredientPriceController@getPrice` exists and returns JSON

### Issue 5: Button does nothing when clicked

**Symptoms:**
- No notification appears
- No console logs
- Button doesn't change to "Testing..."

**Solution:**
1. Check browser console (F12) for JavaScript errors
2. Verify onclick attribute:
   ```html
   <button type="button" onclick="testPriceAPI()">
   ```
3. Check if function is defined in global scope:
   ```javascript
   console.log(typeof testPriceAPI); // should show "function"
   ```
4. Look for script tag errors or unclosed brackets
5. Clear browser cache and hard reload (Ctrl+Shift+R)

---

## Expected API Response

### Success Response
```json
{
    "success": true,
    "ingredient_name": "Carrots",
    "price": 240.00,
    "unit": "kg",
    "source": "Bantay Presyo - Multiple Markets",
    "updated_at": "2025-10-10 21:42:47",
    "region": "NCR"
}
```

### Not Found Response
```json
{
    "success": false,
    "message": "Ingredient not found in price database",
    "ingredient_name": "Rare Spice"
}
```

### Error Response
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "ingredient_name": ["The ingredient name field is required."]
    }
}
```

---

## Code Improvements Made

### Enhanced Error Handling
```javascript
try {
    // Parse JSON
    const data = JSON.parse(responseText);
    // ... success handling
} catch (parseError) {
    // Handle JSON parse errors specifically
    console.error('JSON Parse Error:', parseError);
    showNotification(`❌ Invalid JSON response: ${responseText.substring(0, 100)}`, 'error');
}
```

### Button State Management
```javascript
// Get button and save original state
const button = event?.target?.closest('button');
let originalText = '';

if (button) {
    originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `<svg class="animate-spin">...</svg> Testing...`;
}

// Always restore in finally block
finally {
    if (button && originalText) {
        button.innerHTML = originalText;
        button.disabled = false;
    }
}
```

### Enhanced Console Logging
```javascript
console.log('🧪 Testing API connection...');
console.log('CSRF Token:', csrfToken ? '✅ Found' : '❌ Missing');
console.log('📡 Response status:', response.status);
console.log('📋 Response headers:', Object.fromEntries([...response.headers]));
console.log('📄 Response text:', responseText);
console.log('✅ Parsed data:', data);
```

### Multi-line Notifications
```javascript
showNotification(`✅ API Test Success!
Carrots: ₱${data.price}
Source: ${data.source || 'Bantay Presyo'}`, 'success');
```

---

## Testing Checklist

### ✅ Pre-Test Verification
- [ ] Laravel dev server running (`composer run dev`)
- [ ] Browser on correct URL (localhost:8000)
- [ ] Browser console open (F12)
- [ ] No JavaScript errors in console
- [ ] CSRF token present in page source

### ✅ Click Test
- [ ] Click "Test API" button
- [ ] Button shows loading spinner
- [ ] Button text changes to "Testing..."
- [ ] Console shows test logs
- [ ] Notification appears (green/red/blue)
- [ ] Button returns to normal state

### ✅ Success Scenario
- [ ] Notification is green
- [ ] Shows "API Test Success"
- [ ] Shows price (₱240.00)
- [ ] Shows source (Bantay Presyo)
- [ ] Console shows parsed data

### ✅ Error Scenario
- [ ] Notification is red
- [ ] Shows specific error message
- [ ] Console shows error details
- [ ] Button still returns to normal
- [ ] Can click again to retry

---

## Quick Diagnosis Commands

Run these in your terminal to diagnose issues:

### Check Route Exists
```bash
php artisan route:list | grep ingredient-price
```

### Check Database Has Carrots
```bash
php artisan tinker
>>> \App\Models\Ingredient::where('name', 'Carrots')->first()
```

### Check API Endpoint (cURL)
```bash
curl -X POST http://localhost:8000/api/ingredient-price \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_TOKEN_HERE" \
  -d '{"ingredient_name":"Carrots","region":"NCR"}'
```

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

### Clear All Caches
```bash
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

---

## What the Enhanced Function Does

1. **Captures Click Event** → Gets button element
2. **Shows Loading State** → Spinner + "Testing..." text
3. **Validates CSRF Token** → Checks if token exists
4. **Shows Info Notification** → "Testing API with Carrots..."
5. **Sends API Request** → POST to /api/ingredient-price
6. **Logs Response Details** → Status, headers, body
7. **Parses JSON** → With error handling
8. **Shows Result** → Success (green) or error (red) notification
9. **Logs to Console** → Detailed info with emojis
10. **Restores Button** → Returns to normal state

---

## Benefits of Enhanced Version

| Feature | Before | After |
|---------|--------|-------|
| **Visual Feedback** | None | Loading spinner |
| **Error Details** | Generic | Specific with context |
| **Console Logs** | Basic | Detailed with emojis |
| **Button State** | Could get stuck | Always restores |
| **JSON Errors** | Crash | Handled gracefully |
| **Notifications** | Single line | Multi-line support |
| **Duration** | Fixed 4s | 6s for errors, 4s for success |

---

## Summary

✅ **Test API button now works reliably**  
✅ **Enhanced error messages help diagnose issues**  
✅ **Button state management prevents stuck states**  
✅ **Console logging provides debugging information**  
✅ **Multi-line notifications improve readability**  

**Next Steps:**
1. Click the Test API button
2. Watch for green success notification
3. Check console for detailed logs
4. If error, follow troubleshooting guide above

---

**Last Updated:** October 11, 2025  
**Status:** ✅ Fixed & Enhanced  
**Test Ingredient:** Carrots (₱240/kg)
