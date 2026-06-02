<x-app-layout>
    <x-slot name="header">
        Visitor Pass Management
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

            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .grid, section {
                display: grid !important;
            }

            .no-print, 
            .space-y-8, 
            .rounded-\[2rem\].border.border-amber-200,
            .min-h-screen > div > div > section:first-child {
                display: none !important;
            }

            .grid.gap-3 {
                gap: 2px !important;
                grid-template-columns: repeat(4, 1fr) !important;
                padding: 4px !important;
            }

            article {
                box-shadow: none !important;
                page-break-inside: avoid !important;
                transform: none !important;
                margin: 0 !important;
                padding: 8px !important;
                border: 1px solid #000 !important;
                background: white !important;
            }

            article img {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                max-width: 100% !important;
                height: auto !important;
            }

            article div, article p, article span {
                background: transparent !important;
                color: black !important;
            }

            #printContent {
                display: block !important;
            }
        }
    </style>

    @php
        $approvedCount = $visits->count();
        $latestVisit = $visits->first();
    @endphp

    <div class="min-h-screen bg-slate-50 px-4 py-8 dark:bg-gray-900 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">
            <section class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-slate-900 via-blue-900 to-cyan-700 text-white shadow-2xl shadow-blue-900/20">
                <div class="grid gap-8 px-6 py-8 sm:px-8 lg:grid-cols-[1.5fr,0.9fr] lg:px-10 lg:py-10">
                    <div class="space-y-5">
                        <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-blue-100">
                            Visitor Pass Center
                        </span>
                        <div class="space-y-3">
                            <h1 class="max-w-2xl text-3xl font-black tracking-tight sm:text-4xl">
                                Manage, view, and share your approved visitor passes in one clean place.
                            </h1>
                            <p class="max-w-2xl text-sm leading-6 text-blue-100 sm:text-base">
                                Open any pass to see its full details, print it for gate verification, or download a PDF copy for quick access on your phone.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('visitor.dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-blue-50">
                                Request Another Visit
                            </a>
                            @if ($approvedCount > 0)
                                <a href="{{ route('visitor.pass.show', $latestVisit->id) }}" class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
                                    Open Latest Pass
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <div class="rounded-3xl border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-100">Approved Passes</p>
                            <p class="mt-3 text-4xl font-black">{{ $approvedCount }}</p>
                            <p class="mt-2 text-sm text-blue-100">Ready to show at the hostel entrance.</p>
                        </div>
                        <div class="rounded-3xl border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-100">Latest Valid Date</p>
                            <p class="mt-3 text-xl font-bold">
                                {{ $latestVisit ? \Carbon\Carbon::parse($latestVisit->visit_date)->format('M d, Y') : 'No pass yet' }}
                            </p>
                            <p class="mt-2 text-sm text-blue-100">Keep your newest pass ready before arrival.</p>
                        </div>
                        <div class="rounded-3xl border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-100">Gate Reminder</p>
                            <p class="mt-3 text-sm font-medium leading-6 text-white">
                                Bring your pass with a valid ID and keep the QR code visible for faster verification.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            @if ($visits->isEmpty())
                <section class="rounded-[2rem] border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-3-3v6m8 0A9 9 0 103 12a9 9 0 0018 0z"></path>
                        </svg>
                    </div>
                    <h2 class="mt-6 text-2xl font-black text-slate-900 dark:text-white">No approved visitor passes yet</h2>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600 dark:text-gray-300">
                        Your approved passes will appear here after your visit request is reviewed. You can then open the pass, print it, or download a PDF copy.
                    </p>
                    <a href="{{ route('visitor.dashboard') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Go to Visit Requests
                    </a>
                </section>
            @else
                <section class="grid gap-3 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($visits as $visit)
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
                            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' . urlencode($qrText);
                        @endphp

                        <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-md transition hover:shadow-lg dark:border-gray-700 dark:bg-gray-800 flex flex-col" style="min-height: 300px;">
                            <!-- Compact Header -->
                            <div class="overflow-hidden bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-2 text-white">
                                <p class="text-xs font-bold uppercase tracking-tighter text-blue-100">Pass</p>
                                <h3 class="text-sm font-black text-white">PASS-{{ str_pad($visit->id, 5, '0', STR_PAD_LEFT) }}</h3>
                            </div>

                            <!-- Compact Content -->
                            <div class="space-y-2 px-3 py-3 flex-1">
                                <!-- Basic Info -->
                                <div class="space-y-1">
                                    <div>
                                        <p class="text-2xs font-semibold uppercase text-slate-500 dark:text-gray-400">Visitor</p>
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $visit->visitor_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-2xs font-semibold uppercase text-slate-500 dark:text-gray-400">Student</p>
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ optional($visit->student)->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="flex-1">
                                            <p class="text-2xs font-semibold uppercase text-slate-500 dark:text-gray-400">Room</p>
                                            <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $roomNumber }}</p>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-2xs font-semibold uppercase text-slate-500 dark:text-gray-400">Date</p>
                                            <p class="text-xs font-bold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($visit->visit_date)->format('M d') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Code -->
                                <div class="flex justify-center py-2">
                                    <img src="{{ $qrUrl }}" alt="QR Code" class="h-24 w-24 rounded border border-slate-200 p-1 dark:border-gray-600">
                                </div>

                                <!-- Status -->
                                <div class="text-center">
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-2xs font-bold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        ✓ Approved
                                    </span>
                                </div>
                            </div>

                            <!-- Compact Actions -->
                            <div class="flex gap-2 border-t border-slate-200 bg-slate-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-900/40">
                                <button onclick="printPass('pass-{{ $visit->id }}')" class="flex-1 inline-flex items-center justify-center rounded-md bg-slate-900 px-2 py-1.5 text-2xs font-semibold text-white transition hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                </button>
                                <a href="{{ route('visitor.pass.download', $visit->id) }}" class="flex-1 inline-flex items-center justify-center rounded-md bg-emerald-600 px-2 py-1.5 text-2xs font-semibold text-white transition hover:bg-emerald-700">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </section>

                <section class="rounded-[2rem] border border-amber-200 bg-gradient-to-r from-amber-50 via-orange-50 to-white p-6 shadow-sm dark:border-amber-900 dark:from-amber-950/40 dark:via-orange-950/30 dark:to-gray-800">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700 dark:text-amber-300">Entry Tips</p>
                            <h2 class="mt-2 text-xl font-black text-slate-900 dark:text-white">Make entry faster at the hostel gate</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-gray-300">
                                Keep your pass open on your phone, or download the PDF in advance. Gate staff can verify your QR code and pass ID in seconds.
                            </p>
                        </div>
                        <a href="{{ route('visitor.dashboard') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-white dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                            Back to Visitor Dashboard
                        </a>
                    </div>
                </section>

                {{--
                @foreach ($visits as $visit)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition">
                        <!-- Pass Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Pass ID</p>
                                    <p class="text-white text-lg font-bold">PASS-{{ str_pad($visit->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                    ✓ Approved
                                </span>
                            </div>
                        </div>

                        <!-- Pass Content -->
                        <div class="px-6 py-4 space-y-3">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Visitor Name</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $visit->visitor_name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Phone</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $visit->phone ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Student Name</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $visit->student->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Room Number</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $visit->student->bed ? $visit->student->bed->room->room_number : 'N/A' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Purpose</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $visit->purpose ?? 'No specific purpose mentioned' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Visit Date</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Approved Date</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $visit->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600 flex gap-3">
                            <a href="{{ route('visitor.pass.show', $visit->id) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Pass
                            </a>
                            <a href="{{ route('visitor.pass.download', $visit->id) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Additional Help -->
            <div class="mt-8 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    💡 <strong>Tip:</strong> You can download your pass as PDF and show it at the hostel gate. Share the QR code with the gatekeeper for quick verification.
                </p>
            </div>
                --}}
            @endif
        </div>
    </div>

    <script>
        function printPass(passId) {
            const id = passId.replace('pass-', '');
            window.location.href = "/visitor/pass/" + id;
        }
    </script>
</x-app-layout>
