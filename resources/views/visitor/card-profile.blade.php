<x-app-layout>
    <x-slot name="header">
        Profile Card
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 p-6">
        <div class="max-w-sm mx-auto">
            <!-- Profile Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                <!-- Gradient Header -->
                <div class="h-24 bg-gradient-to-r from-blue-600 to-indigo-600 relative"></div>

                <!-- Card Content -->
                <div class="px-6 py-6">
                    <!-- Avatar with Upload -->
                    <div class="flex justify-center -mt-12 mb-6 relative group">
                        <div class="relative">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg object-cover">
                            @else
                                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center border-4 border-white dark:border-gray-800 shadow-lg">
                                    <span class="text-5xl font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            
                            <!-- Upload Button Overlay -->
                            <label class="absolute bottom-0 right-0 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-2.5 cursor-pointer shadow-lg transition-colors" title="Upload Photo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <input type="file" 
                                       accept="image/*" 
                                       class="hidden" 
                                       id="profilePhotoInput"
                                       onchange="uploadProfilePhoto(this)">
                            </label>
                        </div>
                    </div>

                    <!-- Name and Role -->
                    <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white">{{ Auth::user()->name }}</h2>
                    <p class="text-center text-indigo-600 dark:text-indigo-400 font-semibold capitalize text-sm mt-1">{{ Auth::user()->role }}</p>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>

                    <!-- Contact Info -->
                    <div class="space-y-3 mb-4">
                        <!-- Email -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Email</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Phone</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ Auth::user()->phone ?? 'Not provided' }}</p>
                            </div>
                        </div>

                        <!-- Member Since -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Member Since</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badges -->
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <!-- Email Verification -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/40 rounded-lg p-2 text-center border border-blue-200 dark:border-blue-800">
                            <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold">
                                @if(Auth::user()->email_verified_at)
                                    ✓ Verified
                                @else
                                    ⏳ Pending
                                @endif
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Email</p>
                        </div>

                        <!-- Admin Approval -->
                        <div class="bg-gradient-to-br @if(Auth::user()->admin_approved) from-green-50 to-green-100 @else from-yellow-50 to-yellow-100 @endif dark:@if(Auth::user()->admin_approved) from-green-900/20 dark:to-green-900/40 @else from-yellow-900/20 dark:to-yellow-900/40 @endif rounded-lg p-2 text-center border @if(Auth::user()->admin_approved) border-green-200 dark:border-green-800 @else border-yellow-200 dark:border-yellow-800 @endif">
                            <p class="text-xs @if(Auth::user()->admin_approved) text-green-600 dark:text-green-400 @else text-yellow-600 dark:text-yellow-400 @endif font-semibold">
                                @if(Auth::user()->admin_approved)
                                    ✓ Approved
                                @else
                                    ⏳ Pending
                                @endif
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Admin</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors font-medium text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="contents">
                            @csrf
                            <button type="submit" class="flex items-center justify-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Footer Badge -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-center text-gray-600 dark:text-gray-300">
                        🏠 Smart Hostel Management System
                    </p>
                </div>
            </div>

            <!-- Additional Info Card (Optional) -->
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 7a1 1 0 100-2 1 1 0 000 2zm5 2a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                    </svg>
                    Quick Links
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('visitor.pass') }}" class="flex items-center gap-3 p-3 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h-2m0 0H8m4 0v2m0-2v-2m8 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">My Visitor Pass</span>
                        <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('visitor.dashboard') }}" class="flex items-center gap-3 p-3 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11l-7 7-7-7m0 0l7-7 7 7"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Request Visit</span>
                        <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function uploadProfilePhoto(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validate file size (max 5MB)
                if (file.size > 5120 * 1024) {
                    alert('File size must not exceed 5MB');
                    input.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a valid image file (JPEG, PNG, JPG, or GIF)');
                    input.value = '';
                    return;
                }
                
                const formData = new FormData();
                formData.append('profile_photo', file);

                // Show loading state
                const uploadBtn = document.querySelector('label[for="profilePhotoInput"]');
                const originalHTML = uploadBtn.innerHTML;
                uploadBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                uploadBtn.style.pointerEvents = 'none';

                // Get CSRF token from meta tag
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                console.log('Starting profile photo upload...', { fileName: file.name, fileSize: file.size, token: token ? 'Present' : 'Missing' });

                fetch('{{ route("profile.photo.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    console.log('Upload response status:', response.status);
                    if (!response.ok) {
                        return response.json().then(data => {
                            console.error('Upload error response:', data);
                            throw new Error(data.message || 'Upload failed with status ' + response.status);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Upload success response:', data);
                    if (data.success) {
                        // Show success message and reload after a short delay
                        alert('Profile photo uploaded successfully!');
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        alert('Error uploading photo: ' + (data.message || 'Unknown error'));
                        uploadBtn.innerHTML = originalHTML;
                        uploadBtn.style.pointerEvents = 'auto';
                        input.value = '';
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('Error uploading photo: ' + error.message);
                    uploadBtn.innerHTML = originalHTML;
                    uploadBtn.style.pointerEvents = 'auto';
                    input.value = '';
                });
            }
        }
    </script>
</x-app-layout>
