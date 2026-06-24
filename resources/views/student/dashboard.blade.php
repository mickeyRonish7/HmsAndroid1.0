<x-app-layout>
    <x-slot name="header">
        {{ __('Student Dashboard') }}
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">

        <!-- My Room -->
        <a href="{{ route('student.room') }}" class="bg-white dark:bg-gray-800 rounded-xl p-4 border-l-4 border-blue-500 border border-gray-100 dark:border-gray-700 transition hover:shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ __('My Room') }}</p>
                    <p class="text-base font-bold text-gray-800 dark:text-white mt-0.5">{{ Auth::user()->bed ? __('Room').' '.Auth::user()->bed->room->room_number : __('Not Assigned') }}</p>
                    @if(Auth::user()->bed)
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Bed') }} {{ Auth::user()->bed->bed_number }}</p>
                    @endif
                </div>
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        </a>

        <!-- Academic Info -->
        <a href="{{ route('student.profile') }}" class="bg-white dark:bg-gray-800 rounded-xl p-4 border-l-4 border-purple-500 border border-gray-100 dark:border-gray-700 transition hover:shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ __('Academic Info') }}</p>
                    <p class="text-base font-bold text-gray-800 dark:text-white mt-0.5">{{ Auth::user()->year }} | {{ Auth::user()->semester }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->department }}</p>
                </div>
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
        </a>

        <!-- Pending Fees -->
        <a href="{{ route('student.fees') }}" class="bg-white dark:bg-gray-800 rounded-xl p-4 border-l-4 border-red-500 border border-gray-100 dark:border-gray-700 transition hover:shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ __('Pending Fees') }}</p>
                    @php $pendingFees = Auth::user()->fees()->where('status', 'pending')->sum('amount'); @endphp
                    <p class="text-base font-bold text-gray-800 dark:text-white mt-0.5">Rs. {{ number_format($pendingFees) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Due amount') }}</p>
                </div>
                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg text-red-600 dark:text-red-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </a>

        <!-- Attendance -->
        <a href="{{ route('student.attendance') }}" class="bg-white dark:bg-gray-800 rounded-xl p-4 border-l-4 border-green-500 border border-gray-100 dark:border-gray-700 transition hover:shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ __('Attendance') }}</p>
                    @php
                        $presentDays = \App\Models\Attendance::where('student_id', Auth::id())
                            ->whereMonth('date', now()->month)->where('status', 'present')->count();
                    @endphp
                    <p class="text-base font-bold text-gray-800 dark:text-white mt-0.5">{{ $presentDays }} {{ __('Days') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('This Month') }}</p>
                </div>
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-4">

            <!-- Recent Attendance -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('Recent Attendance') }}
                    </h3>
                    <a href="{{ route('student.attendance') }}" class="text-xs font-medium text-blue-600 hover:underline">{{ __('View All') }}</a>
                </div>
                @php
                    $recentAttendance = \App\Models\Attendance::where('student_id', Auth::id())->latest('date')->take(5)->get();
                @endphp
                @if($recentAttendance->count() > 0)
                    <div class="overflow-x-auto p-0">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/50">
                                    <th class="px-4 py-2.5 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ __('Date') }}</th>
                                    <th class="px-4 py-2.5 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ __('In') }}</th>
                                    <th class="px-4 py-2.5 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ __('Out') }}</th>
                                    <th class="px-4 py-2.5 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($recentAttendance as $record)
                                    <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                                        <td class="px-4 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-sm text-gray-600">{{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('h:i A') : '-' }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-sm text-gray-600">{{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('h:i A') : '-' }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-semibold rounded-full bg-green-50 text-green-700 ring-1 ring-green-200">{{ ucfirst($record->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                        <svg class="w-10 h-10 mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10m-6 4h6m-6 4h6m3-11h2a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>
                        <p class="text-sm font-medium">{{ __('No attendance records found.') }}</p>
                    </div>
                @endif
            </div>

            <!-- Recent Notices -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        {{ __('Recent Notices') }}
                    </h3>
                    <a href="#" class="text-xs font-medium text-blue-600 hover:underline">{{ __('View All') }}</a>
                </div>
                @php $notices = \App\Models\Notice::latest()->take(3)->get(); @endphp
                <div class="p-4 space-y-4">
                    @forelse($notices as $notice)
                        <div class="border-l-4 border-indigo-400 pl-3">
                            <div class="flex justify-between items-start">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $notice->title }}</h4>
                                <span class="text-[10px] font-medium text-gray-400 whitespace-nowrap ml-2">{{ $notice->created_at->format('M d') }}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ $notice->content }}</p>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                            <svg class="w-10 h-10 mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <p class="text-sm font-medium">{{ __('No notices posted yet.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-4">

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4">
                <h3 class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-3">{{ __('Quick Actions') }}</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('student.id-card') }}" class="flex flex-col items-center justify-center p-3.5 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-xl transition group">
                        <div class="p-2 bg-blue-200/50 dark:bg-blue-300/20 rounded-lg mb-1.5 group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ __('ID Card') }}</span>
                    </a>
                    <a href="{{ route('student.feedback.create') }}" class="flex flex-col items-center justify-center p-3.5 bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/40 rounded-xl transition group">
                        <div class="p-2 bg-pink-200/50 dark:bg-pink-300/20 rounded-lg mb-1.5 group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ __('Feedback') }}</span>
                    </a>
                    <a href="{{ route('student.fees') }}" class="flex flex-col items-center justify-center p-3.5 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/40 rounded-xl transition group">
                        <div class="p-2 bg-amber-200/50 dark:bg-amber-300/20 rounded-lg mb-1.5 group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ __('Payment') }}</span>
                    </a>
                    <a href="{{ route('student.complaints.create') }}" class="flex flex-col items-center justify-center p-3.5 bg-orange-50 dark:bg-orange-900/20 hover:bg-orange-100 dark:hover:bg-orange-900/40 rounded-xl transition group">
                        <div class="p-2 bg-orange-200/50 dark:bg-orange-300/20 rounded-lg mb-1.5 group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ __('Complaint') }}</span>
                    </a>
                </div>
            </div>

            <!-- Profile Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-center">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="w-14 h-14 rounded-full mx-auto object-cover ring-2 ring-white/50">
                    @else
                        <div class="w-14 h-14 rounded-full mx-auto bg-white/20 flex items-center justify-center text-white text-xl font-bold ring-2 ring-white/50">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="text-sm font-bold text-white mt-2">{{ Auth::user()->name }}</h3>
                    <p class="text-xs text-blue-100">{{ Auth::user()->email }}</p>
                </div>
                <div class="p-4 text-center">
                    <a href="{{ route('student.profile') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ __('Manage Profile') }}
                    </a>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('My Activity Log') }}
                    </h3>
                    @if($recentActivities->count() > 0)
                        <form action="{{ route('student.activity-logs.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your activity logs?');">
                            @csrf
                            <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-600 flex items-center gap-1 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                {{ __('Clear') }}
                            </button>
                        </form>
                    @endif
                </div>
                <div class="p-4">
                    @if($recentActivities->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentActivities as $activity)
                                <div class="border-l-3 border-blue-400 pl-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $activity->action)) }}</h4>
                                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">{{ $activity->module }}</p>
                                        </div>
                                        <span class="text-[10px] font-medium text-gray-400 whitespace-nowrap ml-2">{{ $activity->created_at->format('M d') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                            <svg class="w-10 h-10 mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm font-medium">{{ __('No recent activities.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

