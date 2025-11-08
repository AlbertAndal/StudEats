<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                        <?php echo e(__('Browse Recipes')); ?>

                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">Discover delicious meal ideas</p>
                </div>
            </div>
            <?php if($recipes->count() > 0): ?>
                <div class="text-sm text-gray-500">
                    <span class="font-semibold text-gray-900"><?php echo e($recipes->total()); ?></span> recipes available
                </div>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6 hover:shadow-md transition-shadow">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50/50 to-white border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 bg-green-500/10 rounded-lg flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Search & Filter</h3>
                            <p class="text-xs text-gray-500">Find your perfect recipe</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" action="<?php echo e(route('recipes.index')); ?>" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                                           placeholder="Search recipes..." 
                                           class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all">
                                </div>
                            </div>

                            <!-- Cuisine Type Filter -->
                            <div>
                                <label for="cuisine_type" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Cuisine Type</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-lg">🍽️</span>
                                    </div>
                                    <select name="cuisine_type" id="cuisine_type" 
                                            class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all">
                                        <option value="">All Cuisines</option>
                                        <?php $__currentLoopData = $cuisineTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type); ?>" <?php echo e(request('cuisine_type') == $type ? 'selected' : ''); ?>>
                                                <?php echo e(ucfirst($type)); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-end">
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-lg shadow-sm hover:shadow-md transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Search
                                </button>
                            </div>
                        </div>
                        <?php if(request('search') || request('cuisine_type')): ?>
                            <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500">Active filters:</span>
                                    <?php if(request('search')): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            "<?php echo e(request('search')); ?>"
                                            <button type="button" onclick="document.getElementById('search').value=''; this.closest('form').submit();" class="hover:text-green-900">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                                    <?php endif; ?>
                                    <?php if(request('cuisine_type')): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            <?php echo e(ucfirst(request('cuisine_type'))); ?>

                                            <button type="button" onclick="document.getElementById('cuisine_type').value=''; this.closest('form').submit();" class="hover:text-blue-900">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo e(route('recipes.index')); ?>" class="text-xs text-gray-600 hover:text-gray-900 font-medium">
                                    Clear all
                                </a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Recipes Grid -->
            <?php if($recipes->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <?php $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('recipes.show', $recipe->id)); ?>" class="group bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            <!-- Recipe Image -->
                            <div class="relative h-48 overflow-hidden bg-gray-100">
                                <?php if($recipe->image_path): ?>
                                    <img src="<?php echo e(Storage::url($recipe->image_path)); ?>" 
                                         alt="<?php echo e($recipe->name); ?>" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100">
                                        <span class="text-6xl">🍽️</span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Featured Badge -->
                                <?php if($recipe->is_featured): ?>
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-white shadow-lg">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Featured
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <!-- Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>

                            <!-- Recipe Info -->
                            <div class="p-4">
                                <div class="mb-2">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors line-clamp-1">
                                        <?php echo e($recipe->name); ?>

                                    </h3>
                                    <?php if($recipe->description): ?>
                                        <p class="text-sm text-gray-600 line-clamp-2 mt-1">
                                            <?php echo e($recipe->description); ?>

                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- Recipe Meta -->
                                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <?php if($recipe->cuisine_type): ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-indigo-100 text-indigo-700">
                                                <span class="text-sm">🍽️</span>
                                                <?php echo e(ucfirst($recipe->cuisine_type)); ?>

                                            </span>
                                        <?php endif; ?>
                                        <?php if($recipe->cost): ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-700">
                                                <span class="text-sm">💰</span>
                                                ₱<?php echo e(number_format($recipe->cost, 2)); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($recipe->nutritionalInfo): ?>
                                        <div class="flex items-center gap-1 px-2 py-1 rounded-lg bg-green-100">
                                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            <span class="text-xs font-semibold text-green-700">
                                                <?php echo e(number_format($recipe->nutritionalInfo->calories)); ?> cal
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- View Recipe Button -->
                                <div class="mt-3">
                                    <div class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 text-green-700 font-semibold rounded-lg border border-green-200 group-hover:border-green-300 transition-all">
                                        View Recipe
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                    <?php echo e($recipes->links()); ?>

                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No recipes found</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            <?php if(request('search') || request('cuisine_type')): ?>
                                We couldn't find any recipes matching your search. Try adjusting your filters or search terms.
                            <?php else: ?>
                                Check back later for delicious recipes!
                            <?php endif; ?>
                        </p>
                        <?php if(request('search') || request('cuisine_type')): ?>
                            <a href="<?php echo e(route('recipes.index')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Clear Filters & View All
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\StudEats\resources\views/recipes/index.blade.php ENDPATH**/ ?>