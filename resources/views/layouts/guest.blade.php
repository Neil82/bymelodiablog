<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script>
            // Prevent FOUC by applying theme immediately
            (function() {
                const theme = localStorage.getItem('theme') || 'light';
                document.documentElement.classList.add(theme);
            })();
        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Language Selector in Header -->
        <div class="absolute top-4 left-4">
            @include('components.language-selector')
        </div>

        <title>Login - ByMelodia Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 relative">
            <!-- Theme Toggle Button -->
            <div class="absolute top-4 right-4">
                <button
                    id="theme-toggle"
                    class="p-2 rounded-lg bg-white dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors shadow-md"
                    aria-label="Toggle theme"
                >
                    <span id="theme-icon" class="text-xl">🌙</span>
                </button>
            </div>
            
            <div>
                <a href="/">
                    <img 
                        id="logo" 
                        src="/images/bymelodia_negro.png" 
                        alt="ByMelodia" 
                        class="h-16 w-auto"
                    >
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
