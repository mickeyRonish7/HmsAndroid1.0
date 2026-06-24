<x-app-layout>
    <x-slot name="header">
        {{ __('Admin Dashboard') }}
    </x-slot>

    <!-- Admin Stats Grid -->
    <div class="mb-6">
        <form action="{{ route('admin.search') }}" method="GET" class="relative">
            <input type="text" name="query" placeholder="Search by Student ID, name, room number..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition">
            <div class="absolute left-3 top-2.5 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-5">
        
        <!-- Total Students -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Total Students') }}</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white">{{ \App\Models\User::where('role', 'student')->count() }}</p>
                </div>
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-full text-indigo-600 dark:text-indigo-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
             <p class="text-xs text-gray-400 mt-1 flex items-center">
                <span class="text-green-500 mr-1">↑ 12%</span> {{ __('since last month') }}
            </p>
        </div>

        <!-- Room Occupancy -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-l-4 border-green-500">
            @php
                $totalBeds = \App\Models\Bed::count();
                $occupiedBeds = \App\Models\Bed::where('is_occupied', true)->count();
                $occupancyRate = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100) : 0;
            @endphp
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Occupancy Rate') }}</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white">{{ $occupancyRate }}%</p>
                </div>
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-full text-green-600 dark:text-green-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1 mt-1">
                <div class="bg-green-500 h-1 rounded-full" style="width: {{ $occupancyRate }}%"></div>
            </div>
        </div>

        <!-- Pending Fee -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-l-4 border-red-500">
             @php
                $totalDue = \App\Models\Fee::where('status', 'pending')->sum('amount');
            @endphp
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Pending Fees') }}</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white">Rs. {{ number_format($totalDue) }}</p>
                </div>
                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-full text-red-600 dark:text-red-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-1">
                {{ __('From') }} {{ \App\Models\Fee::where('status', 'pending')->count() }} {{ __('students') }}
            </p>
        </div>

        <!-- Feedback & Requests -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                     <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Attention Needed') }}</p>
                     <p class="text-lg font-bold text-gray-800 dark:text-white">{{ $pendingRequests + $pendingFeedback + $pendingVisits }}</p>
                </div>
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-full text-yellow-600 dark:text-yellow-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
            </div>
            <div class="mt-1 text-xs flex flex-wrap gap-1">
                 @if($pendingRequests > 0)
                    <a href="{{ route('admin.room-requests.index') }}" class="text-blue-600 hover:underline">{{ $pendingRequests }} {{ __('Rooms') }}</a>
                @endif
                @if($pendingFeedback > 0)
                    <span class="text-gray-400">|</span>
                    <a href="{{ route('admin.feedback.index') }}" class="text-blue-600 hover:underline">{{ $pendingFeedback }} {{ __('Feedback') }}</a>
                @endif
                @if($pendingVisits > 0)
                    <span class="text-gray-400">|</span>
                    <a href="{{ route('admin.visitors.requests') }}" class="text-blue-600 hover:underline font-bold text-orange-600">{{ $pendingVisits }} {{ __('Visits') }}</a>
                @endif
            </div>
        </div>

        <!-- Registration Forms -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-l-4 border-blue-500">
            @php
                $activeRegForm = \App\Models\RegistrationForm::where('is_active', true)->first();
                $totalSubmissions = \App\Models\FormSubmission::count();
            @endphp
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Registration') }}</p>
                    <p class="text-base font-bold text-gray-800 dark:text-white">{{ $activeRegForm ? '✓ Active' : '✕ Disabled' }}</p>
                </div>
                <div class="p-2 rounded-full {{ $activeRegForm ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ $totalSubmissions }} {{ __('Submissions') }}</p>
            <a href="{{ route('admin.registration-forms.index') }}" class="mt-2 inline-block px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition">
                Manage →
            </a>
        </div>
    </div>

    <!-- Professional Pie Chart Summary -->
    @php
        $totalBeds = \App\Models\Bed::count();
        $occupiedBeds = \App\Models\Bed::where('is_occupied', true)->count();
        $vacantBeds = max(0, $totalBeds - $occupiedBeds);
        $totalStudents = \App\Models\User::where('role', 'student')->count();
        $activeStudents = \App\Models\User::where('role', 'student')->where('is_active', true)->count();
        $inactiveStudents = max(0, $totalStudents - $activeStudents);
        $pendingFees = \App\Models\Fee::where('status', 'pending')->sum('amount');
        $paidFees = \App\Models\Fee::where('status', 'paid')->sum('amount');
    @endphp

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ __('Performance Overview') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('A quick visual summary of occupancy, student engagement, and fees.')}}</p>
            </div>
            <div class="flex gap-3">
                <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">{{ __('Occupied Beds') }}: {{ $occupiedBeds }}</span>
                <span class="px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-semibold">{{ __('Active Students') }}: {{ $activeStudents }}</span>
                <span class="px-3 py-1 rounded-full bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 text-xs font-semibold">{{ __('Pending Fees') }}: Rs. {{ number_format($pendingFees) }}</span>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="w-full h-80 p-4 bg-gray-50 dark:bg-gray-900/40 rounded-lg border border-gray-200 dark:border-gray-700">
                <canvas id="adminStatsPie" aria-label="{{ __('Dashboard data pie chart') }}" role="img"></canvas>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div class="p-4 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Room occupancy') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $occupancyRate ?? 0 }}%</p>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $occupancyRate ?? 0 }}%;"></div>
                    </div>
                </div>
                <div class="p-4 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Students status') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $activeStudents }} {{ __('Active') }} / {{ $inactiveStudents }} {{ __('Inactive') }}</p>
                </div>
                <div class="p-4 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Source of attention') }}</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-white">{{ ($pendingRequests + $pendingFeedback + $pendingVisits) ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4">{{ __('Quick Management') }}</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.users.pending') }}" class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-xl transition group">
                    <div class="p-2.5 bg-blue-200/50 dark:bg-blue-300/20 rounded-xl mb-2.5 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 text-center">{{ __('Add Student') }}</span>
                </a>
                <a href="{{ route('admin.rooms.create') }}" class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 rounded-xl transition group">
                    <div class="p-2.5 bg-green-200/50 dark:bg-green-300/20 rounded-xl mb-2.5 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 text-center">{{ __('Add Room') }}</span>
                </a>
                <a href="{{ route('admin.notices.create') }}" class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/40 rounded-xl transition group">
                    <div class="p-2.5 bg-purple-200/50 dark:bg-purple-300/20 rounded-xl mb-2.5 group-hover:scale-110 transition">
                         <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 text-center">{{ __('Post Notice') }}</span>
                </a>
                <a href="{{ route('admin.visitors.index') }}" class="flex flex-col items-center p-4 bg-orange-50 dark:bg-orange-900/20 hover:bg-orange-100 dark:hover:bg-orange-900/40 rounded-xl transition group">
                     <div class="p-2.5 bg-orange-200/50 dark:bg-orange-300/20 rounded-xl mb-2.5 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 text-center">{{ __('Log Visitor') }}</span>
                </a>
            </div>
        </div>

        <!-- Recent Activities (Pending Approvals) -->
         <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ __('Pending Registrations') }}</h3>
                <a href="{{ route('admin.users.pending') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">{{ __('View All') }}</a>
            </div>
            
            {{-- $recentPending is passed from Controller --}}

            @if($recentPending->count() > 0)
                <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($recentPending as $user)
                        <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        @if($user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[10px] bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-2 rounded transition">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded transition" onclick="return confirm('Are you sure you want to reject this registration?');">
                                            Reject
                                        </button>
                                    </form>
                                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="font-medium">{{ __('No pending registrations.') }}</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('adminStatsPie').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Occupied Beds', 'Vacant Beds', 'Active Students', 'Inactive Students'],
                    datasets: [{
                        data: [{{ $occupiedBeds }}, {{ $vacantBeds }}, {{ $activeStudents }}, {{ $inactiveStudents }}],
                        backgroundColor: ['#10b981', '#64748b', '#3b82f6', '#f59e0b'],
                        borderColor: ['#fff', '#fff', '#fff', '#fff'],
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {position: 'bottom', labels: {boxWidth: 14, padding: 12}},
                        tooltip: {mode: 'index', intersect: false}
                    }
                }
            });
        });
    </script>
</x-app-layout>

