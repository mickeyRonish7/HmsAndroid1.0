<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hostel Management') }}</title>
    <!-- Home Logo Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
        <!-- Background Image with Blur -->
        <div class="absolute inset-0 z-0">
             <img class="w-full h-full object-cover" src="{{ asset('images/campus.jpg') }}" alt="Campus">
             <div class="absolute inset-0 bg-blue-900 opacity-50 mix-blend-multiply backdrop-blur-sm"></div>
        </div>

        <!-- Logo -->
        <div class="relative z-10 mb-6">
            <a href="/" class="flex flex-col items-center">
                <div class="bg-white p-2 rounded-full shadow-lg mb-2">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Manmohan Memorial Polytechnic" class="w-16 h-16 rounded-full object-cover">
                </div>
                <span class="text-xl font-bold text-white tracking-wider drop-shadow-md text-center">Manmohan Memorial Polytechnic</span>
            </a>
        </div>

        <!-- Card Container -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-xl relative z-10 border border-gray-100">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
