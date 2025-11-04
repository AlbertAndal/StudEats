@extends('layouts.app')

@section('title', 'Edit Profile')

@push('styles')
<style>
    .form-section {
        transition: all 0.3s ease;
    }
    
    .form-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .field-group {
        position: relative;
    }
    
    .field-group input:focus + label,
    .field-group input:valid + label {
        transform: translateY(-1.5rem) scale(0.9);
        color: #10b981;
    }
    
    .progress-ring {
        transition: stroke-dasharray 0.3s ease;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <!-- Email Verification Status Alert -->
    @if(!$user->email_verified_at)
    <div class="mb-6 bg-orange-50 border-l-4 border-orange-400 rounded-r-lg p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-orange-800">Email Verification Required</h3>
                <p class="text-sm text-orange-700 mt-1">
                    Please verify <span class="font-medium">{{ $user->email }}</span> to unlock all features.
                </p>
                <div class="mt-3">
                    <a href="{{ route('email.verify.form') }}" 
                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Verify Now
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 rounded-r-lg p-3 shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-green-800">
                Account verified • All features enabled
            </p>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Profile</h1>
        <p class="text-base text-gray-600 max-w-2xl">Update your personal information and preferences to get better meal recommendations tailored to your dietary needs and budget.</p>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700">Saving changes...</span>
        </div>
    </div>

    <form id="profile-form" action="{{ route('profile.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @csrf
        @method('PUT')
        
        <!-- Left Column: Personal Information -->
        <div class="space-y-6">
            <!-- Personal Information -->
            <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
                <div class="px-5 py-4 bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 border-b border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        Personal Information
                    </h2>
                </div>
            
                <div class="p-4 space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('name') border-red-300 @enderror"
                               value="{{ old('name', $user->name) }}"
                               placeholder="Enter your full name">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address <span class="text-red-500">*</span>
                            @if($user->email_verified_at)
                                <span class="ml-1 text-xs text-green-600">✓</span>
                            @else
                                <span class="ml-1 text-xs text-red-600">⚠</span>
                            @endif
                        </label>
                        <div class="relative">
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('email') border-red-300 @enderror @if(!$user->email_verified_at) border-orange-300 bg-orange-50 @endif"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="Enter your email address">
                            @if(!$user->email_verified_at)
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                                    <a href="{{ route('email.verify.form') }}" class="text-xs text-orange-600 hover:text-orange-700" title="Verify Email">⚠</a>
                                </div>
                            @endif
                        </div>
                        @if(!$user->email_verified_at)
                            <p class="mt-1 text-xs text-orange-600">Email needs verification • <a href="{{ route('email.verify.form') }}" class="underline hover:no-underline">Verify now</a></p>
                        @else
                            <p class="mt-1 text-xs text-green-600">✓ Verified {{ $user->email_verified_at->format('M d, Y') }}</p>
                        @endif
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                    <!-- Age & Gender -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                            <input type="number" id="age" name="age" min="13" max="120"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('age') border-red-300 @enderror"
                                   value="{{ old('age', $user->age) }}"
                                   placeholder="Age">
                            @error('age')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select id="gender" name="gender"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('gender') border-red-300 @enderror">
                                <option value="">Select</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                <option value="prefer_not_to_say" {{ old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                        <select id="timezone" name="timezone"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('timezone') border-red-300 @enderror">
                            <option value="">Select timezone</option>
                            <option value="Asia/Manila" {{ old('timezone', $user->timezone) == 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila (PHT)</option>
                            <option value="Asia/Singapore" {{ old('timezone', $user->timezone) == 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore</option>
                            <option value="Asia/Jakarta" {{ old('timezone', $user->timezone) == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                            <option value="Asia/Bangkok" {{ old('timezone', $user->timezone) == 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok</option>
                            <option value="UTC" {{ old('timezone', $user->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                        </select>
                        @error('timezone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Physical Information -->
            <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
                <div class="px-5 py-4 bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 border-b border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        Physical Information
                    </h2>
                </div>
                
                <div class="p-4 space-y-4">
                    <!-- Height & Weight -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-1">Height</label>
                            <div class="flex space-x-1">
                                <input type="number" id="height" name="height" min="100" max="250" step="0.1"
                                       class="flex-1 px-3 py-2.5 border border-gray-300 rounded-l-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('height') border-red-300 @enderror"
                                       value="{{ old('height', $user->height) }}"
                                       placeholder="170">
                                <select name="height_unit" 
                                        class="px-3 py-2.5 border border-gray-300 rounded-r-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-gray-50">
                                    <option value="cm" {{ old('height_unit', $user->height_unit ?? 'cm') == 'cm' ? 'selected' : '' }}>cm</option>
                                    <option value="ft" {{ old('height_unit', $user->height_unit) == 'ft' ? 'selected' : '' }}>ft</option>
                                </select>
                            </div>
                            @error('height')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight</label>
                            <div class="flex space-x-1">
                                <input type="number" id="weight" name="weight" min="30" max="300" step="0.1"
                                       class="flex-1 px-3 py-2.5 border border-gray-300 rounded-l-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('weight') border-red-300 @enderror"
                                       value="{{ old('weight', $user->weight) }}"
                                       placeholder="65">
                                <select name="weight_unit" 
                                        class="px-3 py-2.5 border border-gray-300 rounded-r-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-gray-50">
                                    <option value="kg" {{ old('weight_unit', $user->weight_unit ?? 'kg') == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="lbs" {{ old('weight_unit', $user->weight_unit) == 'lbs' ? 'selected' : '' }}>lbs</option>
                                </select>
                            </div>
                            @error('weight')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Activity Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Activity Level</label>
                        <div class="grid grid-cols-2 gap-2">
                            @php
                                $activityLevels = [
                                    'sedentary' => ['Sedentary', '🪑'],
                                    'lightly_active' => ['Light', '🚶'],
                                    'moderately_active' => ['Moderate', '🏃'],
                                    'very_active' => ['Very Active', '💪'],
                                    'extremely_active' => ['Extreme', '🔥']
                                ];
                            @endphp
                            
                            @foreach($activityLevels as $value => $info)
                                <label class="relative flex items-center p-2 border rounded-md cursor-pointer hover:bg-gray-50 transition-colors {{ old('activity_level', $user->activity_level) == $value ? 'bg-green-50 border-green-500' : 'border-gray-300' }}">
                                    <input type="radio" name="activity_level" value="{{ $value }}" 
                                           class="sr-only peer"
                                           {{ old('activity_level', $user->activity_level) == $value ? 'checked' : '' }}>
                                    <span class="mr-2">{{ $info[1] }}</span>
                                    <span class="text-sm font-medium text-gray-900 peer-checked:text-green-700">{{ $info[0] }}</span>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-green-500 rounded-md pointer-events-none"></div>
                                </label>
                            @endforeach
                        </div>
                        @error('activity_level')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Dietary Preferences & Actions -->
        <div class="space-y-6">
            <!-- Dietary Preferences -->
            <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
                <div class="px-5 py-4 bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 border-b border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        Dietary Preferences & Budget
                    </h2>
                </div>
                
                <div class="p-4 space-y-4">
                    <!-- Daily Budget -->
                    <div>
                        <label for="daily_budget" class="block text-sm font-medium text-gray-700 mb-1">Daily Food Budget (₱)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">₱</span>
                            </div>
                            <input type="number" id="daily_budget" name="daily_budget" min="100" max="2000" step="10"
                                   class="w-full pl-7 pr-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('daily_budget') border-red-300 @enderror"
                                   value="{{ old('daily_budget', $user->daily_budget) }}"
                                   placeholder="300">
                        </div>
                        <div class="mt-3 grid grid-cols-4 gap-2">
                            <button type="button" class="budget-preset px-3 py-2 text-sm font-medium bg-gray-100 hover:bg-green-100 hover:text-green-700 hover:border-green-300 border-2 border-transparent rounded-lg transition-all" data-value="200">₱200</button>
                            <button type="button" class="budget-preset px-3 py-2 text-sm font-medium bg-gray-100 hover:bg-green-100 hover:text-green-700 hover:border-green-300 border-2 border-transparent rounded-lg transition-all" data-value="300">₱300</button>
                            <button type="button" class="budget-preset px-3 py-2 text-sm font-medium bg-gray-100 hover:bg-green-100 hover:text-green-700 hover:border-green-300 border-2 border-transparent rounded-lg transition-all" data-value="500">₱500</button>
                            <button type="button" class="budget-preset px-3 py-2 text-sm font-medium bg-gray-100 hover:bg-green-100 hover:text-green-700 hover:border-green-300 border-2 border-transparent rounded-lg transition-all" data-value="800">₱800</button>
                        </div>
                        @error('daily_budget')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dietary Preferences -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dietary Preferences</label>
                        <p class="text-sm text-gray-600 mb-3">Tell us about your dietary preferences to get better meal recommendations</p>
                        
                        <textarea name="dietary_preferences" 
                                  rows="4"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('dietary_preferences') border-red-300 @enderror"
                                  placeholder="E.g., I'm vegetarian, avoid spicy food, prefer low-carb meals, allergic to peanuts, etc.">{{ old('dietary_preferences', is_string($user->dietary_preferences) ? $user->dietary_preferences : '') }}</textarea>
                        
                        @error('dietary_preferences')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
                <div class="px-5 py-4 bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 border-b border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900 flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        Actions
                    </h2>
                </div>
                <div class="p-4 space-y-2">
                    <!-- Success Message Container -->
                    <div id="success-message" class="hidden bg-green-50 border border-green-200 rounded p-3">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="ml-2 text-sm text-green-800" id="success-text">Profile updated successfully!</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-5 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                id="save-button">
                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span id="save-text">Save Changes</span>
                        </button>
                        <a href="{{ route('profile.show') }}" 
                           class="w-full inline-flex justify-center items-center px-5 py-3 border-2 border-gray-300 text-sm font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced verification alert interactions
        const verificationAlert = document.querySelector('.bg-gradient-to-r.from-red-50');
        if (verificationAlert) {
            // Add subtle pulse animation for unverified accounts
            verificationAlert.classList.add('animate-pulse');
            
            // Remove pulse on interaction
            verificationAlert.addEventListener('mouseenter', function() {
                this.classList.remove('animate-pulse');
            });
            
            verificationAlert.addEventListener('mouseleave', function() {
                this.classList.add('animate-pulse');
            });
        }

        // Handle email verification resend button
        const resendButton = document.querySelector('button[formaction*="email.verify.resend"]');
        if (resendButton) {
            resendButton.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const originalText = this.innerHTML;
                
                // Show loading state
                this.disabled = true;
                this.innerHTML = `
                    <svg class="animate-spin h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                `;
                
                // Submit resend form
                if (form) {
                    form.action = '{{ route("email.verify.resend") }}';
                    form.method = 'POST';
                    form.submit();
                }
                
                // Reset after timeout
                setTimeout(() => {
                    this.disabled = false;
                    this.innerHTML = originalText;
                }, 5000);
            });
        }

        // Enhanced form validation with verification check
        const profileForm = document.getElementById('profile-form');
        const emailInput = document.getElementById('email');
        const saveButton = document.getElementById('save-button');
        
        if (profileForm && emailInput) {
            // Check if email is being changed for unverified accounts
            const originalEmail = '{{ $user->email }}';
            const isVerified = {{ $user->email_verified_at ? 'true' : 'false' }};
            
            emailInput.addEventListener('input', function() {
                const currentEmail = this.value.trim();
                const emailChanged = currentEmail !== originalEmail;
                
                if (!isVerified && emailChanged) {
                    // Show warning for unverified accounts changing email
                    showEmailChangeWarning();
                } else {
                    hideEmailChangeWarning();
                }
            });
            
            // Enhanced form submission for unverified accounts
            profileForm.addEventListener('submit', function(e) {
                if (!isVerified) {
                    const emailChanged = emailInput.value.trim() !== originalEmail;
                    
                    if (emailChanged) {
                        e.preventDefault();
                        
                        if (confirm('Changing your email address will require re-verification. Are you sure you want to continue?')) {
                            // Show loading overlay
                            document.getElementById('loading-overlay').classList.remove('hidden');
                            this.submit();
                        }
                        return;
                    }
                }
                
                // Normal form submission
                document.getElementById('loading-overlay').classList.remove('hidden');
            });
        }
        
        // Helper functions for email change warnings
        function showEmailChangeWarning() {
            let warning = document.getElementById('email-change-warning');
            if (!warning) {
                warning = document.createElement('div');
                warning.id = 'email-change-warning';
                warning.className = 'mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded-md';
                warning.innerHTML = `
                    <div class="flex items-center text-sm text-yellow-800">
                        <svg class="h-4 w-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Changing your email will require re-verification of the new address.
                    </div>
                `;
                emailInput.parentNode.appendChild(warning);
            }
            warning.style.display = 'block';
        }
        
        function hideEmailChangeWarning() {
            const warning = document.getElementById('email-change-warning');
            if (warning) {
                warning.style.display = 'none';
            }
        }

        // Budget preset functionality
        const budgetPresets = document.querySelectorAll('.budget-preset');
        const budgetInput = document.getElementById('daily_budget');
        
        budgetPresets.forEach(preset => {
            preset.addEventListener('click', function(e) {
                e.preventDefault();
                const value = this.getAttribute('data-value');
                budgetInput.value = value;
                
                // Visual feedback
                budgetPresets.forEach(p => {
                    p.classList.remove('bg-green-100', 'text-green-700', 'border-green-400', 'font-semibold');
                    p.classList.add('bg-gray-100');
                });
                this.classList.remove('bg-gray-100');
                this.classList.add('bg-green-100', 'text-green-700', 'border-green-400', 'font-semibold');
                
                // Trigger input event for any listeners
                budgetInput.dispatchEvent(new Event('input'));
            });
        });
        
        // Form progress tracking
        const formSections = document.querySelectorAll('.form-section, .bg-white.shadow-lg');
        const totalSections = formSections.length;
        let completedSections = 0;
        
        function updateFormProgress() {
            completedSections = 0;
            
            formSections.forEach(section => {
                const inputs = section.querySelectorAll('input, select, textarea');
                let sectionComplete = true;
                
                inputs.forEach(input => {
                    if (input.hasAttribute('required') && !input.value.trim()) {
                        sectionComplete = false;
                    }
                });
                
                if (sectionComplete && inputs.length > 0) {
                    completedSections++;
                    section.classList.add('border-green-200');
                    section.classList.remove('border-gray-200');
                } else {
                    section.classList.remove('border-green-200');
                    section.classList.add('border-gray-200');
                }
            });
            
            // Update save button state
            const progress = (completedSections / totalSections) * 100;
            if (saveButton) {
                if (progress === 100) {
                    saveButton.classList.add('from-green-600', 'to-blue-600');
                    saveButton.classList.remove('from-gray-400', 'to-gray-500');
                } else {
                    saveButton.classList.remove('from-green-600', 'to-blue-600');
                    saveButton.classList.add('from-gray-400', 'to-gray-500');
                }
            }
        }
        
        // Monitor form changes
        profileForm.addEventListener('input', updateFormProgress);
        profileForm.addEventListener('change', updateFormProgress);
        
        // Dietary preferences counter
        const dietaryCheckboxes = document.querySelectorAll('input[name="dietary_preferences[]"]');
        const preferenceCount = document.getElementById('preference-count');
        
        function updatePreferenceCount() {
            const checkedCount = document.querySelectorAll('input[name="dietary_preferences[]"]:checked').length;
            if (preferenceCount) {
                preferenceCount.textContent = `${checkedCount} preference${checkedCount !== 1 ? 's' : ''} selected`;
            }
        }
        
        dietaryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updatePreferenceCount);
        });
        
        // Initial progress check
        updateFormProgress();
        
        // Enhanced accessibility and keyboard navigation
        document.addEventListener('keydown', function(e) {
            // Quick save with Ctrl+S or Cmd+S
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                if (profileForm) {
                    profileForm.submit();
                }
            }
            
            // Quick verify with Ctrl+V or Cmd+V
            if ((e.ctrlKey || e.metaKey) && e.key === 'v' && !isVerified) {
                e.preventDefault();
                window.location.href = '{{ route("email.verify.form") }}';
            }
        });
        
        // Success message handling
        @if(session('success'))
            showNotification('{{ session("success") }}', 'success');
        @endif
        
        @if(session('error'))
            showNotification('{{ session("error") }}', 'error');
        @endif
        
        // Utility function for notifications
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto transition-all duration-300 transform translate-x-full`;
            
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            
            notification.innerHTML = `
                <div class="${bgColor} text-white p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            ${type === 'success' ? 
                                '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                                type === 'error' ?
                                '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>' :
                                '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
                            }
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <div class="ml-3 flex-shrink-0">
                            <button class="inline-flex text-white hover:text-gray-200 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }
    });
</script>
@endpush

