<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Form Submission Details</h2>
            <a href="{{ route('admin.form-submissions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Student Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    Student Information
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Student Name</label>
                        <p class="text-lg text-gray-800 dark:text-white">{{ $formSubmission->student_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Student ID</label>
                        <p class="text-lg text-gray-800 dark:text-white">{{ $formSubmission->student_id_no }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Student Number</label>
                        <p class="text-lg text-gray-800 dark:text-white">{{ $formSubmission->student_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Email</label>
                        <p class="text-lg text-blue-600 dark:text-blue-400">{{ $formSubmission->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Department</label>
                        <p class="text-lg text-gray-800 dark:text-white">{{ $formSubmission->department }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Semester</label>
                        <p class="text-lg text-gray-800 dark:text-white">{{ $formSubmission->semester }}</p>
                    </div>
                </div>
            </div>

            <!-- Parent Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    Parent Information
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Parent Name</label>
                        <p class="text-lg text-gray-800 dark:text-white">{{ $formSubmission->parent_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Parent Phone</label>
                        <p class="text-lg text-gray-800 dark:text-white">{{ $formSubmission->parent_phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">Phone Number</label>
                        <p class="text-lg text-gray-800 dark:text-white">{{ $formSubmission->phone_number }}</p>
                    </div>
                </div>
            </div>

            <!-- Photos Section -->
            @if ($formSubmission->id_card_photo || $formSubmission->application_photos)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        Submitted Photos
                    </h2>

                    @if ($formSubmission->id_card_photo)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">ID Card Photo</h3>
                            <div class="flex justify-center">
                                <img src="{{ asset('storage/' . $formSubmission->id_card_photo) }}" 
                                     alt="ID Card" class="max-w-full h-auto max-h-80 rounded-lg shadow-md">
                            </div>
                        </div>
                    @endif

                    @if ($formSubmission->application_photos && count($formSubmission->application_photos) > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Application Photos</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($formSubmission->application_photos as $photo)
                                    <div class="flex justify-center">
                                        <img src="{{ asset('storage/' . $photo) }}" 
                                             alt="Application Photo" class="max-w-full h-auto rounded-lg shadow-md">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar - Status and Actions -->
        <div class="lg:col-span-1">
            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Status & Actions</h3>
                
                <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Status</p>
                    <div class="inline-block px-4 py-2 rounded-full text-white text-sm font-bold
                        @if ($formSubmission->status === 'pending') bg-yellow-500
                        @elseif ($formSubmission->status === 'approved') bg-green-500
                        @else bg-red-500
                        @endif">
                        {{ ucfirst($formSubmission->status) }}
                    </div>
                </div>

                <form action="{{ route('admin.form-submissions.updateStatus', $formSubmission->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Update Status
                        </label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="pending" {{ $formSubmission->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $formSubmission->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $formSubmission->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Admin Notes
                        </label>
                        <textarea name="admin_notes" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Add notes about this submission...">{{ $formSubmission->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Update Status & Notes
                    </button>
                </form>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Timeline</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Submitted</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">
                            {{ $formSubmission->created_at->format('M d, Y H:i A') }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Last Updated</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">
                            {{ $formSubmission->updated_at->format('M d, Y H:i A') }}
                        </p>
                    </div>

                    @if ($formSubmission->processed_at)
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Processed</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                {{ $formSubmission->processed_at->format('M d, Y H:i A') }}
                            </p>
                            @if ($formSubmission->processedByUser)
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    by {{ $formSubmission->processedByUser->name ?? 'N/A' }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</x-app-layout>
