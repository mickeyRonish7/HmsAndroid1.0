<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Manmohan Memorial Polytechnic" class="h-8 w-8 rounded-full object-cover shadow">
                    <a href="{{ route('dashboard') }}" class="text-base font-bold text-indigo-600 leading-tight">
                        Manmohan Memorial Polytechnic
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition">
                        Dashboard
                    </a>
                    @if(Auth::check() && Auth::user()->role == 'admin')
                        <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                            Rooms
                        </a>
                        <a href="{{ route('admin.users.pending') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                            Students (Pending)
                        </a>
                        <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                            Attendance
                        </a>
                         <a href="{{ route('admin.profile') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                            Profile
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Notification Bell -->
                    <div x-data="{ 
                            notifications: [], 
                            unreadCount: 0, 
                            open: false,
                            result: null,
                            
                            async fetchNotifications() {
                                try {
                                    const response = await fetch('{{ route('notifications.recent') }}');
                                    const data = await response.json();
                                    this.notifications = data.notifications;
                                    
                                    const countResponse = await fetch('{{ route('notifications.unread-count') }}');
                                    const countData = await countResponse.json();
                                    this.unreadCount = countData.count;
                                } catch (error) {
                                    console.error('Error fetching notifications:', error);
                                }
                            },

                            async markAsRead(id) {
                                try {
                                    await fetch(`/notifications/${id}/read`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        }
                                    });
                                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                                    // Optionally remove from list or mark visually
                                    window.location.href = '{{ route('notifications.index') }}';
                                } catch (error) {
                                    console.error('Error marking as read:', error);
                                }
                            },

                            init() {
                                this.fetchNotifications();
                                setInterval(() => this.fetchNotifications(), 30000); // Poll every 30s
                            }
                        }" 
                        x-init="init()"
                        class="relative mr-4"
                    >
                        <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none relative">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"></span>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" style="display: none;">
                            <div class="py-1">
                                <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                                    <span class="text-sm font-semibold text-gray-700">Notifications</span>
                                    <a href="{{ route('notifications.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800">View All</a>
                                </div>
                                <template x-if="notifications.length === 0">
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                        No new notifications
                                    </div>
                                </template>
                                <template x-for="notification in notifications" :key="notification.id">
                                    <a :href="notification.data.action_url || '#'" @click="markAsRead(notification.id)" class="block px-4 py-3 hover:bg-gray-50 transition duration-150 ease-in-out border-b border-gray-50 last:border-0">
                                        <div class="flex items-start">
                                            <div class="ml-3 w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900" x-text="notification.data.title || 'Notification'"></p>
                                                <p class="mt-1 text-xs text-gray-500" x-text="notification.data.message"></p>
                                                <p class="mt-1 text-xs text-gray-400" x-text="new Date(notification.created_at).toLocaleDateString()"></p>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                @auth
                    <!-- User Name -->
                    <div class="text-gray-500 font-medium text-sm">
                        {{ Auth::user()->name }}
                    </div>
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="ml-4">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-semibold">
                            Log Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline mr-4">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-700 underline">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
