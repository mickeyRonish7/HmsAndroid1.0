<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Visitor Profiles Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if($visitors->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No visitor profiles found.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($visitors as $visitor)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow">
                            <!-- Header -->
                            <div class="h-24 bg-gradient-to-r from-green-400 to-blue-500"></div>

                            <!-- Profile Content -->
                            <div class="px-6 py-4 -mt-12">
                                <!-- Avatar -->
                                <div class="flex justify-center mb-4">
                                    @if($visitor->profile_photo_path)
                                        <img src="{{ asset('storage/' . $visitor->profile_photo_path) }}" 
                                             alt="{{ $visitor->name }}" 
                                             class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg object-cover">
                                    @else
                                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center border-4 border-white dark:border-gray-800 shadow-lg">
                                            <span class="text-5xl font-bold text-white">{{ substr($visitor->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-1">{{ $visitor->name }}</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400 text-center mb-4">{{ $visitor->email }}</p>

                                <!-- Details -->
                                <div class="space-y-2 mb-4 border-y border-gray-200 dark:border-gray-700 py-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Phone</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $visitor->phone }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Status</span>
                                        @if($visitor->is_active)
                                            <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full text-xs font-semibold">
                                                ✓ Active
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 rounded-full text-xs font-semibold">
                                                ✗ Disabled
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Pass Status</span>
                                        @php
                                            $latestVisit = \App\Models\Visitor::where('user_id', $visitor->id)->latest()->first();
                                        @endphp
                                        @if($latestVisit)
                                            <span class="px-2 py-1 text-xs font-semibold rounded
                                                {{ $latestVisit->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($latestVisit->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                                {{ ucfirst($latestVisit->status) }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Pass ID -->
                                @if($latestVisit)
                                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                        <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold">Pass ID</p>
                                        <p class="text-lg font-bold text-blue-800 dark:text-blue-300">
                                            PASS-{{ str_pad($latestVisit->id, 5, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="space-y-2">
                                    <!-- Edit Pass Status -->
                                    @if($latestVisit)
                                        <form action="{{ route('admin.visitors.update-pass', $visitor->id) }}" method="POST" class="mb-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex gap-2">
                                                <select name="pass_status" class="flex-1 text-xs px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                                    <option value="approved" {{ $latestVisit->status === 'approved' ? 'selected' : '' }}>Approve</option>
                                                    <option value="pending" {{ $latestVisit->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="rejected" {{ $latestVisit->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                                                </select>
                                                <button type="submit" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg transition">
                                                    Update
                                                </button>
                                            </div>
                                        </form>
                                    @endif

                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.visitors.toggle-status', $visitor->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 text-xs font-semibold rounded-lg transition
                                            {{ $visitor->is_active 
                                                ? 'bg-red-500 hover:bg-red-600 text-white' 
                                                : 'bg-green-500 hover:bg-green-600 text-white' }}">
                                            {{ $visitor->is_active ? '🔒 Disable Account' : '✓ Enable Account' }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                    Joined: {{ $visitor->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
