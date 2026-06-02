<x-app-layout>
    <x-slot name="header">
        Verify Visitor Pass
    </x-slot>

    <div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Pass Verification Result -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <!-- Status Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-8 text-white">
                <div class="flex items-center justify-center mb-4">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-center">Pass Verified Successfully</h1>
                <p class="text-green-100 text-center mt-2">This is a valid hostel visitor pass</p>
            </div>

            <!-- Visitor Information -->
            <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Visitor Information</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Pass ID</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">PASS-{{ str_pad($visit->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Visitor Name</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $visit->visitor_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Phone Number</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $visit->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Visit Purpose</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $visit->purpose ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Student Information -->
            <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Host Student Information</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Student Name</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $visit->student->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Room Number</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $visit->student->bed ? $visit->student->bed->room->room_number : 'N/A' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Student Email</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $visit->student->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Visit Details -->
            <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Visit Details</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Valid Date</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Approved Date</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $visit->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Pass Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                            ✓ Approved
                        </span>
                    </div>
                </div>
            </div>

            <!-- Approval Details -->
            <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Approval Status</h2>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Student Approval -->
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border-l-4 border-blue-500">
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-2">Student Review</p>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Accepted</span>
                        </div>
                    </div>

                    <!-- Admin Approval -->
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border-l-4 border-purple-500">
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-2">Admin Approval</p>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Approved</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Information -->
            <div class="px-8 py-6 text-center border-t border-gray-200 dark:border-gray-700">
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">
                    ✓ This visitor pass has been verified and is valid for the specified date.
                </p>
                <p class="text-gray-500 dark:text-gray-400 text-xs">
                    Verified on {{ now()->format('M d, Y H:i A') }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex gap-4 justify-center">
            <a href="{{ route('visitor.pass') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to My Passes
            </a>
            <button onclick="window.print()" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Details
            </button>
        </div>
    </div>
</x-app-layout>
