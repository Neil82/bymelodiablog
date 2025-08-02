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

    <!-- Clean Header -->
    <header class="bg-white dark:bg-gray-900 py-8 border-b border-gray-200 dark:border-gray-700">
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

    <!-- Banner Section -->
    <section class="relative bg-gradient-to-br from-azul-claro/20 via-white to-rosado-pastel/15 dark:from-azul-intenso/20 dark:via-gray-900 dark:to-azul-claro/10 py-20 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="about-grid" width="60" height="60" patternUnits="userSpaceOnUse">
                        <path d="M 60 0 L 0 0 0 60" fill="none" stroke="#a7b9e9" stroke-width="1" opacity="0.3"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#about-grid)" />
            </svg>
        </div>
        
        <!-- Floating elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-32 h-32 bg-verde-lima/40 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute bottom-20 right-20 w-40 h-40 bg-rosado-pastel/40 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/3 right-1/4 w-20 h-20 bg-azul-claro/50 rounded-full blur-xl animate-bounce" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- About Badge -->
            <div class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-rosado-pastel/20 to-azul-claro/20 border-2 border-rosado-pastel/30 backdrop-blur-md text-sm font-medium mb-8 shadow-xl">
                <span class="w-3 h-3 bg-gradient-to-r from-rosado-pastel to-azul-claro rounded-full mr-3 animate-pulse shadow-md"></span>
                <span class="text-azul-intenso dark:text-azul-claro font-semibold">{{ __('ui.nav.about') }}</span>
            </div>
            
            <!-- Banner Image -->
            @php
                $aboutBannerDesktop = App\Models\SiteSetting::get('about_banner_image_desktop');
                $aboutBannerMobile = App\Models\SiteSetting::get('about_banner_image_mobile');
            @endphp
            
            @if($aboutBannerDesktop || $aboutBannerMobile)
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-xl border border-azul-claro/30 mb-8">
                    @if($aboutBannerDesktop && $aboutBannerMobile)
                        <!-- Responsive banner images -->
                        <img src="{{ asset('storage/' . $aboutBannerMobile) }}" alt="Acerca de ByMelodia" class="w-full h-64 object-cover rounded-2xl md:hidden">
                        <img src="{{ asset('storage/' . $aboutBannerDesktop) }}" alt="Acerca de ByMelodia" class="w-full h-80 object-cover rounded-2xl hidden md:block">
                    @elseif($aboutBannerDesktop)
                        <img src="{{ asset('storage/' . $aboutBannerDesktop) }}" alt="Acerca de ByMelodia" class="w-full h-64 md:h-80 object-cover rounded-2xl">
                    @else
                        <img src="{{ asset('storage/' . $aboutBannerMobile) }}" alt="Acerca de ByMelodia" class="w-full h-64 md:h-80 object-cover rounded-2xl">
                    @endif
                </div>
            @else
                <!-- Banner Placeholder -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-xl border border-azul-claro/30 mb-8">
                    <div class="w-full h-64 md:h-80 bg-gradient-to-br from-azul-claro/20 to-rosado-pastel/20 rounded-2xl flex items-center justify-center border-2 border-dashed border-azul-claro/30">
                        <div class="text-center">
                            <div class="text-4xl mb-4">🎨</div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Espacio para banner personalizable</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Configurable desde el panel de administración</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Main Title -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-xl border border-verde-lima/30">
                <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-azul-intenso via-azul-claro to-rosado-pastel bg-clip-text text-transparent mb-4">
                    Conoce ByMelodia
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 dark:text-gray-200 font-medium leading-relaxed">
                    Arte con propósito, cultura con sentido
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="py-16 bg-gradient-to-b from-gray-50 via-white to-azul-claro/5 dark:from-gray-800 dark:via-gray-900 dark:to-azul-intenso/10 relative">
        <!-- Background decoration -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 right-1/4 w-40 h-40 bg-rosado-pastel rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-1/4 w-60 h-60 bg-verde-lima rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Content Section -->
            <section class="mb-16">
                
                <!-- Mission Card -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 md:p-12 shadow-xl border border-azul-claro/30 mb-12">
                    <div class="flex items-center mb-6">
                        <span class="text-4xl mr-4">🎯</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">{{ App\Models\SiteSetting::get('about_mission_title', 'Nuestra Misión') }}</h2>
                    </div>
                    
                    <div class="prose prose-lg dark:prose-invert max-w-none">
                        <p class="text-lg md:text-xl text-gray-700 dark:text-gray-300 leading-relaxed">
                            {!! nl2br(e(App\Models\SiteSetting::get('about_mission_description', 'En ByMelodia, creemos que la música es el lenguaje universal que conecta corazones y mentes. Nuestra misión es descubrir, promover y compartir la música que define a una generación, creando un espacio donde los artistas emergentes y establecidos puedan encontrar su audiencia perfecta.'))) !!}
                        </p>
                    </div>
                </div>
                
                <!-- Values Section -->
                <div class="mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white text-center mb-8">{{ App\Models\SiteSetting::get('about_values_title', 'Nuestros Valores') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <!-- Value 1 -->
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 md:p-8 shadow-xl border border-rosado-pastel/30 transform hover:scale-105 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <span class="text-3xl mr-3">🎵</span>
                                <h3 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ App\Models\SiteSetting::get('about_value_1_title', 'Autenticidad') }}</h3>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ App\Models\SiteSetting::get('about_value_1_description', 'Promovemos música genuina que refleje la verdadera esencia de los artistas.') }}
                            </p>
                        </div>
                        
                        <!-- Value 2 -->
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 md:p-8 shadow-xl border border-verde-lima/30 transform hover:scale-105 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <span class="text-3xl mr-3">🌟</span>
                                <h3 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ App\Models\SiteSetting::get('about_value_2_title', 'Innovación') }}</h3>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ App\Models\SiteSetting::get('about_value_2_description', 'Utilizamos tecnología de vanguardia para crear experiencias musicales únicas.') }}
                            </p>
                        </div>
                        
                        <!-- Value 3 -->
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 md:p-8 shadow-xl border border-azul-claro/30 transform hover:scale-105 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <span class="text-3xl mr-3">🤝</span>
                                <h3 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ App\Models\SiteSetting::get('about_value_3_title', 'Comunidad') }}</h3>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ App\Models\SiteSetting::get('about_value_3_description', 'Construimos puentes entre artistas y audiencias, creando una comunidad vibrante.') }}
                            </p>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Contact Call to Action -->
                <div class="bg-gradient-to-r from-azul-intenso/10 via-azul-claro/10 to-rosado-pastel/10 dark:from-azul-intenso/20 dark:via-azul-claro/20 dark:to-rosado-pastel/20 backdrop-blur-md rounded-3xl p-8 md:p-12 shadow-xl border border-azul-claro/30 text-center">
                    <h2 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-azul-intenso via-azul-claro to-rosado-pastel bg-clip-text text-transparent mb-6">
                        {{ App\Models\SiteSetting::get('about_cta_title', '¿Listo para ser parte de nuestra historia?') }}
                    </h2>
                    <p class="text-lg md:text-xl text-gray-700 dark:text-gray-300 leading-relaxed mb-8 max-w-2xl mx-auto">
                        {{ App\Models\SiteSetting::get('about_cta_description', 'Únete a nuestra comunidad y descubre la música que está definiendo el futuro.') }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('blog.index') }}" class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-azul-intenso to-azul-intenso/90 hover:from-azul-intenso/90 hover:to-azul-intenso text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <span>{{ App\Models\SiteSetting::get('about_cta_button_text', 'Explorar Música') }}</span>
                            <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        
                        @if($contact_email = \App\Models\SiteSetting::get('contact_email'))
                        <a href="mailto:{{ $contact_email }}" class="group inline-flex items-center px-8 py-4 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-semibold rounded-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-lg">
                            <span>Contactar</span>
                            <svg class="ml-2 w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
                
            </section>
        </div>
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Theme Toggle Script -->
    <script src="{{ asset('js/theme.js') }}"></script>

</body>
</html>