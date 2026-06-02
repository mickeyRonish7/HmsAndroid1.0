<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Account Settings') }}
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
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Profile Photo Section -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-8 text-white">
                            <h3 class="text-lg font-semibold mb-6">{{ __('Profile Photo') }}</h3>
                            <div class="flex items-center gap-6">
                                <!-- Photo Preview Container -->
                                <div class="relative">
                                    @if($admin->profile_photo_path)
                                        <img id="photoPreview" src="{{ asset('storage/' . $admin->profile_photo_path) }}" alt="{{ $admin->name }}" class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg">
                                    @else
                                        <div id="photoPreview" class="h-32 w-32 rounded-full bg-blue-300 dark:bg-blue-700 flex items-center justify-center text-white text-5xl font-bold border-4 border-white shadow-lg">
                                            {{ substr($admin->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <!-- Camera Icon Badge -->
                                    <label for="profile_photo_path" class="absolute bottom-0 right-0 bg-white text-blue-600 p-2 rounded-full cursor-pointer hover:bg-blue-50 transition-colors shadow-md">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </label>
                                </div>

                                <!-- Upload Info -->
                                <div>
                                    <input type="file" id="profile_photo_path" name="profile_photo_path" class="hidden" accept="image/*" onchange="previewImage(event)">
                                    <label for="profile_photo_path" class="block px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg cursor-pointer hover:bg-blue-50 transition-colors mb-2 text-center">
                                        {{ __('Change Photo') }}
                                    </label>
                                    <p class="text-blue-100 text-sm">{{ __('Max 2MB') }}</p>
                                    <p class="text-blue-100 text-xs mt-2">{{ __('JPG, PNG, GIF') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="p-6 space-y-6">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Full Name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $admin->name) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Email Address') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Field -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Phone Number') }}
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $admin->phone) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Department Field -->
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Department') }}
                                </label>
                                <input type="text" id="department" name="department" value="{{ old('department', $admin->department) }}" placeholder="e.g., Administration, Management" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department') border-red-500 @enderror">
                                @error('department')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Security Section -->
                            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                    {{ __('Change Password') }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    {{ __('Leave blank if you do not want to change your password') }}
                                </p>

                                <!-- New Password Field -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('New Password') }}
                                    </label>
                                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password Field -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Confirm Password') }}
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror">
                                    @error('password_confirmation')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-4">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('Save Changes') }}
                            </button>
                            <a href="{{ route('admin.settings') }}" class="inline-flex items-center px-6 py-3 bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold rounded-lg transition-colors">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Image Preview -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('photoPreview');
            const container = preview.parentElement;
            
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // If current preview is a div, replace it with img
                    if (preview.tagName === 'DIV') {
                        const newImg = document.createElement('img');
                        newImg.id = 'photoPreview';
                        newImg.alt = 'Preview';
                        newImg.className = 'h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg';
                        newImg.src = e.target.result;
                        container.replaceChild(newImg, preview);
                    } else if (preview.tagName === 'IMG') {
                        // Update existing img
                        preview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            } else {
                alert('Please select a valid image file');
            }
        }
    </script>
</x-app-layout>
