<!-- Admin Header with Integrated Navigation -->
<div class="bg-white shadow-sm border-b border-gray-200" data-version="2.0-analytics-fixed">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left: Brand/Logo -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-lg font-semibold text-gray-900">StudEats</span>
                    <span class="ml-1 text-xs text-gray-500 font-medium">ADMIN</span>
                    <!-- Version indicator - remove after testing -->
                    <span class="ml-2 text-[10px] text-green-600 font-bold bg-green-100 px-2 py-0.5 rounded" title="Analytics Fixed Version">v2.0</span>
                </div>
            </div>

            <!-- Center: Main Navigation -->
            <div class="flex items-center space-x-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="group inline-flex items-center px-3 py-2 border-b-2 font-medium transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mr-2 lucide lucide-layout-dashboard" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="9" x="3" y="3" rx="1"/>
                        <rect width="7" height="5" x="14" y="3" rx="1"/>
                        <rect width="7" height="9" x="14" y="12" rx="1"/>
                        <rect width="7" height="5" x="3" y="16" rx="1"/>
                    </svg>
                    Dashboard
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}"
                   class="group inline-flex items-center px-3 py-2 border-b-2 font-medium transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mr-2 lucide lucide-users" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="m22 21-2-2a4 4 0 0 0-4-4h-1"/>
                        <circle cx="16" cy="7" r="3"/>
                    </svg>
                    <span>Users</span>
                    @php
                        try {
                            $userCount = $navUserCount ?? \App\Models\User::count();
                        } catch (\Exception $e) {
                            $userCount = '...';
                        }
                    @endphp
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-600 group-hover:bg-blue-200">
                        {{ $userCount }}
                    </span>
                </a>

                <!-- Recipes -->
                <a href="{{ route('admin.recipes.index') }}"
                   class="group inline-flex items-center px-3 py-2 border-b-2 font-medium transition-colors duration-200 {{ request()->routeIs('admin.recipes.*') ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mr-2 lucide lucide-chef-hat" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/>
                        <line x1="6" x2="18" y1="17" y2="17"/>
                    </svg>
                    <span>Recipes</span>
                    @php
                        try {
                            $recipeCount = $navRecipeCount ?? \App\Models\Meal::count();
                        } catch (\Exception $e) {
                            $recipeCount = '...';
                        }
                    @endphp
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200">
                        {{ $recipeCount }}
                    </span>
                </a>

                <!-- Security Monitor -->
                <a href="{{ route('admin.security-monitor.index') }}"
                   class="group inline-flex items-center px-3 py-2 border-b-2 font-medium transition-colors duration-200 {{ request()->routeIs('admin.security-monitor.*') ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mr-2 lucide lucide-shield-check" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    <span>Security</span>
                </a>
            </div>
            
            <!-- Right: User Profile & Actions -->
            <div class="flex items-center gap-3">
                <!-- Notifications -->
                <div class="relative">
                    <button id="notificationButton" type="button" class="p-2 text-gray-400 hover:text-gray-500 relative rounded-full hover:bg-gray-100 transition-colors" title="Notifications">
                        <svg class="w-5 h-5 lucide lucide-bell" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="m13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <span id="notificationBadge" class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-gray-900">Notifications</h3>
                                <button id="markAllReadBtn" type="button" class="text-sm text-blue-600 hover:text-blue-700 font-medium px-3 py-1 rounded-md hover:bg-blue-50 transition-colors">Mark all read</button>
                            </div>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            <div id="notificationList">
                                <!-- Sample notifications -->
                                <div class="p-5 border-b border-gray-100 hover:bg-gray-50 cursor-pointer notification-item transition-colors" data-read="false">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600 lucide lucide-user-plus" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                                    <circle cx="9" cy="7" r="4"/>
                                                    <line x1="19" x2="19" y1="8" y2="14"/>
                                                    <line x1="22" x2="16" y1="11" y2="11"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 mb-1">New User Registration</p>
                                            <p class="text-sm text-gray-600 leading-relaxed">John Albert Andal just registered for an account</p>
                                            <p class="text-xs text-gray-400 mt-2 font-medium">2 minutes ago</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-5 border-b border-gray-100 hover:bg-gray-50 cursor-pointer notification-item transition-colors" data-read="false">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-orange-600 lucide lucide-alert-triangle" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                                    <path d="M12 9v4"/>
                                                    <path d="m12 17 .01 0"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 mb-1">System Alert</p>
                                            <p class="text-sm text-gray-600 leading-relaxed">Email service experiencing delays - some notifications may be delayed</p>
                                            <p class="text-xs text-gray-400 mt-2 font-medium">5 minutes ago</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-5 border-b border-gray-100 hover:bg-gray-50 cursor-pointer notification-item transition-colors opacity-75" data-read="true">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600 lucide lucide-chef-hat" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/>
                                                    <line x1="6" x2="18" y1="17" y2="17"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-700 mb-1">New Recipe Added</p>
                                            <p class="text-sm text-gray-500 leading-relaxed">Chicken Adobo recipe has been published and is now available</p>
                                            <p class="text-xs text-gray-400 mt-2 font-medium">1 hour ago</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 border-t border-gray-200 bg-gray-50">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-semibold flex items-center justify-center py-2 px-4 rounded-md hover:bg-blue-50 transition-colors">
                                <svg class="w-4 h-4 mr-2 lucide lucide-external-link" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"/>
                                    <path d="M10 14 21 3"/>
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                </svg>
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile & Logout -->
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="text-sm">
                        <div class="font-medium text-gray-900">{{ auth()->user()->name }}</div>
                        <div class="text-gray-500">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                    </div>
                </div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-500 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                            title="Sign out">
                        <svg class="w-4 h-4 mr-1 lucide lucide-log-out" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16,17 21,12 16,7"/>
                            <line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Notification System Functionality
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    const button = document.getElementById('notificationButton');
    
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        button.classList.add('bg-gray-100');
    } else {
        dropdown.classList.add('hidden');
        button.classList.remove('bg-gray-100');
    }
}

function closeNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    const button = document.getElementById('notificationButton');
    dropdown.classList.add('hidden');
    button.classList.remove('bg-gray-100');
}

function markAllAsRead() {
    const notifications = document.querySelectorAll('.notification-item[data-read="false"]');
    const badge = document.getElementById('notificationBadge');
    
    notifications.forEach(notification => {
        notification.setAttribute('data-read', 'true');
        notification.classList.add('opacity-75');
        const dot = notification.querySelector('.bg-blue-500, .bg-orange-500, .bg-green-500');
        if (dot) {
            dot.classList.add('hidden');
        }
    });
    
    // Hide the notification badge
    badge.classList.add('hidden');
    
    // You can add AJAX call here to mark notifications as read in database
    console.log('All notifications marked as read');
}

function markNotificationRead(element) {
    element.setAttribute('data-read', 'true');
    element.classList.add('opacity-75');
    const dot = element.querySelector('.bg-blue-500, .bg-orange-500, .bg-green-500');
    if (dot) {
        dot.classList.add('hidden');
    }
    
    // Check if all notifications are read
    const unreadNotifications = document.querySelectorAll('.notification-item[data-read="false"]');
    if (unreadNotifications.length === 0) {
        document.getElementById('notificationBadge').classList.add('hidden');
    }
}

function updateNotificationCount() {
    const unreadNotifications = document.querySelectorAll('.notification-item[data-read="false"]');
    const badge = document.getElementById('notificationBadge');
    
    if (unreadNotifications.length === 0) {
        badge.classList.add('hidden');
    } else {
        badge.classList.remove('hidden');
    }
}

// Initialize all event listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Header: Initializing event listeners');
    
    // Notification button click handler
    const notificationBtn = document.getElementById('notificationButton');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleNotifications();
        });
        console.log('Notifications: Button event listener attached');
    } else {
        console.error('Notifications: Button not found');
    }
    
    // Mark all read button click handler
    const markAllReadBtn = document.getElementById('markAllReadBtn');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', markAllAsRead);
        console.log('Notifications: Mark all read button event listener attached');
    }
    
    // Add click handlers to notification items
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function() {
            if (this.getAttribute('data-read') === 'false') {
                markNotificationRead(this);
            }
        });
    });
    
    // Update notification count
    updateNotificationCount();
    
    // Close notifications when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const button = document.getElementById('notificationButton');
        
        if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
            closeNotifications();
        }
    });
    
    console.log('Admin Header: All event listeners initialized');
});
</script>