<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Hostel Management') }}</title>
    <!-- Home Logo Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Animations */
        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient-move 15s ease infinite;
        }
        @keyframes gradient-move {
            0% { background-position: 0% 50% }
            50% { background-position: 100% 50% }
            100% { background-position: 0% 50% }
        }
        
        .animation-delay-200 { animation-delay: 200ms; }
        .animation-delay-400 { animation-delay: 400ms; }
        
    </style>
</head>
<body class="antialiased text-gray-800 bg-white" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">

    <!-- Navigation -->
    <nav x-data="{ open: false }" class="absolute z-20 top-0 left-0 bg-blue-700" style="display:flex; align-items:center; width:100%; max-width:100%; overflow:visible;">
        <div style="width:100%; max-width:100%; padding:0 20px;">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:nowrap; width:100%; padding:12px 0;">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="#" class="flex items-center" style="white-space:nowrap;">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Manmohan Memorial Polytechnic" class="h-8 w-8 rounded-full object-cover shadow-lg mr-2">
                        <span class="font-bold text-white tracking-wide leading-tight" style="font-size:13px;">Manmohan Memorial<br><span style="font-size:11px;" class="font-semibold">Polytechnic</span></span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center" style="gap:16px;">
                    <a href="#features" class="text-gray-200 hover:text-white transition font-medium" style="font-size:13px; white-space:nowrap;">Features</a>
                    <a href="#rules" class="text-gray-200 hover:text-white transition font-medium" style="font-size:13px; white-space:nowrap;">Rules</a>
                    <a href="#contact" class="text-gray-200 hover:text-white transition font-medium" style="font-size:13px; white-space:nowrap;">Contact</a>
                    <a href="#form-notices" class="text-gray-200 hover:text-white transition font-medium" style="font-size:13px; white-space:nowrap;">Form Notices</a>
                    
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-white text-blue-700 font-bold rounded-full hover:bg-gray-100 transition shadow-lg" style="font-size:12.5px; padding:6px 14px; white-space:nowrap;">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="bg-transparent text-white font-bold rounded-full hover:bg-white/10 transition border border-white/30" style="font-size:12.5px; padding:6px 14px; white-space:nowrap;">Log in</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white font-bold rounded-full hover:bg-blue-500 transition shadow-lg border border-blue-500" style="font-size:12.5px; padding:6px 14px; white-space:nowrap;">Get Started</a>
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" @click.away="open = false" class="md:hidden absolute top-0 inset-x-0 p-2 transition transform origin-top-right">
            <div class="rounded-lg shadow-md bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
                <div class="px-5 pt-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Manmohan Memorial Polytechnic" class="h-8 w-8 rounded-full object-cover mr-2">
                        <span class="text-base font-bold text-blue-600 leading-tight">Manmohan Memorial Polytechnic</span>
                    </div>
                    <div class="-mr-2">
                        <button @click="open = false" type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="#features" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Features</a>
                    <a href="#rules" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Rules</a>
                    <a href="#contact" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Contact</a>
                    <a href="#form-notices" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Form Notices</a>
                </div>
                <div class="px-5 py-4 border-t border-gray-100">
                     @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">Dashboard</a>
                        @else
                             <a href="{{ route('login') }}" class="block text-center w-full px-4 py-2 border border-blue-600 rounded-md shadow-sm text-base font-medium text-blue-600 bg-white hover:bg-blue-50 mb-2">Log in</a>
                            <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">Sign up</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-gray-900 overflow-hidden">
        <div class="absolute inset-0">
             <img class="w-full h-full object-cover opacity-100" src="{{ asset('images/campus.jpg') }}" alt="Campus Aerial View">
             <div class="absolute inset-0 bg-black opacity-40"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 flex flex-col items-start justify-center min-h-[85vh]">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-7xl mb-6 drop-shadow-2xl">
                Your Home <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">Away From Home</span>
            </h1>
            <p class="mt-6 text-xl text-gray-200 max-w-3xl drop-shadow-md">
                Experience a secure, comfortable, and vibrant living environment designed for your academic success. Managing your hostel life has never been easier.
            </p>
            <div class="mt-10 max-w-sm sm:flex sm:max-w-none w-full sm:justify-start gap-4 flex flex-col sm:flex-row">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto text-center px-8 py-4 border border-transparent text-base font-bold rounded-full text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 md:text-lg md:px-10 transition duration-300 shadow-lg transform hover:-translate-y-1">
                        Go to Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto text-center mt-3 sm:mt-0 px-8 py-4 border-2 border-white/30 backdrop-blur-sm text-base font-bold rounded-full text-white bg-white/10 hover:bg-white/20 md:text-lg md:px-10 transition duration-300 shadow-lg">
                            Sign Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto text-center px-8 py-4 border border-transparent text-base font-bold rounded-full text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 md:text-lg md:px-10 transition duration-300 shadow-lg transform hover:-translate-y-1">
                        Student Registration
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto text-center mt-3 sm:mt-0 px-8 py-4 border-2 border-white/30 backdrop-blur-sm text-base font-bold rounded-full text-white bg-white/10 hover:bg-white/20 md:text-lg md:px-10 transition duration-300 shadow-lg">
                        Login Now
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Rules/Info Section -->
    <div id="rules" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <img class="rounded-lg shadow-2xl" src="{{ asset('images/reception.jpg') }}" alt="Modern Hostel Reception">
            </div>
            <div class="md:w-1/2 md:pl-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl mb-6">
                     Community Guidelines
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center border border-blue-200 mt-1">
                            <span class="text-blue-600 text-xs font-bold">1</span>
                        </div>
                        <p class="ml-4 text-lg text-gray-600">Respect quiet hours from 10 PM to 6 AM.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center border border-blue-200 mt-1">
                            <span class="text-blue-600 text-xs font-bold">2</span>
                        </div>
                        <p class="ml-4 text-lg text-gray-600">Visitors must register at the front desk and leave by 7 PM.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center border border-blue-200 mt-1">
                            <span class="text-blue-600 text-xs font-bold">3</span>
                        </div>
                        <p class="ml-4 text-lg text-gray-600">Keep common areas clean and report maintenance issues promptly.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Facilities</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Everything you need to live comfortably
                </p>
            </div>

            <div class="mt-20">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="flex flex-col items-center bg-white rounded-xl shadow-md hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 overflow-hidden">
                        <img src="{{ asset('images/room.jpg') }}" alt="Modern Room" class="w-full h-48 object-cover">
                        <div class="p-8 flex flex-col items-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Modern Rooms</h3>
                            <p class="text-center text-gray-500">Spacious, well-ventilated rooms with comfortable furniture and ample storage.</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex flex-col items-center bg-white rounded-xl shadow-md hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 overflow-hidden">
                        <img src="{{ asset('images/security.jpg') }}" alt="Secure & Safe" class="w-full h-48 object-cover">
                        <div class="p-8 flex flex-col items-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Secure & Safe</h3>
                            <p class="text-center text-gray-500">24/7 security surveillance, biometric entry logs, and visitor management.</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex flex-col items-center bg-white rounded-xl shadow-md hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 overflow-hidden">
                        <img src="{{ asset('images/wifi.png') }}" alt="High-Speed Wi-Fi" class="w-full h-48 object-cover">
                         <div class="p-8 flex flex-col items-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">High-Speed Wi-Fi</h3>
                            <p class="text-center text-gray-500">Uninterrupted internet access for your studies and entertainment needs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Notices Section -->
    <section id="form-notices" class="py-16 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">📋 Form Notices</h2>
                <p class="text-lg text-gray-600">Important forms and registration updates from the administration</p>
            </div>

            @if($activeForms && $activeForms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activeForms as $form)
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-shadow p-6 border-l-4 border-blue-600">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900 flex-1">{{ $form->title }}</h3>
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full whitespace-nowrap ml-2">Active</span>
                            </div>
                            
                            <p class="text-gray-600 mb-4 text-sm">{{ Str::limit($form->description, 100) }}</p>
                            
                            <div class="space-y-2 mb-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-6 4h6m-6 4h6m3-11h2a2 2 0 012 2v10a2 2 0 01-2 2h-2"></path>
                                    </svg>
                                    <span>Starts: {{ $form->start_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-6 4h6m-6 4h6m3-11h2a2 2 0 012 2v10a2 2 0 01-2 2h-2"></path>
                                    </svg>
                                    <span>Ends: {{ $form->end_date->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <a href="{{ route('public.registration.form') }}" class="w-full inline-block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">
                                View Form
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg">No active forms at this time. Please check back later.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800" id="contact">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-gray-400">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Manmohan Memorial Polytechnic" class="h-10 w-10 rounded-full object-cover">
                        <h3 class="text-white text-lg font-bold">Manmohan Memorial Polytechnic</h3>
                    </div>
                    <p class="text-sm">
                        Providing a home away from home for students. Secure, comfortable, and conducive to learning.
                    </p>
                </div>
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">Home</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Student Login</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Admin Login</a></li>
                    </ul>
                </div>
                <div>
                     <h3 class="text-white text-lg font-bold mb-4">Contact Us</h3>
                     <p class="text-sm mb-2">Manmohan memorial politechinc,morang,nepal</p>
                     <p class="text-sm mb-2">Phone: +977 9821736251</p>
                     <p class="text-sm">Email: mtulsi480@gmail.com</p>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8 text-center">
                <p class="text-base text-gray-400">&copy; {{ date('Y') }} Group of tulsi mandal. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Chatbot Component (Visible if configured for guest, or keep hidden for auth only) -->
    <!-- Ideally chatbot is for auth users only based on current implementation, but can be enabled for guests as 'visitor' context later -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="{{ asset('js/animations.js') }}"></script>
</body>
</html>
