<x-app-layout>
    <x-slot name="header">
        View Visitor Pass
    </x-slot>

    <style>
        .text-2xs {
            font-size: 0.625rem;
            line-height: 0.75rem;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            html, body {
                width: 100%;
                height: 100%;
                background: white !important;
                color: black !important;
                margin: 0 !important;
                padding: 0.3in !important;
            }

            .no-print, 
            .sidebar, 
            .topbar, 
            .space-y-8,
            .bg-gradient-to-r.from-blue-50,
            header {
                display: none !important;
            }

            .pass-container {
                max-width: 7.5in;
                margin: 0 auto;
                background: white !important;
                border: 2px solid #000 !important;
                page-break-inside: avoid !important;
                box-shadow: none !important;
                padding: 0 !important;
            }

            .pass-container * {
                background-color: transparent !important;
                color: #000 !important;
                border-color: #000 !important;
            }

            .pass-container img {
                display: block !important;
                visible: visible !important;
                opacity: 1 !important;
                max-width: 100% !important;
                height: auto !important;
                background: white !important;
                page-break-inside: avoid !important;
            }

            .bg-gradient-to-r {
                background: #000 !important;
                color: white !important;
            }

            .bg-gradient-to-r h1, 
            .bg-gradient-to-r p {
                color: white !important;
            }

            .space-y-6 {
                display: grid !important;
                gap: 6px !important;
            }

            .grid {
                display: grid !important;
            }

            .md\:grid-cols-3 {
                grid-template-columns: repeat(3, 1fr) !important;
            }

            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .gap-4 {
                gap: 6px !important;
            }

            .p-4, .px-6, .py-8 {
                padding: 6px !important;
            }

            .mt-2, .mt-1 {
                margin-top: 2px !important;
            }

            .text-xs {
                font-size: 9px !important;
            }

            .text-sm {
                font-size: 11px !important;
            }

            .text-base {
                font-size: 12px !important;
            }

            .text-lg {
                font-size: 13px !important;
            }

            .text-xl {
                font-size: 14px !important;
            }

            .h-48, .w-48 {
                height: 140px !important;
                width: 140px !important;
            }

            .h-24, .w-24 {
                height: 100px !important;
                width: 100px !important;
            }

            .rounded-xl, .rounded-2xl, .rounded-lg {
                border-radius: 4px !important;
            }

            .border, .border-t, .border-b {
                border-width: 1px !important;
                border-style: solid !important;
            }

            .py-8, .py-4 {
                padding-top: 6px !important;
                padding-bottom: 6px !important;
            }

            .px-6, .px-8 {
                padding-left: 6px !important;
                padding-right: 6px !important;
            }
        }
    </style>

    @php
        $roomNumber = $visit->student && $visit->student->bed && $visit->student->bed->room
            ? $visit->student->bed->room->room_number
            : 'N/A';

        $qrText = json_encode([
            'pass_id' => 'PASS-' . str_pad($visit->id, 5, '0', STR_PAD_LEFT),
            'visitor_name' => $visit->visitor_name,
            'visitor_phone' => $visit->phone,
            'student_name' => optional($visit->student)->name,
            'room_number' => $roomNumber,
            'visit_date' => $visit->visit_date,
            'purpose' => $visit->purpose,
            'approved_at' => $visit->created_at,
            'status' => $visit->status,
        ]);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=' . urlencode($qrText);
    @endphp

    <div class="min-h-screen bg-slate-50 px-4 py-8 dark:bg-gray-900 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl space-y-6">
            <section class="no-print overflow-hidden rounded-[2rem] bg-gradient-to-br from-slate-950 via-blue-900 to-cyan-700 text-white shadow-2xl shadow-blue-900/20">
                <div class="grid gap-6 px-6 py-8 sm:px-8 lg:grid-cols-[1.4fr,1fr]">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-blue-100">Approved Visitor Pass</p>
                        <h1 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">PASS-{{ str_pad($visit->id, 5, '0', STR_PAD_LEFT) }}</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-100 sm:text-base">
                            Keep this pass ready at the hostel entrance. You can print it, download the PDF version, or use the QR code for fast verification.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <div class="rounded-3xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100">Status</p>
                            <p class="mt-2 text-xl font-black text-emerald-100">Approved</p>
                        </div>
                        <div class="rounded-3xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100">Visit Date</p>
                            <p class="mt-2 text-xl font-black">{{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }}</p>
                        </div>
                        <div class="rounded-3xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100">Room</p>
                            <p class="mt-2 text-xl font-black">{{ $roomNumber }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="no-print flex flex-col gap-3 sm:flex-row">
                <button onclick="window.print()" class="inline-flex flex-1 items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Pass
                </button>
                <a href="{{ route('visitor.pass.download', $visit->id) }}" class="inline-flex flex-1 items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </a>
                <a href="{{ route('visitor.pass') }}" class="inline-flex flex-1 items-center justify-center rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Passes
                </a>
            </div>

            <div class="pass-container bg-white border border-slate-200 dark:border-gray-700 dark:bg-gray-800 overflow-hidden">
                <!-- Compact Header -->
                <div class="bg-slate-900 px-4 py-3 text-white sm:px-4">
                    <h1 class="text-lg font-black">Hostel Visitor Pass</h1>
                </div>

                <!-- Compact Content -->
                <div class="px-4 py-3 space-y-2">
                    <!-- Row 1: Pass ID, Status, Date -->
                    <div class="grid gap-2 grid-cols-3">
                        <div class="bg-slate-50 p-2 dark:bg-gray-700/50">
                            <p class="text-2xs font-semibold uppercase text-slate-600 dark:text-gray-400">Pass ID</p>
                            <p class="text-xs font-black text-slate-900 dark:text-white">PASS-{{ str_pad($visit->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="bg-emerald-50 p-2 dark:bg-emerald-900/30">
                            <p class="text-2xs font-semibold uppercase text-emerald-700 dark:text-emerald-300">Status</p>
                            <p class="text-xs font-bold text-emerald-700 dark:text-emerald-300">✓ Approved</p>
                        </div>
                        <div class="bg-amber-50 p-2 dark:bg-amber-900/30">
                            <p class="text-2xs font-semibold uppercase text-amber-700 dark:text-amber-300">Valid</p>
                            <p class="text-xs font-bold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($visit->visit_date)->format('M d') }}</p>
                        </div>
                    </div>

                    <!-- Row 2: Visitor & Student -->
                    <div class="grid gap-2 grid-cols-2">
                        <div class="border border-slate-200 p-2 dark:border-gray-600">
                            <p class="text-2xs font-semibold uppercase text-slate-600 dark:text-gray-400">Visitor</p>
                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $visit->visitor_name }}</p>
                            <p class="text-2xs text-slate-600 dark:text-gray-400 truncate">{{ $visit->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="border border-slate-200 p-2 dark:border-gray-600">
                            <p class="text-2xs font-semibold uppercase text-slate-600 dark:text-gray-400">Student</p>
                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ optional($visit->student)->name ?? 'N/A' }}</p>
                            <p class="text-2xs text-slate-600 dark:text-gray-400">Room {{ $roomNumber }}</p>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="flex flex-col items-center border border-dashed border-slate-300 bg-slate-50 p-2 dark:border-gray-600 dark:bg-gray-700/50">
                        <p class="text-2xs font-semibold uppercase text-slate-600 dark:text-gray-400 mb-1">QR Code</p>
                        <img src="{{ $qrUrl }}" alt="QR Code" class="h-24 w-24 border border-slate-200 p-1 bg-white dark:border-gray-600" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    </div>

                    <!-- Row 3: Additional Details -->
                    <div class="grid gap-2 grid-cols-3 text-2xs">
                        <div class="border border-slate-200 p-1.5 dark:border-gray-600">
                            <p class="font-semibold uppercase text-slate-600 dark:text-gray-400">Issued</p>
                            <p class="font-bold text-slate-900 dark:text-white">{{ $visit->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="border border-slate-200 p-1.5 dark:border-gray-600">
                            <p class="font-semibold uppercase text-slate-600 dark:text-gray-400">Email</p>
                            <p class="font-bold text-slate-900 dark:text-white truncate">{{ optional($visit->student)->email ?? 'N/A' }}</p>
                        </div>
                        <div class="border border-slate-200 p-1.5 dark:border-gray-600">
                            <p class="font-semibold uppercase text-slate-600 dark:text-gray-400">Time</p>
                            <p class="font-bold text-slate-900 dark:text-white">{{ $visit->created_at->format('h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Compact Footer -->
                <div class="border-t border-slate-200 bg-slate-50 px-4 py-2 text-center text-2xs text-slate-600 dark:border-gray-700 dark:bg-gray-900/40 dark:text-gray-400">
                    <p>Valid only for date shown - Present with ID at entrance</p>
                </div>
            </div>

                <!-- Footer Notes -->
                <div class="no-print bg-gradient-to-r from-blue-50 via-slate-50 to-slate-50 dark:from-blue-900/20 dark:to-gray-800 rounded-2xl border border-blue-200 dark:border-blue-900/40 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-700 dark:text-blue-300 mb-3">Important Notes</p>
                    <ul class="space-y-2 text-sm text-slate-700 dark:text-gray-300">
                        <li class="flex gap-2">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">•</span>
                            <span>Present this pass with a valid ID at the hostel entrance</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">•</span>
                            <span>This pass is valid only for the date mentioned above</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">•</span>
                            <span>Generated on {{ now()->format('M d, Y H:i A') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{--

    <div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Print Button -->
        <div class="mb-6 flex gap-3 no-print">
            <button onclick="window.print()" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Pass
            </button>
            <a href="{{ route('visitor.pass.download', $visit->id) }}" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download PDF
            </a>
            <a href="{{ route('visitor.pass') }}" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>

            <!-- Pass Card - Printable -->
            <div class="pass-container bg-white rounded-lg shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="pass-header">
                    <h1>Hostel Visitor Pass</h1>
                    <p>Valid for approved visit</p>
                </div>

                <!-- Pass ID and Status -->
                <div class="pass-section">
                    <div class="info-grid">
                        <div class="info-block">
                            <div class="info-label">Pass ID</div>
                            <div class="info-value">PASS-{{ str_pad($visit->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="info-block">
                            <div class="info-label">Status</div>
                            <div class="status-badge">✓ Approved</div>
                        </div>
                        <div class="info-block">
                            <div class="info-label">Valid Date</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Visitor Information -->
                <div class="pass-section">
                    <h3 style="font-weight: bold; margin-bottom: 12px; font-size: 13px; text-transform: uppercase; color: #666;">Visitor Details</h3>
                    <div class="info-grid">
                        <div class="info-block">
                            <div class="info-label">Visitor Name</div>
                            <div class="info-value">{{ $visit->visitor_name }}</div>
                        </div>
                        <div class="info-block">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">{{ $visit->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Student Information -->
                <div class="pass-section">
                    <h3 style="font-weight: bold; margin-bottom: 12px; font-size: 13px; text-transform: uppercase; color: #666;">Host Student Details</h3>
                    <div class="info-grid">
                        <div class="info-block">
                            <div class="info-label">Student Name</div>
                            <div class="info-value">{{ $visit->student->name }}</div>
                        </div>
                        <div class="info-block">
                            <div class="info-label">Room Number</div>
                            <div class="info-value">{{ $visit->student->bed ? $visit->student->bed->room->room_number : 'N/A' }}</div>
                        </div>
                        <div class="info-block">
                            <div class="info-label">Student Email</div>
                            <div class="info-value">{{ $visit->student->email }}</div>
                        </div>
                        <div class="info-block">
                            <div class="info-label">Approved Date</div>
                            <div class="info-value">{{ $visit->created_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Visit Purpose -->
                <div class="pass-section">
                    <div class="info-block">
                        <div class="info-label">Visit Purpose</div>
                        <div class="info-value">{{ $visit->purpose ?? 'Not specified' }}</div>
                    </div>
                </div>

                <!-- QR Code Section -->
                <div class="qr-section">
                    <div class="qr-label">Scan for Verification</div>
                    @php
                        $qrText = json_encode([
                            'pass_id' => 'PASS-' . str_pad($visit->id, 5, '0', STR_PAD_LEFT),
                            'visitor_name' => $visit->visitor_name,
                            'visitor_phone' => $visit->phone,
                            'student_name' => $visit->student->name,
                            'room_number' => $visit->student->bed ? $visit->student->bed->room->room_number : 'N/A',
                            'visit_date' => $visit->visit_date,
                            'purpose' => $visit->purpose,
                            'approved_at' => $visit->created_at,
                            'status' => $visit->status
                        ]);
                        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=' . urlencode($qrText);
                    @endphp
                    <div class="qr-code">
                        <img src="{{ $qrUrl }}" alt="QR Code">
                    </div>
                    <div class="qr-note">
                        Show this QR code at the hostel gate or entrance for quick verification
                    </div>
                </div>

                <!-- Approval Details -->
                <div class="pass-section">
                    <h3 style="font-weight: bold; margin-bottom: 12px; font-size: 13px; text-transform: uppercase; color: #666;">Approval Status</h3>
                    <div class="approval-grid">
                        <div class="approval-card">
                            <div class="approval-title">Student Review</div>
                            <div class="approval-status">✓ Accepted</div>
                        </div>
                        <div class="approval-card">
                            <div class="approval-title">Admin Approval</div>
                            <div class="approval-status">✓ Approved</div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="pass-footer">
                    <p style="margin-bottom: 5px;">Please present this pass along with a valid ID for verification</p>
                    <p style="margin-bottom: 3px;">Generated on {{ now()->format('M d, Y H:i A') }}</p>
                    <p>This pass is valid only for the date mentioned above</p>
                </div>
            </div>
        </div>
    </div>
    --}}
</x-app-layout>
