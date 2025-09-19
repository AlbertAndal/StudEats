

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Overview</h1>
            <p class="mt-2 text-gray-600">Welcome back! Here's your StudEats daily update.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <!-- Heroicons Users Icon (Solid) -->
                            <svg class="w-7 h-7 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 017 18a9.953 9.953 0 01-5.385-1.572zM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 00-1.588-3.755 4.502 4.502 0 015.874 2.636.818.818 0 01-.36.98A7.465 7.465 0 0114.5 16z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-blue-100">Total Users</h3>
                        <p class="text-3xl font-bold"><?php echo e(number_format($stats['total_users'])); ?></p>
                        <p class="text-xs text-blue-100 mt-1">
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/>
                                </svg>
                                +<?php echo e($stats['recent_registrations']); ?> this week
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <!-- Enhanced Active Users Icon with pulse animation -->
                            <svg class="w-7 h-7 text-green-200" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                                <circle cx="12" cy="12" r="3" opacity="0.7"/>
                                <circle cx="12" cy="12" r="1.5">
                                    <animate attributeName="r" values="1.5;3;1.5" dur="2s" repeatCount="indefinite"/>
                                    <animate attributeName="opacity" values="1;0.3;1" dur="2s" repeatCount="indefinite"/>
                                </circle>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-green-100">Active Users</h3>
                        <p class="text-3xl font-bold"><?php echo e(number_format($stats['active_users'])); ?></p>
                        <p class="text-xs text-green-100 mt-1">
                            <?php echo e(number_format(($stats['active_users'] / $stats['total_users']) * 100, 1)); ?>% of total
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <!-- Enhanced Recipe/Cookbook Icon -->
                            <svg class="w-7 h-7 text-yellow-200" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 4h2v5l-1-.75L9 9V4zm9 16H6V4h1v9l3-2.25L13 13V4h5v16z"/>
                                <circle cx="8" cy="15" r="1"/>
                                <circle cx="12" cy="15" r="1"/>
                                <circle cx="16" cy="15" r="1"/>
                                <path d="M8 17h8v1H8z" opacity="0.7"/>
                                <path d="M8 19h6v1H8z" opacity="0.7"/>
                                <path d="M8 19h6v1H8z" opacity="0.5"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-yellow-100">Total Recipes</h3>
                        <p class="text-3xl font-bold"><?php echo e(number_format($stats['total_meals'])); ?></p>
                        <p class="text-xs text-yellow-100 mt-1"><?php echo e($stats['featured_meals']); ?> featured</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <!-- Enhanced System Status Icon with subtle animation -->
                            <svg class="w-7 h-7 text-purple-200" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" opacity="0.8"/>
                                <circle cx="12" cy="12" r="2" fill="currentColor">
                                    <animate attributeName="opacity" values="0.5;1;0.5" dur="3s" repeatCount="indefinite"/>
                                </circle>
                                <path d="M12 6v12M18 12H6" stroke="currentColor" stroke-width="1" opacity="0.6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-purple-100">System Status</h3>
                        <p class="text-lg font-bold">Operational</p>
                        <p class="text-xs text-purple-100 mt-1">All systems running</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Activities -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Admin Activities</h3>
                        <span class="text-sm text-gray-500">Last 24 hours</span>
                    </div>
                </div>
                <div class="p-6">
                    <?php if($recentActivities->count() > 0): ?>
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <div class="relative pb-8">
                                            <?php if(!$loop->last): ?>
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                            <?php endif; ?>
                                            <div class="relative flex items-start gap-3">
                                                <div class="relative">
                                                    <div class="h-8 w-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414L9 13.414l4.707-4.707z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div>
                                                        <p class="text-sm text-gray-900 font-medium"><?php echo e($activity->description); ?></p>
                                                        <div class="mt-1 flex items-center gap-4 text-xs text-gray-500">
                                                            <span><?php echo e($activity->adminUser->name); ?></span>
                                                            <span>•</span>
                                                            <span><?php echo e($activity->created_at->diffForHumans()); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            <p class="text-gray-500 font-medium">No recent activities</p>
                            <p class="text-sm text-gray-400">Admin activities will appear here</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- System Health -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">System Health</h3>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm text-green-600 font-medium">Live</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div id="system-health" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 flex items-center">
                                <div class="w-4 h-4 bg-gray-300 rounded-full animate-pulse mr-3"></div>
                                Loading system health...
                            </span>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-between">
                        <button onclick="refreshSystemHealth()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                            </svg>
                            Refresh
                        </button>
                        <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View Details →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Meals and Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Popular Meals -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Popular Recipes</h3>
                </div>
                <div class="p-6">
                    <?php if($topMeals->count() > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $topMeals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <?php if($meal->image_path): ?>
                                            <img src="<?php echo e($meal->image_url); ?>" 
                                                 alt="<?php echo e($meal->name); ?>" 
                                                 class="w-12 h-12 rounded-lg object-cover mr-4">
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold mr-4">
                                                <?php echo e(strtoupper(substr($meal->name, 0, 2))); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h4 class="font-medium text-gray-900"><?php echo e($meal->name); ?></h4>
                                            <p class="text-sm text-gray-500"><?php echo e($meal->cuisine_type); ?> • <?php echo e(ucfirst($meal->difficulty)); ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-gray-900"><?php echo e($meal->meal_plans_count); ?></div>
                                            <div class="text-xs text-gray-500">Plans</div>
                                        </div>
                                        <a href="<?php echo e(route('admin.recipes.show', $meal)); ?>" 
                                           class="text-blue-600 hover:text-blue-700">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            <p class="text-gray-500 font-medium">No meals found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="<?php echo e(route('admin.users.index')); ?>" 
                       class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                        <div class="p-2 bg-blue-600 rounded-lg mr-4 group-hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Manage Users</div>
                            <div class="text-sm text-gray-500">View and manage user accounts</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <a href="<?php echo e(route('admin.recipes.index')); ?>" 
                       class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                        <div class="p-2 bg-green-600 rounded-lg mr-4 group-hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Manage Recipes</div>
                            <div class="text-sm text-gray-500">Add, edit, and organize recipes</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <a href="<?php echo e(route('admin.recipes.create')); ?>" 
                       class="flex items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors group">
                        <div class="p-2 bg-yellow-600 rounded-lg mr-4 group-hover:bg-yellow-700 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Add New Recipe</div>
                            <div class="text-sm text-gray-500">Create a new recipe entry</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                    </a>
                    
                    <button onclick="checkSystemHealth()" 
                            class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group w-full text-left">
                        <div class="p-2 bg-purple-600 rounded-lg mr-4 group-hover:bg-purple-700 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">System Check</div>
                            <div class="text-sm text-gray-500">Run system health diagnostics</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
            </div>
        </div>
    </div>

<script>
function refreshSystemHealth() {
    fetch('<?php echo e(route("admin.system-health")); ?>')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('system-health');
            container.innerHTML = `
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900 flex items-center">
                            <div class="w-3 h-3 ${data.database.status === 'healthy' ? 'bg-green-500' : 'bg-red-500'} rounded-full mr-3"></div>
                            Database
                        </span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${data.database.status === 'healthy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${data.database.status}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900 flex items-center">
                            <div class="w-3 h-3 ${data.storage.status === 'healthy' ? 'bg-green-500' : 'bg-yellow-500'} rounded-full mr-3"></div>
                            Storage
                        </span>
                        <span class="text-xs text-gray-600">${data.storage.used_percentage}% used</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900 flex items-center">
                            <div class="w-3 h-3 ${data.memory_usage.percentage < 80 ? 'bg-green-500' : 'bg-yellow-500'} rounded-full mr-3"></div>
                            Memory
                        </span>
                        <span class="text-xs text-gray-600">${data.memory_usage.percentage}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900 flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            Disk Space
                        </span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${data.disk_usage.status === 'healthy' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                            ${data.disk_usage.used_percentage}% used
                        </span>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('system-health').innerHTML = '<p class="text-red-500 text-sm">Error loading system health</p>';
        });
}

function checkSystemHealth() {
    refreshSystemHealth();
}

// Load system health on page load
document.addEventListener('DOMContentLoaded', refreshSystemHealth);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\StudEats\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>