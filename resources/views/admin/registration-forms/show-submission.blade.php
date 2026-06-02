<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Submission Details</h2>
            <a href="{{ route('admin.registration-forms.submissions', $submission->registration_form_id) }}" class="text-blue-600 hover:underline">← Back to Submissions</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-green-800 font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Student Information</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Student Name</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $submission->student_name }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Student Number</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $submission->student_number }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Student ID NO</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $submission->student_id_no }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Email</label>
                                <p class="text-gray-900 dark:text-white">{{ $submission->email }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Phone Number</label>
                                <p class="text-gray-900 dark:text-white">{{ $submission->phone_number }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Department</label>
                                <p class="text-gray-900 dark:text-white">{{ $submission->department }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Semester</label>
                                <p class="text-gray-900 dark:text-white">{{ $submission->semester }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Parent Information</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Parent Name</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $submission->parent_name }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Parent Phone</label>
                                <p class="text-gray-900 dark:text-white">{{ $submission->parent_phone }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Uploaded Documents</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Student ID Card Photo</label>
                                @if($submission->id_card_photo)
                                    <img src="{{ asset('storage/' . $submission->id_card_photo) }}" class="w-64 rounded-lg shadow">
                                    <a href="{{ asset('storage/' . $submission->id_card_photo) }}" target="_blank" class="text-blue-600 hover:underline text-sm mt-2 block">View Full Size →</a>
                                @endif
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Application Photos</label>
                                <div class="grid grid-cols-3 gap-4">
                                    @if($submission->application_photos)
                                        @foreach($submission->application_photos as $photo)
                                            <div>
                                                <img src="{{ asset('storage/' . $photo) }}" class="w-full rounded-lg shadow">
                                                <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="text-blue-600 hover:underline text-xs mt-1 block">View →</a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Actions -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <h3 class="text-sm font-bold text-gray-500 uppercase mb-4">Status</h3>
                        
                        <div class="mb-4">
                            @if($submission->status === 'approved')
                                <span class="px-3 py-2 text-sm font-bold rounded bg-green-100 text-green-800 block text-center">✓ Approved</span>
                            @elseif($submission->status === 'rejected')
                                <span class="px-3 py-2 text-sm font-bold rounded bg-red-100 text-red-800 block text-center">✗ Rejected</span>
                            @else
                                <span class="px-3 py-2 text-sm font-bold rounded bg-yellow-100 text-yellow-800 block text-center">⏳ Pending</span>
                            @endif
                        </div>

                        @if($submission->status === 'pending')
                            <form action="{{ route('admin.registration-forms.approve-submission', $submission->id) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700">
                                    Approve Application
                                </button>
                            </form>

                            <button onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="w-full px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700">
                                Reject Application
                            </button>

                            <form id="rejectForm" action="{{ route('admin.registration-forms.reject-submission', $submission->id) }}" method="POST" class="mt-3 hidden">
                                @csrf
                                <textarea name="admin_notes" rows="3" placeholder="Reason for rejection..." class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-500 rounded-lg dark:bg-gray-700 dark:text-white mb-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 transition"></textarea>
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700">
                                    Confirm Rejection
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <h3 class="text-sm font-bold text-gray-500 uppercase mb-4">Submission Info</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div>
                                <label class="text-xs font-bold text-gray-500">Submitted On</label>
                                <p class="text-gray-900 dark:text-white">{{ $submission->created_at->format('M d, Y h:i A') }}</p>
                            </div>

                            @if($submission->processed_at)
                                <div>
                                    <label class="text-xs font-bold text-gray-500">Processed On</label>
                                    <p class="text-gray-900 dark:text-white">{{ $submission->processed_at->format('M d, Y h:i A') }}</p>
                                </div>
                            @endif

                            @if($submission->processedByUser)
                                <div>
                                    <label class="text-xs font-bold text-gray-500">Processed By</label>
                                    <p class="text-gray-900 dark:text-white">{{ $submission->processedByUser->name }}</p>
                                </div>
                            @endif

                            @if($submission->admin_notes)
                                <div>
                                    <label class="text-xs font-bold text-gray-500">Admin Notes</label>
                                    <p class="text-gray-900 dark:text-white">{{ $submission->admin_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
