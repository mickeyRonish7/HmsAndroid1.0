<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Submissions: {{ $registrationForm->title }}</h2>
                <p class="text-sm text-gray-500">Total: {{ $submissions->total() }} applications</p>
            </div>
            <a href="{{ route('admin.registration-forms.index') }}" class="text-blue-600 hover:underline">← Back to Forms</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-green-800 font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Student Info</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Department/Sem</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($submissions as $submission)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $submission->student_name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $submission->student_id_no }}</div>
                                        <div class="text-xs text-gray-500">No: {{ $submission->student_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        <div>{{ $submission->email }}</div>
                                        <div class="text-xs text-gray-500">{{ $submission->phone_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        <div>{{ $submission->department }}</div>
                                        <div class="text-xs text-gray-500">Sem: {{ $submission->semester }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $submission->created_at->format('M d, Y') }}
                                        <div class="text-xs">{{ $submission->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($submission->status === 'approved')
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-800">Approved</span>
                                        @elseif($submission->status === 'rejected')
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm space-x-2">
                                        <a href="{{ route('admin.registration-forms.show-submission', $submission->id) }}" class="text-blue-600 hover:underline font-bold">View</a>
                                        
                                        @if($submission->status === 'pending')
                                            <form action="{{ route('admin.registration-forms.approve-submission', $submission->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:underline font-bold">Approve</button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.registration-forms.delete-submission', $submission->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this submission?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline font-bold">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">No submissions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($submissions->hasPages())
                    <div class="px-6 py-4 border-t">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
