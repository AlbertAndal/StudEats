<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Meal;
use App\Models\NutritionalInfo;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminRecipeController extends Controller
{
    public function index(Request $request)
    {
        $query = Meal::with(['recipe', 'nutritionalInfo'])->withCount('mealPlans');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('cuisine_type', 'like', "%{$search}%");
            });
        }

        // Filter by cuisine type
        if ($request->filled('cuisine_type') && $request->cuisine_type !== '') {
            $query->where('cuisine_type', $request->cuisine_type);
        }

        // Filter by difficulty
        if ($request->filled('difficulty') && $request->difficulty !== '') {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter by featured status
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === '1');
        }

        $recipes = $query->latest()->paginate($request->get('per_page', 15))->withQueryString();

        $stats = [
            'total' => Meal::count(),
            'featured' => Meal::where('is_featured', true)->count(),
            'filipino' => Meal::where('cuisine_type', 'Filipino')->count(),
            'easy' => Meal::where('difficulty', 'easy')->count(),
        ];

        $cuisineTypes = Meal::distinct()->pluck('cuisine_type');
        $difficulties = ['easy', 'medium', 'hard'];

        return view('admin.recipes.index', compact('recipes', 'stats', 'cuisineTypes', 'difficulties'));
    }

    public function create()
    {
        $cuisineTypes = Meal::distinct()->pluck('cuisine_type');
        $difficulties = ['easy', 'medium', 'hard'];

        return view('admin.recipes.create', compact('cuisineTypes', 'difficulties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'calories' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
            'cuisine_type' => 'required|string|max:100',
            'difficulty' => 'required|in:easy,medium,hard',
            'meal_type' => 'required|in:breakfast,lunch,snack,dinner',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Recipe fields - new structured format
            'ingredient_names' => 'required|array|min:1',
            'ingredient_names.*' => 'required|string|max:100',
            'ingredient_quantities' => 'required|array|min:1',
            'ingredient_quantities.*' => 'required|numeric|min:0',
            'ingredient_units' => 'required|array|min:1',
            'ingredient_units.*' => 'required|string|max:50',
            'ingredient_prices' => 'nullable|array',
            'ingredient_prices.*' => 'nullable|numeric|min:0',
            'instructions' => 'required|string',
            'prep_time' => 'required|integer|min:1',
            'cook_time' => 'required|integer|min:1',
            'servings' => 'required|integer|min:1',

            
            // Nutritional info fields
            'protein' => 'required|numeric|min:0',
            'carbs' => 'required|numeric|min:0',
            'fats' => 'required|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'sugar' => 'nullable|numeric|min:0',
            'sodium' => 'nullable|numeric|min:0',
        ]);

        // Transform structured ingredient data into array format
        $ingredients = [];
        $names = $validated['ingredient_names'];
        $quantities = $validated['ingredient_quantities'];
        $units = $validated['ingredient_units'];
        $prices = $validated['ingredient_prices'] ?? [];

        for ($i = 0; $i < count($names); $i++) {
            $ingredients[] = [
                'name' => $names[$i],
                'amount' => $quantities[$i],
                'unit' => $units[$i],
                'price' => $prices[$i] ?? null,
            ];
        }

        DB::transaction(function () use ($validated, $request, $ingredients) {
            // Handle image upload with enhanced validation
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Additional validation
                if ($image->isValid()) {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('meals', $filename, 's3');
                    
                    // Verify the file was stored successfully
                    if (!Storage::disk('s3')->exists($imagePath)) {
                        throw new \Exception('Failed to store image file');
                    }
                } else {
                    throw new \Exception('Invalid image file uploaded');
                }
            }

            // Create meal
            $meal = Meal::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'calories' => $validated['calories'],
                'cost' => $validated['cost'],
                'cuisine_type' => $validated['cuisine_type'],
                'difficulty' => $validated['difficulty'],
                'meal_type' => $validated['meal_type'],
                'image_path' => $imagePath,
                'is_featured' => $validated['is_featured'] ?? false,
            ]);

            // Create recipe
            Recipe::create([
                'meal_id' => $meal->id,
                'ingredients' => $ingredients,
                'instructions' => $validated['instructions'],
                'prep_time' => $validated['prep_time'],
                'cook_time' => $validated['cook_time'],
                'servings' => $validated['servings'],

            ]);

            // Create nutritional info
            NutritionalInfo::create([
                'meal_id' => $meal->id,
                'calories' => $validated['calories'],
                'protein' => $validated['protein'],
                'carbs' => $validated['carbs'],
                'fats' => $validated['fats'],
                'fiber' => $validated['fiber'] ?? 0,
                'sugar' => $validated['sugar'] ?? 0,
                'sodium' => $validated['sodium'] ?? 0,
            ]);

            AdminLog::createLog(
                Auth::id(),
                'recipe_created',
                "Created new recipe: {$meal->name}",
                $meal
            );
        });

        return redirect()->route('admin.recipes.index')->with('success', 'Recipe created successfully!');
    }

    public function show(Meal $recipe)
    {
        $recipe->load(['recipe', 'nutritionalInfo', 'mealPlans.user']);
        
        return view('admin.recipes.show', compact('recipe'));
    }

    public function edit(Meal $recipe)
    {
        $recipe->load(['recipe', 'nutritionalInfo']);
        $cuisineTypes = Meal::distinct()->pluck('cuisine_type');
        $difficulties = ['easy', 'medium', 'hard'];

        return view('admin.recipes.edit', compact('recipe', 'cuisineTypes', 'difficulties'));
    }

    public function update(Request $request, Meal $recipe)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'calories' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
            'cuisine_type' => 'required|string|max:100',
            'difficulty' => 'required|in:easy,medium,hard',
            'meal_type' => 'required|in:breakfast,lunch,snack,dinner',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Recipe fields - new structured format
            'ingredient_names' => 'nullable|array',
            'ingredient_names.*' => 'nullable|string|max:100',
            'ingredient_quantities' => 'nullable|array',
            'ingredient_quantities.*' => 'nullable|numeric|min:0',
            'ingredient_units' => 'nullable|array',
            'ingredient_units.*' => 'nullable|string|max:50',
            'ingredient_prices' => 'nullable|array',
            'ingredient_prices.*' => 'nullable|numeric|min:0',
            'instructions' => 'nullable|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',

            
            // Nutritional info fields
            'protein' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'fats' => 'nullable|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'sugar' => 'nullable|numeric|min:0',
            'sodium' => 'nullable|numeric|min:0',
        ]);

        // Transform structured ingredient data into array format
        $ingredients = [];
        if (!empty($validated['ingredient_names'])) {
            $names = $validated['ingredient_names'];
            $quantities = $validated['ingredient_quantities'] ?? [];
            $units = $validated['ingredient_units'] ?? [];
            $prices = $validated['ingredient_prices'] ?? [];

            for ($i = 0; $i < count($names); $i++) {
                // Only add ingredient if name is not empty
                if (!empty($names[$i]) && !empty($units[$i]) && isset($quantities[$i])) {
                    $ingredients[] = [
                        'name' => $names[$i],
                        'amount' => $quantities[$i],
                        'unit' => $units[$i],
                        'price' => $prices[$i] ?? null,
                    ];
                }
            }
        }

        DB::transaction(function () use ($validated, $request, $recipe, $ingredients) {
            // Handle image upload with enhanced validation
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                if ($image->isValid()) {
                    // Delete old image
                    if ($recipe->image_path && Storage::disk('s3')->exists($recipe->image_path)) {
                        Storage::disk('s3')->delete($recipe->image_path);
                    }
                    
                    // Store new image with unique filename
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('meals', $filename, 's3');
                    
                    // Verify the file was stored successfully
                    if (Storage::disk('s3')->exists($imagePath)) {
                        $validated['image_path'] = $imagePath;
                    } else {
                        throw new \Exception('Failed to store image file');
                    }
                } else {
                    throw new \Exception('Invalid image file uploaded');
                }
            }

            // Update meal
            $recipe->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'calories' => $validated['calories'],
                'cost' => $validated['cost'],
                'cuisine_type' => $validated['cuisine_type'],
                'difficulty' => $validated['difficulty'],
                'meal_type' => $validated['meal_type'],
                'image_path' => $validated['image_path'] ?? $recipe->image_path,
                'is_featured' => $request->boolean('is_featured'),
            ]);

            // Update or create recipe details if any recipe data is provided
            if (!empty($ingredients) || !empty($validated['instructions']) || 
                isset($validated['prep_time']) || isset($validated['cook_time']) || isset($validated['servings'])) {
                
                $recipeData = [
                    'ingredients' => !empty($ingredients) ? $ingredients : ($recipe->recipe->ingredients ?? []),
                    'instructions' => $validated['instructions'] ?? '',
                    'prep_time' => $validated['prep_time'] ?? 0,
                    'cook_time' => $validated['cook_time'] ?? 0,
                    'servings' => $validated['servings'] ?? 1,

                ];

                if ($recipe->recipe) {
                    $recipe->recipe()->update($recipeData);
                } else {
                    $recipe->recipe()->create($recipeData);
                }
            }

            // Update or create nutritional info if any nutritional data is provided
            if (isset($validated['protein']) || isset($validated['carbs']) || isset($validated['fats']) ||
                isset($validated['fiber']) || isset($validated['sugar']) || isset($validated['sodium'])) {
                
                $nutritionalData = [
                    'calories' => $validated['calories'],
                    'protein' => $validated['protein'] ?? 0,
                    'carbs' => $validated['carbs'] ?? 0,
                    'fats' => $validated['fats'] ?? 0,
                    'fiber' => $validated['fiber'] ?? 0,
                    'sugar' => $validated['sugar'] ?? 0,
                    'sodium' => $validated['sodium'] ?? 0,
                ];

                if ($recipe->nutritionalInfo) {
                    $recipe->nutritionalInfo()->update($nutritionalData);
                } else {
                    $recipe->nutritionalInfo()->create($nutritionalData);
                }
            }

            AdminLog::createLog(
                Auth::id(),
                'recipe_updated',
                "Updated recipe: {$recipe->name}",
                $recipe
            );
        });

        return redirect()->route('admin.recipes.index')->with('success', 'Recipe updated successfully!');
    }

    public function destroy(Meal $recipe)
    {
        DB::transaction(function () use ($recipe) {
            // Delete image file
            if ($recipe->image_path) {
                Storage::disk('s3')->delete($recipe->image_path);
            }

            AdminLog::createLog(
                Auth::id(),
                'recipe_deleted',
                "Deleted recipe: {$recipe->name}",
                $recipe
            );

            $recipe->delete();
        });

        return back()->with('success', 'Recipe deleted successfully!');
    }

    /**
     * Toggle the featured status of a recipe.
     */
    public function toggleFeatured(Meal $recipe)
    {
        try {
            $recipe->update(['is_featured' => !$recipe->is_featured]);

            AdminLog::createLog(
                Auth::id(),
                'toggle_featured_recipe',
                "Marked recipe '{$recipe->name}' as " . ($recipe->is_featured ? 'featured' : 'not featured'),
                $recipe
            );

            return response()->json([
                'success' => true, 
                'is_featured' => $recipe->is_featured
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to toggle featured status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update featured status'], 500);
        }
    }
}
