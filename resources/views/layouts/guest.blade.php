<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Lottie Player -->
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-700 via-green-800 to-gray-900 relative overflow-hidden p-4">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <!-- Decorative Accents -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-orange-500 rounded-full filter blur-3xl opacity-20 -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-red-500 rounded-full filter blur-3xl opacity-15 translate-y-1/2 -translate-x-1/2"></div>

            <!-- Unified Card -->
            <div class="relative z-10 w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">
                <!-- Left Side - Branding -->
                <div class="lg:w-1/2 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 p-8 lg:p-10 flex flex-col justify-center">
                    <!-- Lottie Animation -->
                    <div class="w-full max-w-xs mx-auto">
                        <lottie-player
                            src="/search.json"
                            background="transparent"
                            speed="1"
                            style="width: 100%; height: 200px;"
                            loop
                            autoplay>
                        </lottie-player>
                    </div>

                    <!-- Text Content -->
                    <div class="text-center mt-6">
                        <h2 class="text-2xl lg:text-3xl font-bold text-white mb-3">File Tracking System</h2>
                        <p class="text-gray-300 text-sm lg:text-base max-w-sm mx-auto">
                            Track, manage, and monitor your files efficiently across departments.
                        </p>
                    </div>

                    <!-- Features -->
                    <div class="mt-8 grid grid-cols-3 gap-4 text-center">
                        <div class="text-white">
                            <div class="w-10 h-10 bg-orange-500/30 rounded-lg flex items-center justify-center mx-auto mb-2 border border-orange-400/30">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-300">Track</p>
                        </div>
                        <div class="text-white">
                            <div class="w-10 h-10 bg-green-500/30 rounded-lg flex items-center justify-center mx-auto mb-2 border border-green-400/30">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-300">Send</p>
                        </div>
                        <div class="text-white">
                            <div class="w-10 h-10 bg-red-500/30 rounded-lg flex items-center justify-center mx-auto mb-2 border border-red-400/30">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-300">Receive</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="lg:w-1/2 p-8 lg:p-10 flex flex-col justify-center bg-white">
                    <!-- Logo & Header -->
                    <div class="text-center mb-6">
                        <a href="/" wire:navigate class="inline-block">
                            <x-application-logo class="h-12 w-auto mx-auto" />
                        </a>
                        <h1 class="mt-4 text-xl lg:text-2xl font-bold text-gray-900">Welcome Back</h1>
                        <p class="mt-1 text-sm text-gray-500">Sign in to your account</p>
                    </div>

                    <!-- Form Content -->
                    <div class="w-full max-w-sm mx-auto">
                        {{ $slot }}
                    </div>

                    <!-- Footer -->
                    <p class="mt-6 text-center text-xs text-gray-400">
                        &copy; {{ date('Y') }} File Tracking Management System
                    </p>
                </div>
            </div>

            <!-- Toast Notifications -->
            <x-toast-notifications />
        </div>
    </body>
</html>
