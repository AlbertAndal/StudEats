@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<!-- Admin Header -->
<div class="bg-white shadow-sm border-b border-gray-200 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                    <p class="text-sm text-gray-600">Manage user accounts, roles, and permissions</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="exportUsers()" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle stroke-linecap="round" stroke-linejoin="round" cx="9" cy="7" r="4"></circle>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 21l-2-2a4 4 0 0 0-4-4h-1"></path>
                            <circle stroke-linecap="round" stroke-linejoin="round" cx="16" cy="7" r="3"></circle>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Users</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                    </div>
                </div>
            </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 lucide lucide-user-check" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <polyline points="16,11 18,13 22,9"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Active Users</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 lucide lucide-user-x" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <line x1="17" x2="22" y1="8" y2="13"/>
                            <line x1="22" x2="17" y1="8" y2="13"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Suspended</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['suspended']) }}</p>
                    </div>
                </div>
            </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Admins</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['admins']) }}</p>
                </div>
            </div>
        </div>
        </div>

    <!-- Filters Card -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filter Users</h3>
            <p class="mt-1 text-sm text-gray-600">Search and filter user accounts</p>
        </div>
        <div class="px-6 py-4">
            <form method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Users</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by name or email..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ request('role') === 'all' ? 'selected' : '' }}>All Roles</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Users</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admins</option>
                            <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admins</option>
                        </select>
                    </div>

                    <div class="w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" {{ request('status') === '' ? 'selected' : '' }}>All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

    <!-- Users Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-medium">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                                           ($user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Active' : 'Suspended' }}
                                    </span>
                                    @if(!$user->is_active && $user->suspended_reason)
                                        <div class="text-xs text-gray-500 mt-1" title="{{ $user->suspended_reason }}">
                                            {{ Str::limit($user->suspended_reason, 30) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </a>
                                        
                                        @if($user->id !== auth()->id())
                                            @if($user->is_active)
                                                <button onclick="showSuspendModal('{{ $user->id }}')" 
                                                        class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50" title="Suspend User">
                                                    <svg class="w-4 h-4 lucide lucide-user-x" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                                        <circle cx="9" cy="7" r="4"/>
                                                        <line x1="17" x2="22" y1="8" y2="13"/>
                                                        <line x1="22" x2="17" y1="8" y2="13"/>
                                                    </svg>
                                                </button>
                                            @else
                                                <button onclick="showActivateModal('{{ $user->id }}')" 
                                                        class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50" title="Activate User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                            @endif
                                            
                                            @if($user->role !== 'super_admin')
                                                <button onclick="showDeleteModal('{{ $user->id }}', '{{ $user->name }}')" 
                                                        class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50" title="Delete User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <p class="text-lg font-medium">No users found</p>
                                    <p class="text-sm">Try adjusting your search or filter criteria</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

<!-- Suspend User Modal -->
<div id="suspendModal" class="hidden fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300" style="display: none; opacity: 0;">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300" id="suspendModalContent" style="transform: scale(0.95);">
        <div class="p-6">
            <!-- Icon and Title -->
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Suspend User</h3>
                    <p class="text-sm text-gray-600">
                        Please provide a reason for suspending this user.
                    </p>
                </div>
            </div>
            
            <form id="suspendForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for suspension</label>
                    <textarea name="reason" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" rows="3" placeholder="Enter reason for suspension..."></textarea>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeSuspendModal()" 
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 shadow-lg">
                        Suspend User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Activate User Modal -->
<div id="activateModal" class="hidden fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300" style="display: none; opacity: 0;">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300" id="activateModalContent" style="transform: scale(0.95);">
        <div class="p-6">
            <!-- Icon and Title -->
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Activate User</h3>
                    <p class="text-sm text-gray-600">
                        Are you sure you want to activate this user? They will regain access to their account.
                    </p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3 mt-6">
                <button onclick="closeActivateModal()" 
                        class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <form id="activateForm" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="w-full px-4 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 shadow-lg">
                        Activate User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300" style="display: none; opacity: 0;">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300" id="deleteModalContent" style="transform: scale(0.95);">
        <div class="p-6">
            <!-- Icon and Title -->
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Delete User</h3>
                    <p class="text-sm text-gray-600">
                        Are you sure you want to permanently delete <span id="deleteUserName" class="font-semibold"></span>? This action cannot be undone.
                    </p>
                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-xs text-red-700">
                            <strong>Warning:</strong> This will permanently delete the user account and all associated data including meal plans and activity history.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3 mt-6">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 shadow-lg">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showSuspendModal(userId) {
    document.getElementById('suspendForm').action = `/admin/users/${userId}/suspend`;
    const modal = document.getElementById('suspendModal');
    const modalContent = document.getElementById('suspendModalContent');
    
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    
    setTimeout(() => {
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    }, 10);
}

function closeSuspendModal() {
    const modal = document.getElementById('suspendModal');
    const modalContent = document.getElementById('suspendModalContent');
    
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }, 300);
}

function showActivateModal(userId) {
    document.getElementById('activateForm').action = `/admin/users/${userId}/activate`;
    const modal = document.getElementById('activateModal');
    const modalContent = document.getElementById('activateModalContent');
    
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    
    setTimeout(() => {
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    }, 10);
}

function closeActivateModal() {
    const modal = document.getElementById('activateModal');
    const modalContent = document.getElementById('activateModalContent');
    
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }, 300);
}

function showDeleteModal(userId, userName) {
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    document.getElementById('deleteUserName').textContent = userName;
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    
    setTimeout(() => {
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }, 300);
}

function exportUsers() {
    // Get current filters
    const params = new URLSearchParams(window.location.search);
    
    // Redirect to export endpoint with current filters
    window.location.href = `/admin/users/export?${params.toString()}`;
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSuspendModal();
        closeActivateModal();
        closeDeleteModal();
    }
});
</script>
@endsection