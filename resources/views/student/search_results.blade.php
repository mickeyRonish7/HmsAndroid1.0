<x-app-layout>
    <x-slot name="header">
        Search Results for: "{{ $query }}"
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Notices Section -->
        @if($notices->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Notices</h3>
                <div class="space-y-4">
                    @foreach($notices as $notice)
                        <div class="border-l-4 border-blue-500 pl-4 py-2 hover:bg-gray-50 transition">
                            <h4 class="font-bold text-gray-900">{{ $notice->title }}</h4>
                            <p class="text-xs text-gray-500 mb-1">{{ $notice->created_at->format('M d, Y h:i A') }}</p>
                            <p class="text-sm text-gray-700">{{ $notice->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- My Complaints Section -->
        @if($myComplaints->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">My Complaints</h3>
                <ul class="divide-y divide-gray-100">
                    @foreach($myComplaints as $complaint)
                        <li class="py-3">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-bold px-2 py-1 rounded {{ $complaint->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $complaint->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="font-bold text-gray-900">{{ $complaint->category }}</p>
                            <p class="text-sm text-gray-600">{{ $complaint->description }}</p>
                        </li>
                    @endforeach
                </ul>
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
                                <p class="font-bold text-gray-900">{{ ucfirst($fee->type) }}</p>
                                <p class="text-xs text-gray-500">Due: {{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }}</p>
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

        @if($notices->isEmpty() && $myComplaints->isEmpty() && $fees->isEmpty())
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
