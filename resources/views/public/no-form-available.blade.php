<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4">
        @if($latestForm)
            <!-- Form Details Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Notice Photo - Full Width -->
                @if($latestForm->notice_photo)
                    <div class="w-full bg-gray-900 flex items-center justify-center p-4">
                        <img src="{{ Storage::url($latestForm->notice_photo) }}" alt="{{ $latestForm->title }}" class="max-w-4xl max-h-screen w-full object-contain print:max-w-none">
                    </div>
                    
                    <!-- Photo Actions -->
                    <div class="flex gap-3 p-4 bg-gray-50 border-t justify-center">
                        <a href="{{ Storage::url($latestForm->notice_photo) }}" download="{{ $latestForm->title }}.jpg" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download
                        </a>
                        <button onclick="window.print()" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                    </div>
                @endif

                <!-- Form Information -->
                <div class="p-8">
                    <h1 class="text-3xl font-black text-gray-900 mb-2">{{ $latestForm->title }}</h1>
                    <p class="text-gray-600 text-sm mb-4">Catalog No: {{ $latestForm->id }}</p>
                    
                    @if($latestForm->description)
                        <p class="text-gray-700 text-base mb-6 leading-relaxed">{{ $latestForm->description }}</p>
                    @endif

                    <!-- Status Badge -->
                    <div class="mb-6">
                        @if($latestForm->is_active)
                            <span class="inline-block px-4 py-2 bg-green-100 text-green-800 font-bold rounded-full">✓ Currently Active</span>
                        @else
                            <span class="inline-block px-4 py-2 bg-red-100 text-red-800 font-bold rounded-full">✕ Currently Disabled</span>
                        @endif
                    </div>

                    <!-- Form Timeline -->
                    <div class="grid grid-cols-2 gap-6 mb-8 p-6 bg-gray-50 rounded-lg border-l-4 border-blue-600">
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wide font-semibold">Registration Starts</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $latestForm->start_date->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-600">{{ $latestForm->start_date->format('h:i A') }}</p>
                        </div>
                        <div class="border-l-4 border-red-600 pl-4">
                            <p class="text-xs text-gray-600 uppercase tracking-wide font-semibold">Registration Ends</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $latestForm->end_date->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-600">{{ $latestForm->end_date->format('h:i A') }}</p>
                        </div>
                    </div>

                    <!-- Status Message -->
                    @if(!$latestForm->is_active)
                        <div class="mb-8 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                            <p class="text-yellow-800 font-semibold">⚠️ Registration Currently Closed</p>
                            <p class="text-yellow-700 text-sm mt-1">This form is currently disabled. Please check back when it becomes active, or contact the administration for more information.</p>
                        </div>
                    @endif

                    <!-- Return Home Button -->
                    <div class="flex justify-center">
                        <a href="/" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition transform hover:scale-105 flex items-center gap-2">
                            ← Return to Home
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- No Form Available -->
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-gray-900 mb-3">No Forms Available</h1>
                    <p class="text-gray-600 mb-6">There are no registration forms available at this time. Please check back later or contact the administration.</p>
                    <a href="/" class="inline-block px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition transform hover:scale-105">
                        ← Return to Home
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .max-w-5xl {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .bg-white {
                box-shadow: none !important;
                border: none !important;
            }
            .flex.gap-3.p-4 {
                display: none !important;
            }
            .p-8 {
                display: none !important;
            }
            .min-h-screen {
                min-height: auto !important;
            }
            .py-12 {
                padding: 0 !important;
            }
            img {
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
</body>
</html>
