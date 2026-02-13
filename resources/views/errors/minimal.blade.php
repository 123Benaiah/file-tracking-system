<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - {{ config('app.name', 'File Tracking System') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logo-fts.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Lottie Player -->
        <script src="https://unpkg.com/@lottiefiles/lottie-player@2.0.8/dist/lottie-player.js" defer></script>

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

            <div class="relative z-10 w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Lottie Animation -->
                <div class="flex justify-center pt-8 px-6">
                    <div class="w-48 h-48 sm:w-56 sm:h-56">
                        <lottie-player
                            src="/Error 404.json"
                            background="transparent"
                            speed="1"
                            loop
                            autoplay
                            style="width: 100%; height: 100%;"
                        ></lottie-player>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 sm:px-8 pb-8 text-center">
                    <h1 class="text-5xl sm:text-6xl font-bold text-gray-900 mb-2">@yield('code')</h1>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-3">@yield('title')</h2>
                    <p class="text-sm text-gray-500 mb-6">@yield('message', 'You may not have access to this page, or the page you are looking for does not exist.')</p>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center justify-center px-6 py-2.5 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-green-500/25">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Go to Dashboard
                        </a>
                        @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center px-6 py-2.5 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-green-500/25">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Go to Login
                        </a>
                        @endauth
                        <button onclick="history.back()"
                                class="inline-flex items-center justify-center px-6 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Go Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
