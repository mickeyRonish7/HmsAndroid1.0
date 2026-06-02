<x-app-layout>
    <x-slot name="header">
        {{ __('Account Settings') }}
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Sidebar Navigation -->
            <div class="md:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                            {{ __('Settings Menu') }}
                        </h3>
                        <nav class="space-y-2">
                            <a href="{{ route('admin.settings') }}" class="block px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('Overview') }}
                            </a>
                            <a href="{{ route('admin.settings.edit') }}" class="block px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.settings.edit') ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('Edit Profile') }}
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <!-- Profile Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <div class="flex items-center gap-4">
                            @if($admin->profile_photo_path)
                                <img src="{{ asset('storage/' . $admin->profile_photo_path) }}" alt="{{ $admin->name }}" class="h-16 w-16 rounded-full object-cover border-4 border-white">
                            @else
                                <div class="h-16 w-16 rounded-full bg-blue-300 flex items-center justify-center text-white text-2xl font-bold border-4 border-white">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h2 class="text-2xl font-bold">{{ $admin->name }}</h2>
                                <p class="text-blue-100">{{ __('Administrator') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Information -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">
                            {{ __('Account Information') }}
                        </h3>

                        <div class="space-y-4">
                            <!-- Email -->
                            <div class="pb-4 border-b border-gray-200 dark:border-gray-700">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                                    {{ __('Email Address') }}
                                </label>
                                <p class="text-lg text-gray-800 dark:text-white">{{ $admin->email }}</p>
                            </div>

                            <!-- Phone -->
                            <div class="pb-4 border-b border-gray-200 dark:border-gray-700">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                                    {{ __('Phone Number') }}
                                </label>
                                <p class="text-lg text-gray-800 dark:text-white">{{ $admin->phone ?? __('Not provided') }}</p>
                            </div>

                            <!-- Department -->
                            <div class="pb-4 border-b border-gray-200 dark:border-gray-700">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                                    {{ __('Department') }}
                                </label>
                                <p class="text-lg text-gray-800 dark:text-white">{{ $admin->department ?? __('Not provided') }}</p>
                            </div>

                            <!-- Role -->
                            <div class="pb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                                    {{ __('Role') }}
                                </label>
                                <p class="text-lg">
                                    <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full font-semibold capitalize">
                                        {{ $admin->role }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.settings.edit') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('Edit Settings') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Account Stats -->
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">{{ __('Account Status') }}</h4>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ __('Active') }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">{{ __('Member Since') }}</h4>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $admin->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
