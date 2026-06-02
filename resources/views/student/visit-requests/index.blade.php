<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visit Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Pending Requests -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('Pending Visit Requests') }}</h3>
                <p class="text-sm text-gray-600 mb-6">Review and accept/reject visitor requests to your room.</p>

                @if($visitRequests->isEmpty())
                    <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-xl">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Pending Requests</h3>
                        <p class="mt-1 text-sm text-gray-500">You don't have any new visit requests at the moment.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Visitor Details') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Visit Info') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($visitRequests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900">{{ $request->visitor_name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $request->phone }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 font-bold">
                                                {{ \Carbon\Carbon::parse($request->visit_date)->format('D, M d, Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500 mt-1">{{ $request->purpose }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap flex space-x-3">
                                            <form action="{{ route('student.visit-requests.accept', $request->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition shadow-sm">
                                                    {{ __('Accept') }}
                                                </button>
                                            </form>
                                            <form action="{{ route('student.visit-requests.reject', $request->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-bold hover:bg-red-200 transition shadow-sm">
                                                    {{ __('Reject') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Visit History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('Visit History') }}</h3>
                <p class="text-sm text-gray-600 mb-6">Past visit requests that you have processed.</p>

                @if($processedRequests->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <p>{{ __('No visit history available.') }}</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Visitor') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Purpose') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($processedRequests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $request->visitor_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $request->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($request->visit_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($request->purpose, 30) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($request->status === 'approved')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ __('Accepted') }}
                                                </span>
                                            @elseif($request->status === 'rejected')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ __('Rejected') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
