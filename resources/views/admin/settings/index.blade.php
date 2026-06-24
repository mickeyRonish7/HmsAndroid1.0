<x-app-layout>
    <x-slot name="header">
        {{ __('Account Settings') }}
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Settings Sidebar Nav -->
            <div class="md:col-span-1">
                <div class="bg-[#1e3a5f] rounded-xl overflow-hidden">
                    <div class="p-4">
                        <h3 class="text-xs font-semibold text-blue-300 uppercase tracking-wider mb-3 px-3">{{ __('Settings Menu') }}</h3>
                        <nav class="space-y-1">
                            <a href="{{ route('admin.settings') }}" class="flex items-center px-3 py-2 text-sm text-white hover:bg-[#2a4a7f] rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-[#2a4a7f] text-white font-medium border-l-4 border-blue-400 rounded-l-none' : '' }}">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ __('Overview') }}
                            </a>
                            <a href="{{ route('admin.settings.edit') }}" class="flex items-center px-3 py-2 text-sm text-white hover:bg-[#2a4a7f] rounded-lg transition-colors {{ request()->routeIs('admin.settings.edit') ? 'bg-[#2a4a7f] text-white font-medium border-l-4 border-blue-400 rounded-l-none' : '' }}">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                {{ __('Edit Profile') }}
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-3 space-y-5">
                <!-- Profile Header Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-5">
                        <div class="flex items-center gap-4">
                            @if($admin->profile_photo_path)
                                <img src="{{ asset('storage/' . $admin->profile_photo_path) }}" alt="{{ $admin->name }}" class="h-14 w-14 rounded-full object-cover ring-2 ring-white/50">
                            @else
                                <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center text-white text-2xl font-bold ring-2 ring-white/50">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h2 class="text-lg font-bold text-white">{{ $admin->name }}</h2>
                                <p class="text-sm text-blue-100">{{ __('Administrator') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="p-5">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">{{ __('Account Information') }}</h3>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            <!-- Email -->
                            <div class="py-3 flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Email Address') }}</span>
                                <span class="text-sm text-gray-800 dark:text-white font-medium">{{ $admin->email }}</span>
                            </div>
                            <!-- Phone -->
                            <div class="py-3 flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Phone Number') }}</span>
                                <span class="text-sm text-gray-800 dark:text-white font-medium">{{ $admin->phone ?? __('Not provided') }}</span>
                            </div>
                            <!-- Department -->
                            <div class="py-3 flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Department') }}</span>
                                <span class="text-sm text-gray-800 dark:text-white font-medium">{{ $admin->department ?? __('Not provided') }}</span>
                            </div>
                            <!-- Role -->
                            <div class="py-3 flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Role') }}</span>
                                <span class="inline-flex items-center px-3 py-0.5 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 ring-1 ring-blue-200 capitalize">{{ $admin->role }}</span>
                            </div>
                        </div>

                        <!-- Edit Settings Button -->
                        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('admin.settings.edit') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                {{ __('Edit Settings') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bottom Stats Row -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Account Status') }}</p>
                        <p class="text-base font-bold text-green-600 dark:text-green-400">{{ __('Active') }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Member Since') }}</p>
                        <p class="text-base font-bold text-blue-600 dark:text-blue-400">{{ $admin->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
