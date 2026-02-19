<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logo-fts.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('logo-fts.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Lottie Player -->
        <script src="https://unpkg.com/@lottiefiles/lottie-player@2.0.8/dist/lottie-player.js" defer></script>

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

            <div class="relative z-10 w-full max-w-[360px] sm:max-w-2xl">
                <!-- Unified Card -->
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden flex flex-col sm:flex-row">
                    <!-- Left Side - Branding -->
                    <div class="sm:w-1/2 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 p-5 sm:p-8 flex flex-col justify-center">
                        <!-- Title on top - smaller on mobile -->
                        <div class="text-center mb-3 sm:mb-4">
                            <h1 class="text-lg sm:text-2xl font-bold text-white">File Tracking System</h1>
                        </div>

                        <!-- Lottie Animation -->
                        <div class="w-full max-w-[70px] sm:max-w-[120px] mx-auto h-16 sm:h-[120px]">
                            <lottie-player
                                src="/search.json"
                                background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%;"
                                loop
                                autoplay>
                            </lottie-player>
                        </div>

                        <!-- Text Content -->
                        <div class="text-center mt-3 sm:mt-4">
                            <p class="text-gray-300 text-xs sm:text-sm max-w-xs mx-auto">
                                Track, manage, and monitor your files efficiently.
                            </p>
                        </div>

                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="w-full p-5 sm:p-8 flex flex-col justify-center bg-white">
                        <!-- Welcome text -->
                        <div class="text-center mb-4 sm:mb-5">
                            <a href="/" wire:navigate class="flex justify-center">
                                <x-application-logo class="h-10 w-auto" />
                            </a>
                            <h1 class="mt-4 text-lg sm:text-xl font-bold text-gray-900">Welcome Back</h1>
                            <p class="mt-1 text-sm text-gray-500">Sign in to your account</p>
                        </div>

                        <!-- Form Content -->
                        <div class="w-full max-w-[260px] sm:max-w-xs mx-auto">
                            {{ $slot }}
                        </div>

                        <!-- Footer -->
                        <p class="mt-5 text-center text-xs text-gray-400">
                            &copy; {{ date('Y') }} FTS
                        </p>
                    </div>
                </div>
            </div>

            <!-- Toast Notifications -->
            <x-toast-notifications />
        </div>
    </body>
</html>
