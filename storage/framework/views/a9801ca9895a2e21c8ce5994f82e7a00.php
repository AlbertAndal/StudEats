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
                            <!-- Lucide Users Icon -->
                            <svg class="w-7 h-7 text-blue-200 lucide lucide-users" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="m22 21-2-2a4 4 0 0 0-4-4h-1"/>
                                <circle cx="16" cy="7" r="3"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-blue-100">Total Users</h3>
                        <p class="text-3xl font-bold"><?php echo e(number_format($stats['total_users'])); ?></p>
                        <p class="text-xs text-blue-100 mt-1">
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1 lucide lucide-trending-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="22,7 13.5,15.5 8.5,10.5 2,17"/>
                                    <polyline points="16,7 22,7 22,13"/>
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
                            <!-- Lucide Activity Icon -->
                            <svg class="w-7 h-7 text-green-200 lucide lucide-activity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m22 12-4-4-6 6-4-4-4 4"/>
                                <path d="M2 12h20"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-green-100">Active Users</h3>
                        <p class="text-3xl font-bold"><?php echo e(number_format($stats['active_users'])); ?></p>
                        <p class="text-xs text-green-100 mt-1">
                            <?php if($stats['total_users'] > 0): ?>
                                <?php echo e(number_format(($stats['active_users'] / $stats['total_users']) * 100, 1)); ?>% of total
                            <?php else: ?>
                                0% of total
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <!-- Lucide Book Open Icon -->
                            <svg class="w-7 h-7 text-yellow-200 lucide lucide-book-open" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-yellow-100">Total Recipes</h3>
                        <p class="text-3xl font-bold"><?php echo e(number_format($stats['total_meals'])); ?></p>
                        <p class="text-xs text-yellow-100 mt-1"><?php echo e($stats['featured_meals'] ?? 0); ?> featured</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <!-- Lucide Server Icon -->
                            <svg class="w-7 h-7 text-purple-200 lucide lucide-server" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="8" x="2" y="2" rx="2" ry="2"/>
                                <rect width="20" height="8" x="2" y="14" rx="2" ry="2"/>
                                <line x1="6" x2="6.01" y1="6" y2="6"/>
                                <line x1="6" x2="6.01" y1="18" y2="18"/>
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
                                                        <p class="text-sm text-gray-900 font-medium"><?php echo e($activity->description ?? 'Activity'); ?></p>
                                                        <div class="mt-1 flex items-center gap-4 text-xs text-gray-500">
                                                            <span><?php echo e($activity->adminUser->name ?? 'Unknown Admin'); ?></span>
                                                            <span>•</span>
                                                            <span><?php echo e($activity->created_at ? $activity->created_at->diffForHumans() : 'Recently'); ?></span>
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
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4 lucide lucide-folder-open" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 14 1.5-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.54 6a2 2 0 0 1-1.95 1.5H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H18a2 2 0 0 1 2 2v2"/>
                            </svg>
                            <p class="text-gray-500 font-medium">No recent activities</p>
                            <p class="text-sm text-gray-400">Admin activities will appear here</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- System Health & Quick Actions Combined -->
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
                    <div id="system-health" class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900 flex items-center">
                                <div class="w-4 h-4 bg-gray-300 rounded-full animate-pulse mr-3"></div>
                                Loading system health...
                            </span>
                        </div>
                    </div>
                    
                    <!-- Integrated Quick Actions -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-semibold text-gray-900">Quick Actions</h4>
                            <button onclick="refreshSystemHealth()" 
                                    class="text-blue-600 hover:text-blue-700 text-xs font-medium flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1 lucide lucide-refresh-cw" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                    <path d="M21 3v5h-5"/>
                                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                    <path d="M3 21v-5h5"/>
                                </svg>
                                Refresh
                            </button>
                        </div>
                        <div class="space-y-2">
                            <a href="<?php echo e(route('admin.users.index')); ?>" 
                               class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                                <div class="p-1.5 bg-blue-600 rounded-md mr-3 group-hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 text-white lucide lucide-users" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="m22 21-2-2a4 4 0 0 0-4-4h-1"/>
                                        <circle cx="16" cy="7" r="3"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">Manage Users</div>
                                    <div class="text-xs text-gray-500">View all accounts</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            
                            <a href="<?php echo e(route('admin.recipes.index')); ?>" 
                               class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                                <div class="p-1.5 bg-green-600 rounded-md mr-3 group-hover:bg-green-700 transition-colors">
                                    <svg class="w-4 h-4 text-white lucide lucide-book-open" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">Browse Recipes</div>
                                    <div class="text-xs text-gray-500">Manage meal database</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            
                            <a href="<?php echo e(route('admin.recipes.create')); ?>" 
                               class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                                <div class="p-1.5 bg-purple-600 rounded-md mr-3 group-hover:bg-purple-700 transition-colors">
                                    <svg class="w-4 h-4 text-white lucide lucide-plus" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/>
                                        <path d="M12 5v14"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">Add Recipe</div>
                                    <div class="text-xs text-gray-500">Create new entry</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Meals -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Popular Recipes</h3>
            </div>
            <div class="p-6">
                    <?php if($topMeals->count() > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $topMeals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <?php
                                            $imageUrl = null;
                                            if (isset($meal->image_path) && $meal->image_path) {
                                                try {
                                                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($meal->image_path)) {
                                                        $imageUrl = asset('storage/' . $meal->image_path);
                                                    }
                                                } catch (\Exception $e) {
                                                    // Silent fail - use placeholder
                                                }
                                            }
                                        ?>
                                        
                                        <?php if($imageUrl): ?>
                                            <img src="<?php echo e($imageUrl); ?>" 
                                                 alt="<?php echo e($meal->name ?? 'Meal'); ?>" 
                                                 class="w-12 h-12 rounded-lg object-cover mr-4">
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold mr-4">
                                                <?php echo e(isset($meal->name) ? strtoupper(substr($meal->name, 0, 2)) : 'M'); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h4 class="font-medium text-gray-900"><?php echo e($meal->name ?? 'Unnamed Meal'); ?></h4>
                                            <p class="text-sm text-gray-500">
                                                <?php echo e($meal->cuisine_type ?? 'Unknown'); ?> • <?php echo e(isset($meal->difficulty) ? ucfirst($meal->difficulty) : 'N/A'); ?>

                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-gray-900"><?php echo e($meal->meal_plans_count ?? 0); ?></div>
                                            <div class="text-xs text-gray-500">Plans</div>
                                        </div>
                                        <?php if(isset($meal->id)): ?>
                                            <a href="<?php echo e(route('admin.recipes.show', $meal->id)); ?>" 
                                               class="text-blue-600 hover:text-blue-700">
                                                <svg class="w-5 h-5 lucide lucide-eye" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4 lucide lucide-utensils" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                                <path d="M7 2v20"/>
                                <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                            </svg>
                            <p class="text-gray-500 font-medium">No meals found</p>
                        </div>
                    <?php endif; ?>
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