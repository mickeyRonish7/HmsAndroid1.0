<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Room Occupancy Requests') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-[#f8f9fa] dark:bg-gray-700/50">
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Student') }}</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Requested Room') }}</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Current Assignment') }}</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Requested At') }}</th>
                                    <th class="px-4 py-3 text-right text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($requests as $request)
                                    <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($request->user)
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0 h-9 w-9">
                                                        @if($request->user->profile_photo_path)
                                                            <img class="h-9 w-9 rounded-full object-cover" src="{{ asset('storage/' . $request->user->profile_photo_path) }}" alt="">
                                                        @else
                                                            <div class="h-9 w-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold text-sm">
                                                                {{ substr($request->user->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $request->user->name }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $request->user->email }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-sm text-red-500 font-semibold">{{ __('User Deleted') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold dark:text-gray-300">
                                            @if($request->room)
                                                <div class="flex items-center gap-3">
                                                    @if($request->room->room_photo)
                                                        <img class="h-9 w-9 rounded-lg object-cover" src="{{ asset('storage/' . $request->room->room_photo) }}" alt="Room">
                                                    @else
                                                        <div class="h-9 w-9 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                            <span class="text-[8px] font-semibold text-gray-400">NO IMG</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        Room {{ $request->room->room_number }}
                                                        <span class="block text-[10px] font-medium text-gray-400 dark:text-gray-500 mt-0.5">{{ $request->room->type }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-red-500 font-semibold text-xs">{{ __('Room Deleted') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if($request->user && $request->user->bed)
                                                @if($request->user->bed->room)
                                                    Room {{ $request->user->bed->room->room_number }} (Bed {{ $request->user->bed->bed_number }})
                                                @else
                                                    Bed {{ $request->user->bed->bed_number }} (Room Deleted)
                                                @endif
                                            @else
                                                <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-medium">{{ __('Unassigned') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full
                                                {{ $request->status === 'pending' ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' : '' }}
                                                {{ $request->status === 'approved' ? 'bg-green-50 text-green-700 ring-1 ring-green-200' : '' }}
                                                {{ $request->status === 'rejected' ? 'bg-red-50 text-red-700 ring-1 ring-red-200' : '' }}
                                            ">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            @if($request->status === 'pending')
                                                <div x-data="{ open: false, rejectOpen: false }" class="flex items-center justify-end gap-1">
                                                    <!-- Approve Icon -->
                                                    <button @click="open = true" title="{{ __('Approve') }}" class="p-1.5 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                    
                                                    <!-- Reject Icon -->
                                                    <button @click="rejectOpen = true" title="{{ __('Reject') }}" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                    
                                                    <!-- View Icon -->
                                                    <button title="{{ __('View') }}" class="p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    </button>

                                                    <!-- Approve Modal -->
                                                    <template x-teleport="body">
                                                        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                                            <div class="flex items-center justify-center min-h-screen p-4">
                                                                <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/90 backdrop-blur-sm" @click="open = false"></div>
                                                                <div x-show="open" x-transition.scale class="relative bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md w-full text-left shadow-2xl">
                                                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-4">{{ __('Approve Room Assignment') }}</h3>
                                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 font-medium">
                                                                        {{ __('This student will be automatically assigned to the first available bed in Room') }} <span class="font-bold text-blue-600">{{ $request->room ? $request->room->room_number : 'Unknown' }}</span>.
                                                                    </p>
                                                                    <form action="{{ route('admin.room-requests.approve', $request->id) }}" method="POST">
                                                                        @csrf
                                                                        <div class="flex space-x-3">
                                                                            <button type="submit" class="flex-grow py-3 bg-blue-600 text-white font-black rounded-xl uppercase tracking-widest">{{ __('Confirm Assignment') }}</button>
                                                                            <button type="button" @click="open = false" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl">{{ __('Cancel') }}</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <!-- Reject Modal -->
                                                    <template x-teleport="body">
                                                        <div x-show="rejectOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                                            <div class="flex items-center justify-center min-h-screen p-4">
                                                                <div x-show="rejectOpen" x-transition.opacity class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/90 backdrop-blur-sm" @click="rejectOpen = false"></div>
                                                                <div x-show="rejectOpen" x-transition.scale class="relative bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md w-full text-left shadow-2xl">
                                                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-4">{{ __('Reject Room Request') }}</h3>
                                                                    <form action="{{ route('admin.room-requests.reject', $request->id) }}" method="POST">
                                                                        @csrf
                                                                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Reason for Rejection') }}</label>
                                                                        <textarea name="admin_note" rows="3" class="w-full rounded-xl border-gray-100 dark:border-gray-700 dark:bg-gray-900 text-sm mb-6" placeholder="{{ __('e.g. This room is reserved for graduating seniors.') }}"></textarea>
                                                                        <div class="flex space-x-3">
                                                                            <button type="submit" class="flex-grow py-3 bg-red-600 text-white font-black rounded-xl uppercase tracking-widest">{{ __('Reject Request') }}</button>
                                                                            <button type="button" @click="rejectOpen = false" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl">{{ __('Cancel') }}</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-600 text-xs font-medium">{{ __('Processed') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-16 text-center">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">{{ __('No pending room requests found.') }}</p>
                                            <p class="text-xs text-gray-300 dark:text-gray-600 mt-1">{{ __('All requests have been reviewed.') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
