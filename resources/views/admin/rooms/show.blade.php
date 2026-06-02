<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Room Details') }} - {{ $room->room_number }}
            </h2>
            <a href="{{ route('admin.rooms.index') }}" class="text-sm text-blue-600 hover:underline">
                ← {{ __('Back to Rooms') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Room Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Room Photo -->
                    <div class="lg:w-2/5 relative min-h-[300px]">
                        @if($room->room_photo)
                            <img src="{{ asset('storage/' . $room->room_photo) }}" class="absolute inset-0 w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-blue-600">{{ $room->type }}</span>
                            <span class="px-3 py-1 {{ $room->status === 'active' ? 'bg-green-500' : 'bg-orange-500' }} text-white rounded-lg text-xs font-bold uppercase">{{ $room->status }}</span>
                        </div>
                    </div>

                    <!-- Room Details -->
                    <div class="lg:w-3/5 p-6">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Room {{ $room->room_number }}</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Total Beds</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $room->capacity }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Occupied</p>
                                <p class="text-2xl font-bold text-red-600">{{ $room->occupiedBedsCount() }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Available</p>
                                <p class="text-2xl font-bold text-green-600">{{ $room->availableBedsCount() }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Occupancy</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $room->capacity > 0 ? round(($room->occupiedBedsCount() / $room->capacity) * 100) : 0 }}%</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                {{ __('Edit Room') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bed Assignments -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('Bed Assignments') }}</h3>
                
                @if($room->beds->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($room->beds as $bed)
                            <div class="border {{ $bed->is_occupied ? 'border-red-300 bg-red-50 dark:bg-red-900/20' : 'border-green-300 bg-green-50 dark:bg-green-900/20' }} dark:border-gray-600 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $bed->bed_number }}</h4>
                                    <span class="px-2 py-1 text-xs font-bold rounded {{ $bed->is_occupied ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $bed->is_occupied ? __('Occupied') : __('Vacant') }}
                                    </span>
                                </div>
                                
                                @if($bed->student)
                                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center">
                                            @if($bed->student->profile_photo_path)
                                                <img src="{{ asset('storage/' . $bed->student->profile_photo_path) }}" class="w-10 h-10 rounded-full object-cover mr-3">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-3">
                                                    <span class="text-blue-600 dark:text-blue-300 font-bold">{{ substr($bed->student->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $bed->student->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $bed->student->student_id_number }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.students.profile', $bed->student->id) }}" class="mt-2 block text-xs text-blue-600 hover:underline">
                                            {{ __('View Profile') }} →
                                        </a>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ __('No student assigned') }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 dark:text-gray-400">{{ __('No beds found for this room.') }}</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
