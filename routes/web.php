<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMarketPriceController;
use App\Http\Controllers\Admin\AdminRecipeController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\RecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    try {
        // Check if database is accessible
        \DB::connection()->getPdo();
        
        // Get sample meals for the landing page (featured first, then by cost)
        $sampleMeals = \App\Models\Meal::whereNotNull('image_path')
            ->orderBy('is_featured', 'desc')
            ->orderBy('cost', 'asc')
            ->limit(6)
            ->get();
    } catch (\Exception $e) {
        // If database query fails, return empty collection
        \Log::warning('Welcome page error: ' . $e->getMessage());
        $sampleMeals = collect([]);
    }
    
    return view('welcome', compact('sampleMeals'));
})->name('welcome');

// Contact page
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Legal pages
Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
})->name('terms-of-service');

Route::get('/policies', function () {
    return view('legal.combined-policies');
})->name('policies.combined');

// Calendar component demo (for development)
Route::get('/calendar-demo', function () {
    return view('components.calendar-demo');
})->name('calendar.demo');

// Nutrition API Test Page (for development/admin)
Route::get('/test-nutrition-api', function () {
    return view('test-nutrition-api');
})->middleware(['auth', 'admin'])->name('test.nutrition.api');

// Emergency Admin Creator Route (DELETE AFTER USE!)
Route::match(['GET', 'POST'], '/emergency-create-admin', function (\Illuminate\Http\Request $request) {
    if ($request->isMethod('post')) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,super_admin',
            ]);
            
            $admin = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => \Hash::make($validated['password']),
                'email_verified_at' => now(),
                'role' => $validated['role'],
                'is_active' => true,
                'timezone' => 'Asia/Manila',
            ]);
            
            return redirect()->route('emergency.admin.create')
                ->with('success', "Admin created! Email: {$admin->email} | Role: {$admin->role}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    
    return view('emergency-admin-create');
})->name('emergency.admin.create');

// Emergency Admin Password Reset Route (DELETE AFTER USE!)
Route::get('/emergency-reset-admin', function () {
    try {
        $admin = \App\Models\User::where('email', 'admin@studeats.com')->first();
        
        if ($admin) {
            $admin->update([
                'password' => \Hash::make('admin123'),
                'email_verified_at' => now(),
                'role' => 'super_admin',
                'is_active' => true,
            ]);
            $message = "✅ Admin password reset! Email: admin@studeats.com | Password: admin123";
        } else {
            $admin = \App\Models\User::create([
                'name' => 'StudEats Admin',
                'email' => 'admin@studeats.com',
                'password' => \Hash::make('admin123'),
                'email_verified_at' => now(),
                'role' => 'super_admin',
                'is_active' => true,
                'timezone' => 'Asia/Manila',
            ]);
            $message = "✅ Admin created! Email: admin@studeats.com | Password: admin123";
        }
        
        return view('emergency-admin-reset', ['message' => $message, 'admin' => $admin]);
    } catch (\Exception $e) {
        return view('emergency-admin-reset', ['error' => $e->getMessage()]);
    }
})->name('emergency.admin.reset');

// Redirect common mistake URL to correct admin login
Route::redirect('/login/admin/login', '/admin/login', 301);
Route::redirect('/login/admin', '/admin/login', 301);

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
});

// Email verification routes (accessible to both guests and authenticated users)
Route::get('/email/verify', [EmailVerificationController::class, 'showVerification'])->name('email.verify.form');
Route::post('/email/verify', [EmailVerificationController::class, 'verifyOtp'])->name('email.verify.otp');
Route::match(['GET', 'POST'], '/email/resend', [EmailVerificationController::class, 'resendOtp'])->name('email.verify.resend');
Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verifyEmail'])->name('email.verify.token');

// Public recipes routes (accessible to all users)
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/search', [RecipeController::class, 'search'])->name('recipes.search');
Route::get('/recipes/{meal}', [RecipeController::class, 'show'])->name('recipes.show');

// Custom storage file serving for Laravel Cloud compatibility
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    
    if (!file_exists($filePath)) {
        \Log::warning('Storage file not found', [
            'requested_path' => $path,
            'full_path' => $filePath,
            'storage_exists' => is_dir(storage_path('app/public')),
            'files_in_meals' => is_dir(storage_path('app/public/meals')) ? array_slice(scandir(storage_path('app/public/meals')), 0, 5) : 'meals dir not found'
        ]);
        abort(404, 'File not found');
    }
    
    $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
    
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000, immutable',
        'Access-Control-Allow-Origin' => '*',
        'Cross-Origin-Resource-Policy' => 'cross-origin',
    ]);
})->where('path', '.*')->name('storage.serve');

// Debug route for file system analysis
Route::get('/debug/storage', function () {
    $data = [
        'storage_directory_exists' => is_dir(storage_path('app/public')),
        'meals_directory_exists' => is_dir(storage_path('app/public/meals')),
        'files_in_meals' => [],
        'meals_with_images' => [],
        'storage_permissions' => substr(sprintf('%o', fileperms(storage_path('app/public'))), -4),
    ];
    
    // Get files in meals directory
    if (is_dir(storage_path('app/public/meals'))) {
        $files = array_diff(scandir(storage_path('app/public/meals')), ['.', '..']);
        $data['files_in_meals'] = array_values($files);
    }
    
    // Get meals from database with image paths
    $meals = \App\Models\Meal::whereNotNull('image_path')->get(['id', 'name', 'image_path']);
    foreach ($meals as $meal) {
        $data['meals_with_images'][] = [
            'id' => $meal->id,
            'name' => $meal->name,
            'image_path' => $meal->image_path,
            'file_exists' => file_exists(storage_path('app/public/' . $meal->image_path)),
            'generated_url' => $meal->image_url,
        ];
    }
    
    return response()->json($data, 200, [], JSON_PRETTY_PRINT);
})->name('debug.storage');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Protected routes (requires authentication and email verification)
Route::middleware(['auth', 'verified', 'no.super.admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Meal Plans
    Route::get('/meal-plans', [MealPlanController::class, 'index'])->name('meal-plans.index');
    Route::get('/meal-plans/create', [MealPlanController::class, 'create'])->name('meal-plans.create');
    Route::get('/meal-plans/weekly', [MealPlanController::class, 'weekly'])->name('meal-plans.weekly');
    Route::post('/meal-plans', [MealPlanController::class, 'store'])->name('meal-plans.store');
    Route::get('/meal-plans/{mealPlan}', [MealPlanController::class, 'show'])->name('meal-plans.show');
    Route::get('/meal-plans/{mealPlan}/edit', [MealPlanController::class, 'edit'])->name('meal-plans.edit');
    Route::put('/meal-plans/{mealPlan}', [MealPlanController::class, 'update'])->name('meal-plans.update');
    Route::delete('/meal-plans/{mealPlan}', [MealPlanController::class, 'destroy'])->name('meal-plans.destroy');
    Route::patch('/meal-plans/{mealPlan}/toggle', [MealPlanController::class, 'toggleCompletion'])->name('meal-plans.toggle');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Profile Photo routes
    Route::post('/profile/photo/upload', [ProfilePhotoController::class, 'upload'])->name('profile.photo.upload');
    Route::post('/profile/photo/crop', [ProfilePhotoController::class, 'crop'])->name('profile.photo.crop');
    Route::delete('/profile/photo', [ProfilePhotoController::class, 'delete'])->name('profile.photo.delete');
    Route::get('/profile/photo/status', [ProfilePhotoController::class, 'status'])->name('profile.photo.status');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/system-health', [AdminDashboardController::class, 'systemHealth'])->name('system-health');

    // Admin Registration (super_admin only)
    Route::middleware('super.admin')->group(function () {
        Route::get('/register-new', [\App\Http\Controllers\Admin\AdminRegistrationController::class, 'showStandaloneRegistrationForm'])->name('register.standalone');
        Route::post('/register-new', [\App\Http\Controllers\Admin\AdminRegistrationController::class, 'standaloneRegister'])->name('register.standalone.submit');
    });

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/export', [AdminUserController::class, 'export'])->name('users.export');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::patch('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::patch('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Recipe Management
    Route::resource('recipes', AdminRecipeController::class);
    Route::post('recipes/{recipe}/toggle-featured', [AdminRecipeController::class, 'toggleFeatured'])->name('recipes.toggle-featured');

    // Analytics
    Route::get('/analytics/data', [AnalyticsController::class, 'getData'])->name('analytics.data');
    Route::get('/analytics/hourly', [AnalyticsController::class, 'getHourlyActivity'])->name('analytics.hourly');
    Route::post('/analytics/refresh', [AnalyticsController::class, 'refresh'])->name('analytics.refresh');

    // Market Prices
    Route::get('/market-prices', [AdminMarketPriceController::class, 'index'])->name('market-prices.index');
    Route::post('/market-prices/update', [AdminMarketPriceController::class, 'update'])->name('market-prices.update');
    Route::get('/market-prices/stats', [AdminMarketPriceController::class, 'stats'])->name('market-prices.stats');
    Route::get('/market-prices/ingredients', [AdminMarketPriceController::class, 'ingredients'])->name('market-prices.ingredients');
    Route::get('/market-prices/{ingredient}/history', [AdminMarketPriceController::class, 'history'])->name('market-prices.history');
});

// API Routes for Dynamic Pricing System
Route::prefix('api')->group(function () {
    Route::post('/ingredient-price', [App\Http\Controllers\Api\IngredientPriceController::class, 'getPrice']);
    Route::get('/ingredients-list', [App\Http\Controllers\Api\IngredientPriceController::class, 'getIngredientsList']);
    Route::post('/ingredient-prices/bulk', [App\Http\Controllers\Api\IngredientPriceController::class, 'bulkUpdatePrices']);
    Route::get('/pricing-stats', [App\Http\Controllers\Api\IngredientPriceController::class, 'getPricingStats']);
    
    // Market Prices Search API (keep auth for admin-only routes)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/market-prices/search', [AdminMarketPriceController::class, 'search']);
        Route::post('/ingredient-price/{ingredient}/refresh', [AdminMarketPriceController::class, 'refreshSingle']);
        
        // Nutrition API endpoints removed - using public API routes instead
    });
    
    // Admin-only API routes without CSRF protection (for seamless admin experience)
    Route::middleware(['auth', 'admin'])->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\VerifyCsrfToken::class
    ])->prefix('admin-api')->group(function () {
        Route::post('/ingredient-price', [App\Http\Controllers\Api\IngredientPriceController::class, 'getPrice']);
        // Nutrition API endpoints removed - using public API routes instead
    });
});

// Health check route for Railway
Route::get('/health', function () {
    try {
        // Check database connection
        DB::connection()->getPdo();
        
        return response()->json([
            'status' => 'healthy',
            'database' => 'connected',
            'timestamp' => now()->toISOString()
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'database' => 'disconnected',
            'error' => $e->getMessage(),
            'timestamp' => now()->toISOString()
        ], 503);
    }
});

// Loading Examples (for development)
Route::get('/loading-examples', function () {
    return view('loading-examples');
})->name('loading-examples');

// FlyonUI Loading Buttons Demo
Route::get('/loading-buttons-demo', function () {
    return view('components.loading-button-demo');
})->name('loading-buttons.demo');

// FlyonUI Loading Spinners Demo
Route::get('/loading-spinners-demo', function () {
    return view('components.loading-spinner-demo');
})->name('loading-spinners.demo');

// Spinner Animation Test
Route::get('/spinner-test', function () {
    return view('spinner-test');
})->name('spinner.test');

// Font Loading Test
Route::get('/font-test', function () {
    return view('font-test');
})->name('font.test');

// Laravel Cloud Debug Route (remove after use)
Route::get('/debug-deployment', function () {
    return response()->json([
        'app_name' => config('app.name'),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'app_key_set' => !empty(config('app.key')),
        'database_configured' => !empty(config('database.connections.mysql.host')),
        'mail_configured' => !empty(config('mail.mailers.smtp.host')),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'storage_writeable' => is_writable(storage_path()),
        'cache_writeable' => is_writable(storage_path('framework/cache')),
        'session_writeable' => is_writable(storage_path('framework/sessions')),
        'timestamp' => now()->toISOString()
    ]);
});

// CSRF Test Page (for development and testing)
Route::get('/csrf-test', function () {
    return view('csrf-test');
})->name('csrf.test');

// CSRF Test Form Submission
Route::post('/csrf-test', function (Request $request) {
    return view('csrf-test-success', [
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'message' => $request->input('message'),
        'csrf_token' => $request->input('_token')
    ]);
})->name('csrf.test.submit');

// TEMPORARY DEBUG ROUTE - REMOVE AFTER TESTING
Route::get('/debug-cookies', function () {
    $cookies = request()->cookies->all();
    $sessionConfig = [
        'cookie_name' => config('session.cookie'),
        'domain' => config('session.domain'),
        'secure' => config('session.secure'),
        'same_site' => config('session.same_site'),
        'partitioned' => config('session.partitioned'),
        'app_env' => config('app.env'),
        'app_url' => config('app.url'),
    ];
    
    return response()->json([
        'session_config' => $sessionConfig,
        'received_cookies' => array_keys($cookies),
        'cookie_details' => $cookies,
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token(),
        'current_domain' => request()->getHost(),
        'is_secure' => request()->isSecure(),
        'user_agent' => request()->userAgent(),
        'headers' => request()->headers->all(),
    ]);
})->middleware('web');
