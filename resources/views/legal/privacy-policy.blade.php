<!DOCTYPE html>
<html lang="es">
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
    <title>Política de Privacidad - ByMelodia</title>
    <meta name="description" content="Política de privacidad de ByMelodia - Cómo manejamos tu información personal">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">

    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Theme Toggle - Top Right -->
            <div class="absolute top-4 right-4">
                <button
                    id="theme-toggle"
                    class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                    aria-label="Toggle theme"
                >
                    <span id="theme-icon">🌙</span>
                </button>
            </div>
            
            <!-- Centered Logo - Large -->
            <div class="text-center mb-6">
                <a href="{{ route('home') }}">
                    <img 
                        id="logo" 
                        src="/images/bymelodia_negro.png" 
                        alt="ByMelodia" 
                        class="h-20 md:h-24 lg:h-28 w-auto mx-auto transition-all duration-300 hover:scale-105"
                    >
                </a>
            </div>
            
            <!-- Navigation Menu - Below Logo -->
            <nav class="flex flex-wrap justify-center items-center gap-4 md:gap-8">
                <a href="{{ route('home') }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                    Inicio
                </a>
                <a href="{{ route('blog.index') }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                    Blog
                </a>
                <a href="#" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                    Acerca de
                </a>
                
                @auth
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <a href="{{ route('admin.posts.index') }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                        Admin
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium">
                            Logout
                        </button>
                    </form>
                @else
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <a href="{{ route('login') }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                        Login
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Inicio</a></li>
                <li><span>/</span></li>
                <li class="text-gray-700 dark:text-gray-300">Política de Privacidad</li>
            </ol>
        </nav>

        <!-- AdSense Banner Top -->
        <x-adsense-banner slot="auto" class="mb-8" />

        <!-- Content -->
        <article class="prose prose-lg dark:prose-invert max-w-none">
            <div class="markdown-content space-y-6">
                {!! \Illuminate\Support\Str::markdown($content) !!}
            </div>
        </article>

        <!-- AdSense Banner Bottom -->
        <x-adsense-banner slot="auto" class="mt-8" />

    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 dark:bg-gray-800 mt-16">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    © {{ date('Y') }} ByMelodia. Todos los derechos reservados.
                </p>
                <div class="mt-4 flex justify-center space-x-6">
                    <a href="{{ route('privacy.policy') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm">
                        Política de Privacidad
                    </a>
                    <a href="{{ route('terms.service') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm">
                        Términos de Servicio
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- GDPR Consent -->
    <x-gdpr-consent />

</body>
</html>