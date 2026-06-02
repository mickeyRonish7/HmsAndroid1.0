<x-app-layout>
    <x-slot name="header">
        My Profile
    </x-slot>

    <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-gray-800 dark:text-gray-100">My Profile</h1>

        <!-- Profile Information Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Header with Background -->
            <div class="h-32 bg-gradient-to-r from-blue-600 to-blue-700"></div>

            <!-- Profile Content -->
            <div class="px-6 py-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Profile Photo -->
                    <div class="flex flex-col items-center md:items-start">
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-700 shadow-lg object-cover -mt-16 mb-4">
                        @else
                            <div class="w-32 h-32 rounded-full bg-blue-200 dark:bg-blue-700 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold text-5xl -mt-16 mb-4 border-4 border-white dark:border-gray-700 shadow-lg">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <!-- User Information -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $user->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 capitalize">{{ $user->role }}</p>

                        <!-- Profile Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Email Address</p>
                                <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ $user->email }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Phone Number</p>
                                <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ $user->phone ?? 'Not provided' }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Date of Birth</p>
                                <p class="text-gray-900 dark:text-gray-100 font-semibold">
                                    @if($user->dob)
                                        {{ \Carbon\Carbon::parse($user->dob)->format('M d, Y') }}
                                    @else
                                        Not provided
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Member Since</p>
                                <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ $user->created_at->format('M d, Y') }}</p>
                            </div>

                            @if($user->address)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg md:col-span-2">
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Address</p>
                                    <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ $user->address }}</p>
                                </div>
                            @endif

                            @if($user->city)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">City</p>
                                    <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ $user->city }}</p>
                                </div>
                            @endif

                            @if($user->country)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Country</p>
                                    <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ $user->country }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Status Section -->
            <div class="px-6 py-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Account Status</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border-l-4 border-blue-500">
                        <svg class="w-6 h-6 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Email Status</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                @if($user->email_verified_at)
                                    <span class="text-green-600">Verified</span>
                                @else
                                    <span class="text-yellow-600">Pending</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border-l-4 @if($user->admin_approved) border-green-500 @else border-yellow-500 @endif">
                        <svg class="w-6 h-6 @if($user->admin_approved) text-green-500 @else text-yellow-500 @endif mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Admin Approval</p>
                            <p class="text-sm font-semibold @if($user->admin_approved) text-green-600 @else text-yellow-600 @endif">
                                @if($user->admin_approved)
                                    Approved
                                @else
                                    Pending
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border-l-4 border-green-500">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 7H7v6h6V7z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Account Status</p>
                            <p class="text-sm font-semibold text-green-600">Active</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-6 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-600 flex gap-3 flex-wrap">
                <a href="{{ route('visitor.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
