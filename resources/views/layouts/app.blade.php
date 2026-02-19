<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'file tracking system') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logo-fts.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('logo-fts.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"></noscript>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Puter.js AI SDK (client-side, free) -->
        <script src="https://js.puter.com/v2/"></script>
    </head>
    <body class="font-sans antialiased overflow-x-hidden">
        <div class="min-h-screen bg-gradient-to-br from-slate-100 via-gray-100 to-slate-200 relative overflow-x-hidden">

            <!-- Content Wrapper -->
            <div class="relative z-10">
                <livewire:layout.navigation />

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>

<!-- Toast Notifications -->
            <x-toast-notifications />
        </div>

        {{-- AI Chatbot Widget --}}
        @include('partials.chatbot')
    </body>
</html>
