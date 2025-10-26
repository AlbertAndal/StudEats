# Adding Market Prices to Admin Navigation

## Option 1: Quick Add to Sidebar

If you have an admin sidebar/navigation, add this link:

```html
<!-- Market Prices Link -->
<a href="{{ route('admin.market-prices.index') }}" 
   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.market-prices.*') ? 'bg-gray-100 font-semibold' : '' }}">
    <svg class="w-5 h-5 mr-3 lucide lucide-trending-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="22,7 13.5,15.5 8.5,10.5 2,17"/>
        <polyline points="16,7 22,7 22,13"/>
    </svg>
    Market Prices
</a>
```

## Option 2: Add to Dashboard Quick Links

Add a card/button on the admin dashboard:

```html
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <a href="{{ route('admin.market-prices.index') }}" class="flex items-center justify-between hover:bg-gray-50 transition-colors p-4 rounded-lg">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600 lucide lucide-trending-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22,7 13.5,15.5 8.5,10.5 2,17"/>
                    <polyline points="16,7 22,7 22,13"/>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Market Prices</h3>
                <p class="text-sm text-gray-500">Update ingredient prices from Bantay Presyo</p>
            </div>
        </div>
        <svg class="w-5 h-5 text-gray-400 lucide lucide-chevron-right" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m9 18 6-6-6-6"/>
        </svg>
    </a>
</div>
```

## Direct Access

You can always access the page directly at:
```
/admin/market-prices
```

Or use the route helper in any view:
```php
<a href="{{ route('admin.market-prices.index') }}">Market Prices</a>
```

## Navigation Menu Suggestion

If you have a `resources/views/admin/partials/navigation.blade.php` or similar:

```html
<nav class="mt-6">
    <a href="{{ route('admin.dashboard') }}" class="...">Dashboard</a>
    <a href="{{ route('admin.users.index') }}" class="...">Users</a>
    <a href="{{ route('admin.recipes.index') }}" class="...">Recipes</a>
    
    <!-- ADD THIS -->
    <a href="{{ route('admin.market-prices.index') }}" 
       class="flex items-center px-4 py-2 mt-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('admin.market-prices.*') ? 'bg-gray-100' : '' }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="22,7 13.5,15.5 8.5,10.5 2,17"/>
            <polyline points="16,7 22,7 22,13"/>
        </svg>
        Market Prices
    </a>
</nav>
```

## Icon Options

You can use different icons:

**Trending Up (Current):**
```html
<svg class="lucide lucide-trending-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="22,7 13.5,15.5 8.5,10.5 2,17"/>
    <polyline points="16,7 22,7 22,13"/>
</svg>
```

**Dollar Sign:**
```html
<svg class="lucide lucide-dollar-sign" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <line x1="12" x2="12" y1="2" y2="22"/>
    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
</svg>
```

**Shopping Cart:**
```html
<svg class="lucide lucide-shopping-cart" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="8" cy="21" r="1"/>
    <circle cx="19" cy="21" r="1"/>
    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
</svg>
```

## Testing the Link

After adding the link, test it:
1. Log in as admin
2. Click the new "Market Prices" link
3. Verify you see the Market Prices dashboard
4. Click "Update Market Prices" to test functionality
