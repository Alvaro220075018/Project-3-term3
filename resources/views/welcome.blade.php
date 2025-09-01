<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Job-Hub: Discover local jobs, connect with employers, and apply effortlessly. Your next career move starts here!">
    <title>Welcome | Job-Hub</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet">

    <!-- Tailwind / Vite -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @endif

    <!-- Custom Styles -->
    <style>
        .hero-bg {
            background: linear-gradient(135deg, rgba(219, 39, 119, 0.85), rgba(59, 130, 246, 0.85)),
            url('{{ asset('images/bg.jpg') }}') no-repeat center center/cover fixed;
        }
        .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
        }
        .animate-slide-up {
            animation: slideUp 0.8s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .hover-scale { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-scale:hover { transform: scale(1.05); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
        .nav-link { transition: color 0.3s ease, background-color 0.3s ease; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col font-sans bg-gray-100">

<!-- Navbar -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <a href="/" class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <h1 class="text-2xl font-bold text-pink-600">Job-Hub</h1>
        </a>
        <nav class="flex items-center space-x-4">
            <a href="/" class="text-gray-700 hover:text-pink-600 font-medium nav-link px-3 py-2 rounded-lg">Home</a>
            <a href="{{ route('login') }}" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 shadow-md nav-link hover-scale">Login</a>
            <a href="{{ route('register') }}" class="bg-white text-pink-600 border border-pink-600 px-4 py-2 rounded-lg hover:bg-pink-50 shadow-md nav-link hover-scale">Register</a>
        </nav>
    </div>
</header>

<!-- Hero Section -->
<main class="relative flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20 hero-bg text-white z-10 min-h-screen">
    <div class="text-center max-w-4xl animate-fade-in">
        <h2 class="text-4xl sm:text-5xl md:text-6xl font-extrabold mb-6 drop-shadow-lg">
            Find Your Dream Job with Job-Hub
        </h2>
        <p class="text-lg sm:text-xl md:text-2xl mb-8 drop-shadow-md max-w-3xl mx-auto animate-slide-up">
            Explore local job opportunities, connect with employers, and apply with ease. Whether you're seeking part-time, freelance, or full-time roles, <span class="text-pink-300 font-semibold">Job-Hub</span> simplifies your job search with a focus on local opportunities.
        </p>
        <div class="flex justify-center space-x-4 animate-slide-up" style="animation-delay: 0.2s;">
            <a href="{{ route('register') }}" class="bg-pink-600 text-white px-6 sm:px-8 py-3 rounded-xl shadow-lg hover:bg-pink-700 nav-link hover-scale text-lg font-medium">
                Get Started
            </a>
            <a href="{{ route('login') }}" class="bg-transparent border-2 border-white text-white px-6 sm:px-8 py-3 rounded-xl hover:bg-white/20 nav-link hover-scale text-lg font-medium">
                Login
            </a>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="relative bg-gray-800 text-white py-8 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex justify-center space-x-6 mb-6">
            <a href="#" class="text-gray-300 hover:text-pink-600 transition">About Us</a>
            <a href="#" class="text-gray-300 hover:text-pink-600 transition">Contact</a>
            <a href="#" class="text-gray-300 hover:text-pink-600 transition">Privacy Policy</a>
            <a href="#" class="text-gray-300 hover:text-pink-600 transition">Terms of Service</a>
        </div>
        <p class="text-sm text-gray-400">&copy; {{ date('Y') }} {{ config('app.name', 'Job-Hub') }}. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
