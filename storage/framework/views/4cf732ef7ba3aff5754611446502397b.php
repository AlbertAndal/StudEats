<?php $__env->startSection('title', 'Recipe Management'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Recipe Management</h1>
                <p class="mt-2 text-gray-600">Manage all recipes and meal options in the system</p>
            </div>
            <a href="<?php echo e(route('admin.recipes.create')); ?>" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 mr-2 lucide lucide-plus" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="M12 5v14"/>
                </svg>
                Add New Recipe
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 lucide lucide-book-open" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($recipes->total()); ?></p>
                        <p class="text-sm text-gray-600">Total Recipes</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($recipes->where('is_featured', true)->count()); ?></p>
                        <p class="text-sm text-gray-600">Featured</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($cuisineTypes->count()); ?></p>
                        <p class="text-sm text-gray-600">Cuisine Types</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($recipes->sum('meal_plans_count'))); ?></p>
                        <p class="text-sm text-gray-600">Total Plans</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="<?php echo e(route('admin.recipes.index')); ?>" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Recipes</label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="<?php echo e(request('search')); ?>" 
                               placeholder="Search by name, cuisine, or ingredients..." 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cuisine Type</label>
                    <select name="cuisine_type" class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Cuisines</option>
                        <?php $__currentLoopData = $cuisineTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuisine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cuisine); ?>" <?php echo e(request('cuisine_type') === $cuisine ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($cuisine)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="sm:w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty</label>
                    <select name="difficulty" class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Levels</option>
                        <option value="easy" <?php echo e(request('difficulty') === 'easy' ? 'selected' : ''); ?>>Easy</option>
                        <option value="medium" <?php echo e(request('difficulty') === 'medium' ? 'selected' : ''); ?>>Medium</option>
                        <option value="hard" <?php echo e(request('difficulty') === 'hard' ? 'selected' : ''); ?>>Hard</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                        Search
                    </button>
                    <?php if(request()->hasAny(['search', 'cuisine_type', 'difficulty'])): ?>
                        <a href="<?php echo e(route('admin.recipes.index')); ?>" 
                           class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Recipe Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Recipes
                        <span class="text-sm font-normal text-gray-500 ml-2">
                            (<?php echo e($recipes->firstItem() ?? 0); ?>-<?php echo e($recipes->lastItem() ?? 0); ?> of <?php echo e($recipes->total()); ?>)
                        </span>
                    </h3>
                    <div class="flex items-center gap-3">
                        <select onchange="changePerPage(this.value)" class="text-sm border-gray-300 rounded-lg">
                            <option value="10" <?php echo e(request('per_page', 15) == 10 ? 'selected' : ''); ?>>10 per page</option>
                            <option value="15" <?php echo e(request('per_page', 15) == 15 ? 'selected' : ''); ?>>15 per page</option>
                            <option value="25" <?php echo e(request('per_page', 15) == 25 ? 'selected' : ''); ?>>25 per page</option>
                            <option value="50" <?php echo e(request('per_page', 15) == 50 ? 'selected' : ''); ?>>50 per page</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipe</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <?php if($recipe->image_path && $recipe->image_url): ?>
                                            <img src="<?php echo e($recipe->image_url); ?>" 
                                                 alt="<?php echo e($recipe->name); ?>" 
                                                 class="w-16 h-16 rounded-lg object-cover mr-4"
                                                 title="Image URL: <?php echo e($recipe->image_url); ?>"
                                                 onerror="console.error('Image failed to load:', '<?php echo e($recipe->image_url); ?>'); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-16 h-16 bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg flex items-center justify-center text-white font-bold text-lg mr-4" style="display:none;" title="Image failed to load">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        <?php else: ?>
                                            <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold text-lg mr-4" title="No image available">
                                                <?php echo e(strtoupper(substr($recipe->name, 0, 2))); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($recipe->name); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo e(Str::limit($recipe->description, 50)); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo e(ucfirst($recipe->cuisine_type)); ?>

                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'bg-green-100 text-green-800' => $recipe->difficulty === 'easy',
                                                'bg-yellow-100 text-yellow-800' => $recipe->difficulty === 'medium',
                                                'bg-red-100 text-red-800' => $recipe->difficulty === 'hard',
                                            ]); ?>">
                                                <?php echo e(ucfirst($recipe->difficulty)); ?>

                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span class="inline-flex items-center mr-4">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <?php echo e($recipe->cooking_time); ?>m
                                            </span>
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                                </svg>
                                                <?php echo e($recipe->servings); ?> servings
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e(number_format($recipe->meal_plans_count)); ?> plans</div>
                                        <div class="text-sm text-gray-500">Added <?php echo e($recipe->created_at->diffForHumans()); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        <?php if($recipe->is_featured): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                                </svg>
                                                Featured
                                            </span>
                                        <?php endif; ?>
                                        <div class="text-xs text-gray-500">
                                            Last updated <?php echo e($recipe->updated_at->diffForHumans()); ?>

                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="<?php echo e(route('admin.recipes.show', $recipe)); ?>" 
                                           class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </a>
                                        <a href="<?php echo e(route('admin.recipes.edit', $recipe)); ?>" 
                                           class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                            </svg>
                                        </a>
                    <button data-toggle-featured="<?php echo e($recipe->id); ?>" 
                        class="p-2 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded-lg transition-colors" title="Toggle Featured">
                                            <?php if($recipe->is_featured): ?>
                                                <!-- Solid Star (featured) -->
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M11.48 3.499a.562.562 0 01.04.53l-2.125 5.111a.563.563 0 01-.475.345l-5.518.442a.562.562 0 00-.321.988l4.204 3.602c.162.139.23.357.182.557l-1.285 5.386a.562.562 0 00.84.61l4.725-2.885a.563.563 0 01.586 0l4.725 2.885a.562.562 0 00.84-.61l-1.285-5.386a.563.563 0 01.182-.557l4.204-3.602a.562.562 0 00-.321-.988l-5.518-.442a.563.563 0 01-.475-.345L12.52 3.999a.562.562 0 01.04-.53c-.354-.63-1.266-.63-1.52 0z" clip-rule="evenodd" />
                                                </svg>
                                            <?php else: ?>
                                                <!-- Outline Star (not featured) -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.977 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.977-2.888a1 1 0 00-1.176 0l-3.977 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.52-4.674z" />
                                                </svg>
                                            <?php endif; ?>
                                        </button>
                    <button data-delete-recipe="<?php echo e($recipe->id); ?>" data-recipe-name="<?php echo e(e($recipe->name)); ?>" 
                                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" title="Delete Recipe">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No recipes found</h3>
                                        <p class="text-gray-500 mb-6"><?php echo e(request()->hasAny(['search', 'cuisine_type', 'difficulty']) ? 'Try adjusting your search filters.' : 'Get started by creating your first recipe.'); ?></p>
                                        <?php if(!request()->hasAny(['search', 'cuisine_type', 'difficulty'])): ?>
                                            <a href="<?php echo e(route('admin.recipes.create')); ?>" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                                </svg>
                                                Create First Recipe
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($recipes->hasPages()): ?>
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <?php echo e($recipes->appends(request()->query())->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 p-4 transition-opacity duration-300" style="display: none;">
    <div class="flex items-center justify-center min-h-full">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all duration-300 scale-95 modal-content">
        <!-- Modal Header with Icon -->
        <div class="p-6 pb-4">
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Recipe</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Are you sure you want to delete <span class="font-semibold text-gray-900">'<span id="deleteRecipeName"></span>'</span>?
                    </p>
                    <p class="text-sm text-red-600 font-medium mt-2">
                        ⚠️ This action cannot be undone.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Modal Actions -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl flex gap-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 px-5 py-2.5 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                Cancel
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" 
                        class="w-full px-5 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg hover:shadow-xl">
                    <span class="flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                        Delete Recipe
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

function toggleFeatured(recipeId) {
    fetch(`/admin/recipes/${recipeId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update featured status');
    });
}

function deleteRecipe(recipeId, recipeName) {
    document.getElementById('deleteRecipeName').textContent = recipeName;
    document.getElementById('deleteForm').action = `/admin/recipes/${recipeId}`;
    
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    
    // Trigger animation
    setTimeout(() => {
        modal.style.opacity = '1';
        const content = modal.querySelector('.modal-content');
        if (content) {
            content.style.transform = 'scale(1)';
        }
    }, 10);
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.opacity = '0';
    
    const content = modal.querySelector('.modal-content');
    if (content) {
        content.style.transform = 'scale(0.95)';
    }
    
    // Restore body scroll
    document.body.style.overflow = '';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }, 300);
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('deleteModal');
        if (!modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    }
});

// Delegate clicks for feature toggle and delete to avoid inline handlers
document.addEventListener('click', function(e) {
    const featureBtn = e.target.closest('[data-toggle-featured]');
    if (featureBtn) {
        const id = featureBtn.getAttribute('data-toggle-featured');
        toggleFeatured(id);
        return;
    }
    const deleteBtn = e.target.closest('[data-delete-recipe]');
    if (deleteBtn) {
        const id = deleteBtn.getAttribute('data-delete-recipe');
        const name = deleteBtn.getAttribute('data-recipe-name');
        deleteRecipe(id, name);
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\StudEats\resources\views/admin/recipes/index.blade.php ENDPATH**/ ?>