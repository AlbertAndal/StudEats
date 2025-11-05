@extends('layouts.admin')

@section('title', 'User Details - ' . $user->name)

@section('content')
<!-- Admin Header -->
<div class="bg-white shadow-sm border-b border-gray-200 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Users
                </a>
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
                    <p class="text-sm text-gray-600">Complete information about {{ $user->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if($user->id !== auth()->id())
                    @if($user->is_active)
                        <button onclick="showSuspendModal()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                            </svg>
                            Suspend User
                        </button>
                    @else
                        <form method="POST" action="{{ route('admin.users.activate', $user) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Are you sure you want to activate this user?')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Activate User
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                    <div class="text-center">
                        <!-- Avatar -->
                        <div class="h-24 w-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        
                        <!-- User Info -->
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $user->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $user->email }}</p>
                        
                        <!-- Status & Role -->
                        <div class="flex justify-center gap-2 mb-4">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Suspended' }}
                            </span>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                                   ($user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>

                        @if(!$user->is_active && $user->suspended_reason)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                <p class="text-sm font-medium text-red-800 mb-1">Suspension Reason</p>
                                <p class="text-sm text-red-700">{{ $user->suspended_reason }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Profile Details -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Joined</span>
                                <span class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Last Updated</span>
                                <span class="text-gray-900">{{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                            @if($user->dietary_preferences && count($user->dietary_preferences) > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Dietary Preferences</span>
                                    <span class="text-gray-900">{{ is_array($user->dietary_preferences) ? implode(', ', array_map('ucfirst', $user->dietary_preferences)) : $user->dietary_preferences }}</span>
                                </div>
                            @endif
                            @if($user->budget_range)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Budget Range</span>
                                    <span class="text-gray-900">${{ $user->budget_range }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Admin Actions -->
                    @if($user->id !== auth()->id())
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="space-y-2">
                                <button onclick="showRoleModal()" 
                                        class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                                    Change Role
                                </button>
                                <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="return confirm('Are you sure you want to reset this user\'s password?')"
                                            class="w-full bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg text-sm font-medium">
                                        Reset Password
                                    </button>
                                </form>
                                @if($user->role !== 'super_admin')
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                                class="w-full bg-red-50 hover:bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-medium">
                                            Delete User
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity & Stats -->
            <div class="lg:col-span-2">
            <!-- Activity Stats -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Activity Overview</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="p-4 bg-blue-100 rounded-lg inline-flex">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($activityStats['meal_plans']) }}</p>
                            <p class="text-sm text-gray-600">Total Meal Plans</p>
                        </div>
                        <div class="text-center">
                            <div class="p-4 bg-green-100 rounded-lg inline-flex">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($activityStats['completed_plans']) }}</p>
                            <p class="text-sm text-gray-600">Completed Plans</p>
                        </div>
                        <div class="text-center">
                            <div class="p-4 bg-purple-100 rounded-lg inline-flex">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $activityStats['last_login']->diffForHumans() }}</p>
                            <p class="text-sm text-gray-600">Last Activity</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Meal Plans -->
                @if($user->mealPlans->count() > 0)
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center gap-3">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Recent Meal Plans
                            </h3>
                        </div>
                        <div class="px-6 py-4">
                        <div class="space-y-4">
                            @foreach($user->mealPlans->take(5) as $mealPlan)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg flex items-center justify-center text-white text-sm font-semibold">
                                            {{ substr($mealPlan->meal->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <h5 class="font-medium text-gray-900">{{ $mealPlan->meal->name }}</h5>
                                            <p class="text-sm text-gray-600">{{ $mealPlan->planned_date ? $mealPlan->planned_date->format('M d, Y') : 'No date set' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $mealPlan->is_completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $mealPlan->is_completed ? 'Completed' : 'Planned' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Admin Logs -->
                @if($user->adminLogs->count() > 0)
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center gap-3">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Admin Activity Log
                            </h3>
                        </div>
                        <div class="px-6 py-4">

                        <div class="space-y-3">
                            @foreach($user->adminLogs->take(10) as $log)
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ $log->action }}</p>
                                        <p class="text-sm text-gray-600">{{ $log->description }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
                    </div>
                </div>
    </div>

    <!-- Suspend User Modal -->
    <div id="suspendModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Suspend User</h3>
                <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason for suspension</label>
                        <textarea name="reason" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" rows="3" placeholder="Enter reason for suspension..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeSuspendModal()" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Suspend User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Role Modal -->
    <div id="roleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Change User Role</h3>
                <form method="POST" action="{{ route('admin.users.update-role', $user) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select New Role</label>
                        <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeRoleModal()" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showSuspendModal() {
            document.getElementById('suspendModal').classList.remove('hidden');
        }

        function closeSuspendModal() {
            document.getElementById('suspendModal').classList.add('hidden');
        }

        function showRoleModal() {
            document.getElementById('roleModal').classList.remove('hidden');
        }

        function closeRoleModal() {
            document.getElementById('roleModal').classList.add('hidden');
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSuspendModal();
                closeRoleModal();
            }
        });
    </script>
@endsection