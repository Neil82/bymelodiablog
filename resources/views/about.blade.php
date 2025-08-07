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
    
    <!-- SEO Meta Tags -->
    <x-seo-meta 
        :title="__('ui.nav.about')"
        :description="App\Models\SiteSetting::get('about_mission_description', 'Conoce más sobre ByMelodia, nuestra misión y valores en la cultura juvenil contemporánea.')"
        :url="route('about')"
        type="website"
    />
    
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logo_bymelodia_blanco.png">
    <link rel="apple-touch-icon" href="/images/logo_bymelodia_blanco.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">

    <!-- Melody Introduction Section with Header -->
    <section class="relative bg-gradient-to-br from-pink-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen flex flex-col overflow-hidden">
        <!-- Clean Header -->
        <header class="bg-transparent py-8 border-b border-gray-200/30 dark:border-gray-700/30">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Theme Toggle - Top Right -->
                <div class="absolute top-6 right-6">
                    <button
                        id="theme-toggle"
                        class="p-3 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                        aria-label="Toggle theme"
                    >
                        <span id="theme-icon">🌙</span>
                    </button>
                </div>
                
                <!-- Language Selector -->
                <div class="absolute top-6 left-6">
                    @include('components.language-selector')
                </div>
                
                <!-- Centered Logo - Large -->
                <div class="text-center mb-12">
                    <a href="{{ route('home') }}">
                        <img 
                            id="logo" 
                            src="/images/bymelodia_negro.png" 
                            alt="ByMelodia" 
                            class="h-24 md:h-32 lg:h-40 w-auto mx-auto transition-all duration-300 hover:scale-105"
                        >
                    </a>
                </div>
                
                <!-- Clean Navigation -->
                <nav class="flex justify-center items-center gap-12">
                    <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-azul-intenso dark:hover:text-azul-claro transition-colors font-medium text-lg">
                        {{ __('ui.nav.home') }}
                    </a>
                    <a href="{{ route('blog.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-azul-intenso dark:hover:text-azul-claro transition-colors font-medium text-lg">
                        {{ __('ui.nav.blog') }}
                    </a>
                    <a href="{{ route('about') }}" class="text-azul-intenso dark:text-azul-claro font-semibold text-lg border-b-2 border-azul-intenso dark:border-azul-claro pb-1">
                        {{ __('ui.nav.about') }}
                    </a>
                </nav>
            </div>
        </header>

        <!-- Content Section -->
        <div class="flex-1 relative flex items-center">
            <!-- Decorative elements background -->
            <div class="absolute inset-0 opacity-30">
                <div class="absolute top-10 left-10 w-20 h-20 bg-pink-300 rounded-full blur-xl animate-pulse"></div>
                <div class="absolute top-1/3 right-20 w-16 h-16 bg-blue-300 rounded-full blur-lg animate-bounce" style="animation-delay: 1s;"></div>
                <div class="absolute bottom-20 left-1/4 w-24 h-24 bg-green-300 rounded-full blur-2xl animate-pulse" style="animation-delay: 2s;"></div>
                <div class="absolute top-20 right-1/3 w-12 h-12 bg-yellow-300 rounded-full blur-md animate-bounce" style="animation-delay: 3s;"></div>
            </div>
            
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Side - Video -->
                <div class="relative order-2 lg:order-1">
                    <div class="w-full max-w-md mx-auto">
                        <video 
                            class="w-full h-auto rounded-2xl shadow-xl" 
                            autoplay 
                            muted 
                            loop 
                            playsinline
                        >
                            <source src="/videos/0807.mp4" type="video/mp4">
                            Tu navegador no soporta videos HTML5.
                        </video>
                    </div>
                </div>
                
                <!-- Right Side - Text Content -->
                <div class="text-left space-y-6 order-1 lg:order-2">
                    <div class="space-y-4">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight">
                            Hello girls!
                        </h2>
                        
                        <div class="space-y-4 text-gray-700 dark:text-gray-300 text-lg leading-relaxed">
                            <p>
                                Soy Melody, y comencé este rincón porque descubrí que el arte y la organización podían ser mucho más que un simple hobbie: <strong class="text-gray-900 dark:text-white">podían convertirse en un estilo de vida.</strong>
                            </p>
                            
                            <p>
                                Aquí comparto todo lo que me ayudó a reconectar conmigo misma y poder cambiar mi realidad.
                            </p>
                            
                            <p>
                                Quiero que cada una de ustedes sienta que <strong class="text-gray-900 dark:text-white">también pueden diseñar una vida auténtica</strong> ♡
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Mi Historia Timeline Section -->
    <section class="relative bg-gradient-to-br from-gray-50 via-gray-100 to-blue-50 dark:bg-gray-900 min-h-screen flex items-center overflow-hidden">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Timeline Layout -->
            <div class="flex flex-col lg:flex-row gap-12 items-start">
                <!-- Left side - Text content -->
                <div class="w-full lg:w-1/2">
                    <!-- Section Title -->
                    <div class="mb-12">
                        <h2 class="text-4xl md:text-5xl font-bold leading-tight">
                            <span class="text-gray-900 dark:text-white">Mi </span>
                            <span class="text-green-400">historia</span>
                        </h2>
                    </div>
                    
                    <!-- Timeline Items -->
                    <div class="space-y-8">
                        <!-- 2020 -->
                        <div class="timeline-item">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">2020</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                                        Empecé mi cuenta de "studygram" publicando apuntes bonitos, mi bullet journal y mucho, MUCHO lettering.
                                    </p>
                                    <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed mt-3">
                                        Participé en concursos de arte y quedé entre los primeros puestos (pude colaborar con una marca internacional)
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 2023 -->
                        <div class="timeline-item">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">2023</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                                        Tomaba cursos digitales en mi tiempo libre y logré certificarme en: Marketing, Diseño Gráfico, Comunicación y Liderazgo, Branding, entre otros.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 2024 -->
                        <div class="timeline-item">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">2024</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                                        Ingresé a una de las mejores universidades privadas de mi país y conseguí una beca de honor por mi promedio alto
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 2025 -->
                        <div class="timeline-item">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">2025</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                                        Decidí comenzar con mi marca personal para compartir todo lo que aprendí estos años (y sigo aprendiendo)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right side - Images in 2x2 grid -->
                <div class="w-full lg:w-1/2">
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Top row: 13.png and 14.png -->
                        <div>
                            <picture>
                                <source srcset="/images/acercade/13.webp" type="image/webp">
                                <img src="/images/acercade/13.png" alt="2020 - Studygram" class="w-full h-auto">
                            </picture>
                        </div>
                        <div>
                            <picture>
                                <source srcset="/images/acercade/14.webp" type="image/webp">
                                <img src="/images/acercade/14.png" alt="2023 - Cursos digitales" class="w-full h-auto">
                            </picture>
                        </div>
                        
                        <!-- Bottom row: 15.png and 16.png -->
                        <div>
                            <picture>
                                <source srcset="/images/acercade/15.webp" type="image/webp">
                                <img src="/images/acercade/15.png" alt="2024 - Universidad" class="w-full h-auto">
                            </picture>
                        </div>
                        <div>
                            <picture>
                                <source srcset="/images/acercade/16.webp" type="image/webp">
                                <img src="/images/acercade/16.png" alt="2025 - Marca personal" class="w-full h-auto">
                            </picture>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mi Felicidad Section -->
    <section class="relative bg-white dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 min-h-screen flex items-center overflow-hidden">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Title -->
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold leading-tight">
                    <span class="text-blue-400">Mi felicidad</span>
                    <span class="text-gray-900 dark:text-white"> en pequeñas cosas</span>
                </h2>
            </div>
            
            <!-- Images Grid Layout -->
            <div class="space-y-8">
                <!-- Row 1 - Centered 4 images -->
                <div class="flex justify-center">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-10 max-w-5xl">
                        <div class="relative shadow-lg transform hover:scale-105 transition-all duration-300 rounded-xl overflow-hidden">
                            <picture>
                                <source srcset="/images/acercade/4.webp" type="image/webp">
                                <img src="/images/acercade/4.png" alt="Salidas a la cafetería" class="w-full h-64 object-cover">
                            </picture>
                        </div>
                        
                        <div class="relative shadow-lg transform hover:scale-105 transition-all duration-300 rounded-xl overflow-hidden">
                            <picture>
                                <source srcset="/images/acercade/5.webp" type="image/webp">
                                <img src="/images/acercade/5.png" alt="La playita" class="w-full h-64 object-cover">
                            </picture>
                        </div>
                        
                        <div class="relative shadow-lg transform hover:scale-105 transition-all duration-300 rounded-xl overflow-hidden">
                            <picture>
                                <source srcset="/images/acercade/6.webp" type="image/webp">
                                <img src="/images/acercade/6.jpg" alt="Los makiss" class="w-full h-64 object-cover">
                            </picture>
                        </div>
                        
                        <div class="relative shadow-lg transform hover:scale-105 transition-all duration-300 rounded-xl overflow-hidden">
                            <picture>
                                <source srcset="/images/acercade/7.webp" type="image/webp">
                                <img src="/images/acercade/7.png" alt="Bailar marinera" class="w-full h-64 object-cover">
                            </picture>
                        </div>
                    </div>
                </div>
                
                <!-- Row 2 - Centered 5 images -->
                <div class="flex justify-center">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-8 md:gap-10 max-w-6xl">
                        <div class="relative shadow-lg transform hover:scale-105 transition-all duration-300 rounded-xl overflow-hidden">
                            <picture>
                                <source srcset="/images/acercade/8.webp" type="image/webp">
                                <img src="/images/acercade/8.jpg" alt="Noche de pelis" class="w-full h-64 object-cover">
                            </picture>
                        </div>
                        
                        <div class="relative shadow-lg transform hover:scale-105 transition-all duration-300 rounded-xl overflow-hidden">
                            <picture>
                                <source srcset="/images/acercade/9.webp" type="image/webp">
                                <img src="/images/acercade/9.png" alt="Museos" class="w-full h-64 object-cover">
                            </picture>
                        </div>
                        
                        <div class="relative shadow-lg transform hover:scale-105 transition-all duration-300 rounded-xl overflow-hidden">
                            <picture>
                                <source srcset="/images/acercade/10.webp" type="image/webp">
                                <img src="/images/acercade/10.png" alt="Sesión de fotos" class="w-full h-64 object-cover">
                            </picture>
                        </div>
                        
                        <div class="relative shadow-lg transform hover:scale-105 transition-all duration-300 rounded-xl overflow-hidden">
                            <picture>
                                <source srcset="/images/acercade/11.webp" type="image/webp">
                                <img src="/images/acercade/11.png" alt="Días soleados" class="w-full h-64 object-cover">
                            </picture>
                        </div>
                        
                        <div class="relative shadow-lg transform hover:scale-105 transition-all duration-300 rounded-xl overflow-hidden">
                            <picture>
                                <source srcset="/images/acercade/12.webp" type="image/webp">
                                <img src="/images/acercade/12.png" alt="Dibujar + música" class="w-full h-64 object-cover">
                            </picture>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="relative bg-gradient-to-br from-pink-50 via-purple-50 to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen flex items-center overflow-hidden">
        <!-- Background Images for Desktop/Tablet -->
        <div class="hidden md:block">
            <!-- Left Image -->
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-64 lg:w-80 h-96 lg:h-[500px]">
                <picture>
                    <source srcset="/images/acercade/left.webp" type="image/webp">
                    <img src="/images/acercade/left.jpg" alt="Inspiración izquierda" class="w-full h-full object-cover rounded-r-3xl shadow-2xl">
                </picture>
            </div>
            
            <!-- Right Image -->
            <div class="absolute right-0 top-1/2 transform -translate-y-1/2 w-64 lg:w-80 h-96 lg:h-[500px]">
                <picture>
                    <source srcset="/images/acercade/right.webp" type="image/webp">
                    <img src="/images/acercade/right.jpg" alt="Inspiración derecha" class="w-full h-full object-cover rounded-l-3xl shadow-2xl">
                </picture>
            </div>
        </div>
        
        <!-- Central Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <!-- Main Message -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 md:p-12 shadow-2xl border border-pink-200/50 dark:border-gray-700/50">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight mb-8">
                    <span class="text-gray-900 dark:text-white">Todos somos</span><br>
                    <span class="text-gray-900 dark:text-white">artistas, solo necesitamos</span><br>
                    <span class="text-gray-900 dark:text-white">recordarlo con propósito.</span>
                </h2>
                
                <p class="text-xl md:text-2xl text-gray-700 dark:text-gray-300 leading-relaxed mb-10 max-w-3xl mx-auto">
                    Este espacio es la prueba de que tú también puedes diseñar tu vida con arte y claridad
                </p>
                
                <!-- Call to Action Button -->
                <a href="{{ route('blog.index') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-pink-400 to-purple-500 hover:from-pink-500 hover:to-purple-600 text-white font-semibold text-lg rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                    Quiero empezar ya!
                    <svg class="ml-3 w-6 h-6 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
            
            <!-- Mobile Images - Below text for mobile -->
            <div class="md:hidden mt-12 grid grid-cols-2 gap-4">
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <picture>
                        <source srcset="/images/acercade/left.webp" type="image/webp">
                        <img src="/images/acercade/left.jpg" alt="Inspiración" class="w-full h-48 object-cover">
                    </picture>
                </div>
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <picture>
                        <source srcset="/images/acercade/right.webp" type="image/webp">
                        <img src="/images/acercade/right.jpg" alt="Inspiración" class="w-full h-48 object-cover">
                    </picture>
                </div>
            </div>
        </div>
        
        <!-- Decorative Elements -->
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute top-20 left-1/4 w-32 h-32 bg-pink-300 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-purple-300 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/3 right-1/3 w-24 h-24 bg-blue-300 rounded-full blur-xl animate-bounce" style="animation-delay: 2s;"></div>
        </div>
    </section>

    <!-- Footer -->
    <x-footer />

    <!-- Theme Toggle Script -->
    <script src="{{ asset('js/theme.js') }}"></script>

</body>
</html>