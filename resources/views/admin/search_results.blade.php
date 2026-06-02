<x-app-layout>
    <x-slot name="header">
        Search Results for: "{{ $query }}"
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Students Section -->
        @if($students->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Students</h3>
                <ul class="divide-y divide-gray-100">
                    @foreach($students as $student)
                        <li class="py-3 flex justify-between items-center hover:bg-gray-50 p-2 rounded transition">
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="font-bold text-gray-900">{{ $student->name }}</p>
                                    @if(!$student->is_approved)
                                        <span class="text-[10px] bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">Pending</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500">{{ $student->email }} | ID: {{ $student->student_id_number }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                @if(!$student->is_approved)
                                    <form action="{{ route('admin.users.approve', $student->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-2 rounded transition">
                                            Approve
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.students.profile', $student->id) }}" class="text-sm text-blue-600 hover:underline">View Profile</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Rooms Section -->
        @if($rooms->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Rooms</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($rooms as $room)
                        <div class="border rounded-lg p-4 text-center hover:shadow-md transition">
                            <p class="font-bold text-xl text-gray-800">{{ $room->room_number }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($room->type) }}</p>
                            <a href="{{ route('admin.rooms.show', $room->id) }}" class="mt-2 text-xs inline-block bg-indigo-100 text-indigo-700 px-2 py-1 rounded">View</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Complaints Section -->
        @if($complaints->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Complaints</h3>
                <ul class="divide-y divide-gray-100">
                    @foreach($complaints as $complaint)
                        <li class="py-3">
                            <div class="flex justify-between">
                                <span class="text-xs font-bold px-2 py-1 rounded {{ $complaint->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $complaint->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="font-bold mt-1 text-gray-900">{{ $complaint->category }}</p>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $complaint->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">By: {{ $complaint->student->name }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Visitors Section -->
        @if($visitors->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Visitors</h3>
                <ul class="divide-y divide-gray-100">
                    @foreach($visitors as $visitor)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="font-bold text-gray-900">{{ $visitor->visitor_name }}</p>
                                <p class="text-xs text-gray-500">To see: {{ $visitor->student ? $visitor->student->name : 'N/A' }} | Ph: {{ $visitor->phone }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $visitor->purpose }}</span>
                                <p class="text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($visitor->entry_time)->format('M d h:i A') }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Notices Section -->
        @if($notices->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                 <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Notices</h3>
                 <div class="space-y-4">
                    @foreach($notices as $notice)
                        <div class="border-l-4 border-purple-500 pl-4 py-1">
                            <h4 class="font-bold text-gray-900">{{ $notice->title }}</h4>
                            <p class="text-xs text-gray-500">{{ $notice->created_at->format('M d, Y') }} | To: {{ ucfirst($notice->audience) }}</p>
                        </div>
                    @endforeach
                 </div>
            </div>
        @endif

        <!-- Fees Section -->
        @if($fees->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Fees</h3>
                <ul class="divide-y divide-gray-100">
                    @foreach($fees as $fee)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="font-bold text-gray-900">{{ $fee->student ? $fee->student->name : 'Unknown Student' }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($fee->type) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">Rs. {{ number_format($fee->amount) }}</p>
                                <span class="text-[10px] font-bold px-2 py-1 rounded {{ $fee->status === 'pending' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($fee->status) }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($students->isEmpty() && $rooms->isEmpty() && $complaints->isEmpty() && $visitors->isEmpty() && $fees->isEmpty() && $notices->isEmpty())
            <div class="text-center py-12 text-gray-500 bg-white rounded-xl shadow-md p-6">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <p class="text-lg font-medium text-gray-900">No results found for "{{ $query }}"</p>
                @if($type !== 'all')
                    <p class="text-xs text-gray-400 mt-1">Filter active: <strong>{{ ucfirst($type) }}</strong></p>
                @endif
                <p class="text-sm mt-2">Try checking your spelling or using a different keyword.</p>
                @if(strlen($query) < 3 && $query != '')
                    <p class="text-xs text-yellow-600 mt-2">Tip: Use at least 3 characters for better results.</p>
                @endif
            </div>
        @endif

    </div>
</x-app-layout>
