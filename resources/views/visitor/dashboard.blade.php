<x-app-layout>
    <x-slot name="header">
        Visitor Dashboard
    </x-slot>

    <div class="max-w-7xl mx-auto">
        
        <!-- Welcome Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 reveal-up">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Welcome, {{ Auth::user()->name }}</h3>
                <p class="mt-1 text-gray-600">
                    Use this dashboard to request visit passes and check their status.
                    Please adhere to the visiting hours: <span class="font-bold">4:00 PM - 7:00 PM</span>.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 stagger-container">
            
            <!-- Request Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover-tilt">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Request a Visit</h3>
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 text-green-700 p-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('visitor.request') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Select Student</label>
                            <select name="student_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition">
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }}) - Room: {{ $student->bed ? $student->bed->room->room_number : 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Purpose of Visit</label>
                            <input type="text" name="purpose" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition" placeholder="e.g. Family Visit, Delivering items">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Date</label>
                            <input type="date" name="date" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 shadow-sm transition">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition shadow-lg">
                            Send Request
                        </button>
                    </form>
                </div>
            </div>

            <!-- History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover-tilt">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">My Requests</h3>
                    
                    @if($visits->isEmpty())
                        <p class="text-gray-500 text-center py-4">No requests found.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($visits as $visit)
                                <li class="py-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                Visiting: {{ $visit->student->name }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $visit->created_at->format('M d, Y') }} - {{ $visit->purpose }}
                                            </p>
                                        </div>
                                        <div>
                                            @if($visit->status == 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @elseif($visit->status == 'approved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ ucfirst($visit->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
