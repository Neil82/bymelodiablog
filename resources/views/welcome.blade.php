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

        <!-- SEO Meta Tags -->
        <x-seo-meta />
        
        <!-- Post ID for analytics (not applicable for home page) -->
        
        <!-- Language Selector -->
        <div class="absolute top-4 left-4">
            @include('components.language-selector')
        </div>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Google Search Console Verification -->
        @php $googleSearchConsole = App\Models\SiteSetting::get('google_search_console'); @endphp
        @if($googleSearchConsole)
        <meta name="google-site-verification" content="{{ $googleSearchConsole }}">
        @endif

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Google Analytics -->
        <x-google-analytics />
    </head>
    <body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
        
        <!-- AdSense Auto Ads -->
        <x-adsense-auto />
        
        <!-- Clean Header -->
        <header class="absolute top-0 left-0 right-0 z-10 py-8">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <div class="text-center mb-6">
                    <img 
                        id="logo" 
                        src="/images/bymelodia_negro.png" 
                        alt="ByMelodia" 
                        class="h-24 md:h-32 lg:h-40 w-auto mx-auto transition-all duration-300 hover:scale-105"
                    >
                </div>
                
                <!-- Clean Navigation -->
                <nav class="flex justify-center items-center gap-12">
                    <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-azul-intenso dark:hover:text-azul-claro transition-colors font-medium text-lg">
                        {{ __('ui.nav.home') }}
                    </a>
                    <a href="{{ route('blog.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-azul-intenso dark:hover:text-azul-claro transition-colors font-medium text-lg">
                        {{ __('ui.nav.blog') }}
                    </a>
                    <a href="{{ route('about') }}" class="text-gray-700 dark:text-gray-300 hover:text-azul-intenso dark:hover:text-azul-claro transition-colors font-medium text-lg">
                        {{ __('ui.nav.about') }}
                    </a>
                </nav>
            </div>
        </header>

        <!-- Nueva Versión Welcome Section -->
        <section class="relative bg-gradient-to-br from-pink-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen flex items-center overflow-hidden pt-32 md:pt-40">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <!-- Left Side - Text Content -->
                    <div class="text-left space-y-6">
                        <div class="space-y-4">
                            <p class="text-xl md:text-2xl text-gray-700 dark:text-gray-300 leading-relaxed">
                                ¿Ambiciosa pero siempre hay algo que te 
                                detiene de ser tu mejor versión?
                            </p>
                            <p class="text-xl md:text-2xl text-gray-700 dark:text-gray-300 leading-relaxed">
                                <strong>Todas tenemos una espina</strong> que nos 
                                detiene. Aquí descubrirás cuál es la tuya... 
                                y cómo transformarla en tu mayor poder
                            </p>
                        </div>
                        
                        <div class="pt-6">
                            <h1 class="text-6xl md:text-7xl leading-none">
                                <span class="font-serif text-gray-800 dark:text-gray-200">Bienvenida a tu</span>
                                <br>
                                <span class="font-libre text-pink-500" style="font-size: calc(1em + 2px);">Nueva Versión</span>
                            </h1>
                        </div>
                        
                        <!-- Decorative elements -->
                        <div class="flex items-center space-x-4 pt-4">
                            <div class="flex space-x-2">
                                <span class="text-2xl">💫</span>
                                <span class="text-yellow-400 text-xl">✨</span>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center space-x-2">
                                <span>mind</span>
                                <span>body</span>
                                <span>soul</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Side - Video -->
                    <div class="relative order-1 lg:order-2">
                        <div class="relative w-full max-w-md mx-auto rounded-2xl shadow-xl overflow-hidden">
                            <video 
                                class="w-full h-auto rounded-2xl" 
                                autoplay 
                                muted 
                                loop 
                                playsinline
                                poster="/images/home_1.png"
                            >
                                <source src="/videos/0806.mp4" type="video/mp4">
                            </video>
                            <!-- Decorative elements around video -->
                            <div class="absolute -top-4 -right-4 w-8 h-8 bg-pink-400 rounded-full opacity-60"></div>
                            <div class="absolute -bottom-4 -left-4 w-6 h-6 bg-green-400 rounded-full opacity-60"></div>
                            <div class="absolute top-1/2 -left-6 w-4 h-4 bg-yellow-400 rounded-full opacity-40"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Character Types Section -->
        <section class="relative bg-white dark:bg-gray-900 py-16 md:py-20 overflow-hidden">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl text-gray-900 dark:text-white mb-4">
                        ¿En cuál de estas chicas
                    </h2>
                    <h3 class="text-4xl md:text-5xl lg:text-6xl text-blue-600 mb-8">
                        te reconoces hoy?
                    </h3>
                </div>

                <!-- Three Character Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                    <!-- La Reina de la Procrastinación -->
                    <div class="bg-gradient-to-b from-green-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-3xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="relative mb-6">
                            <img src="/images/home/1.png" alt="La Reina de la Procrastinación" class="w-48 h-48 mx-auto object-contain">
                            <!-- Decorative bow -->
                            <div class="absolute -top-2 -right-2 w-16 h-16">
                                <svg viewBox="0 0 60 60" class="w-full h-full text-green-300 opacity-70">
                                    <path d="M30 10C40 15 50 20 55 30C50 40 40 45 30 50C20 45 10 40 5 30C10 20 20 15 30 10Z" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h4 class="text-xl md:text-2xl text-blue-600 mb-4">
                            La Reina de la<br>Procrastinación
                        </h4>
                        
                        <p class="text-lg text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
                            Siempre dices "mañana empiezo", pero nunca lo haces. 
                            Te llena de pendientes y te pierdes en TikTok.
                        </p>
                        
                        <div class="text-left">
                            <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                Lo que necesitas:
                            </p>
                            <p class="text-lg text-gray-700 dark:text-gray-300">
                                disciplina suave y rutinas que la motiven.
                            </p>
                        </div>
                    </div>

                    <!-- La Chica de los "Y si..." -->
                    <div class="bg-gradient-to-b from-pink-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-3xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="relative mb-6">
                            <img src="/images/home/2.png" alt="La Chica de los Y si..." class="w-48 h-48 mx-auto object-contain">
                            <!-- Decorative bow -->
                            <div class="absolute -top-2 -right-2 w-16 h-16">
                                <svg viewBox="0 0 60 60" class="w-full h-full text-pink-300 opacity-70">
                                    <path d="M30 10C40 15 50 20 55 30C50 40 40 45 30 50C20 45 10 40 5 30C10 20 20 15 30 10Z" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h4 class="text-xl md:text-2xl text-blue-600 mb-4">
                            La Chica de los "Y si..."
                        </h4>
                        
                        <p class="text-lg text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
                            Tiene muchas metas por cumplir, conoces su potencial, 
                            pero el miedo y las dudas la frenan: "¿y si fracaso?".
                        </p>
                        
                        <div class="text-left">
                            <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                Lo que necesitas:
                            </p>
                            <p class="text-lg text-gray-700 dark:text-gray-300">
                                confianza creativa y atreverte a brillar.
                            </p>
                        </div>
                    </div>

                    <!-- La Soñadora Caótica -->
                    <div class="bg-gradient-to-b from-blue-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-3xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="relative mb-6">
                            <img src="/images/home/3.png" alt="La Soñadora Caótica" class="w-48 h-48 mx-auto object-contain">
                            <!-- Decorative bow -->
                            <div class="absolute -top-2 -right-2 w-16 h-16">
                                <svg viewBox="0 0 60 60" class="w-full h-full text-blue-300 opacity-70">
                                    <path d="M30 10C40 15 50 20 55 30C50 40 40 45 30 50C20 45 10 40 5 30C10 20 20 15 30 10Z" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h4 class="text-xl md:text-2xl text-blue-600 mb-4">
                            La Soñadora Caótica
                        </h4>
                        
                        <p class="text-lg text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
                            Te apasiona todo, pero vives en desorden y te quedas sin energía.
                        </p>
                        
                        <div class="text-left">
                            <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                Lo que necesitas:
                            </p>
                            <p class="text-lg text-gray-700 dark:text-gray-300">
                                claridad y orden emocional para avanzar en tus metas.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bottom attribution -->
                <div class="text-center mt-12">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Imágenes de @nikaniki
                    </p>
                </div>
            </div>
        </section>

        <!-- Content Types Section -->
        <section class="relative bg-gradient-to-b from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 py-16 md:py-20 overflow-hidden">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-left mb-16">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl text-gray-900 dark:text-white mb-1">
                        Esto es lo que
                    </h2>
                    <h3 class="text-4xl md:text-5xl lg:text-6xl text-red-500 mb-8">
                        te va a encantar
                    </h3>
                </div>

                <!-- Four Content Type Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6">
                    <!-- Rutinas que inspiran -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="mb-6">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-4 mb-4">
                                <img src="/images/home/17.png" alt="Rutinas que inspiran" class="w-full h-32 object-cover rounded-lg">
                            </div>
                        </div>
                        
                        <h4 class="text-xl md:text-2xl font-bold text-red-500 mb-4">
                            Rutinas que<br>inspiran
                        </h4>
                        
                        <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                            Diseñadas para que vuelvas a ti, sin presión pero con intención.
                        </p>
                    </div>

                    <!-- Arte con propósito -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="mb-6">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-4 mb-4">
                                <img src="/images/home/18.png" alt="Arte con propósito" class="w-full h-32 object-cover rounded-lg">
                            </div>
                        </div>
                        
                        <h4 class="text-xl md:text-2xl font-bold text-red-500 mb-4">
                            Arte con<br>propósito
                        </h4>
                        
                        <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                            Creatividad aplicada a tu vida diaria para expresarte y crecer.
                        </p>
                    </div>

                    <!-- Tips de estudio y productividad -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="mb-6">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-4 mb-4">
                                <img src="/images/home/19.png" alt="Tips de estudio y productividad" class="w-full h-32 object-cover rounded-lg">
                            </div>
                        </div>
                        
                        <h4 class="text-xl md:text-2xl font-bold text-red-500 mb-4">
                            Tips de estudio y<br>productividad
                        </h4>
                        
                        <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                            Métodos, hacks y recursos y apps con IA que hacen tu aprendizaje más ligero y efectivo.
                        </p>
                    </div>

                    <!-- Orden emocional & bienestar -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="mb-6">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-4 mb-4">
                                <img src="/images/home/20.png" alt="Orden emocional & bienestar" class="w-full h-32 object-cover rounded-lg">
                            </div>
                        </div>
                        
                        <h4 class="text-xl md:text-2xl font-bold text-red-500 mb-4">
                            Orden emocional<br>& bienestar
                        </h4>
                        
                        <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed">
                            Herramientas para soltar el caos y encontrar claridad y calma.
                        </p>
                    </div>
                </div>
            </div>
        </section>


        <!-- Main Content -->
        <main class="py-16 bg-gradient-to-b from-gray-50 via-white to-azul-claro/5 dark:from-gray-800 dark:via-gray-900 dark:to-azul-intenso/10 relative">
            <!-- Background decoration -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 left-1/4 w-64 h-64 bg-rosado-pastel rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-verde-lima rounded-full blur-3xl"></div>
            </div>
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Posts Recientes -->
                <section id="latest" class="mb-20">
                    <div class="relative text-center mb-16">
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-8 shadow-2xl border border-azul-claro/30">
                            <h2 class="text-4xl md:text-5xl mb-4">
                                <span class="text-blue-400">Lo nuevo</span> <span class="text-gray-900 dark:text-white">en el blog</span>
                            </h2>
                            <p class="text-lg text-gray-600 dark:text-gray-300 max-w-4xl mx-auto">
                                Cada semana comparto plantillas personalizadas, retos para tu glow up, mis métodos de estudio para la universidad y muchas cosas más
                            </p>
                        </div>
                    </div>
                    
                    @php
                        // Get featured posts first, if we have them
                        $featuredPosts = App\Models\FeaturedPost::with(['post.category', 'post.user', 'post.approvedComments'])
                                          ->orderBy('order')
                                          ->get()
                                          ->pluck('post')
                                          ->filter(function($post) {
                                              return $post && $post->isPublished();
                                          });
                        
                        // If we don't have 6 featured posts, fill with recent posts
                        if ($featuredPosts->count() < 6) {
                            $excludeIds = $featuredPosts->pluck('id')->toArray();
                            $additionalPosts = App\Models\Post::published()
                                             ->with(['category', 'user', 'approvedComments'])
                                             ->whereNotIn('id', $excludeIds)
                                             ->latest('published_at')
                                             ->limit(6 - $featuredPosts->count())
                                             ->get();
                            
                            $recentPosts = $featuredPosts->concat($additionalPosts)->take(6);
                        } else {
                            $recentPosts = $featuredPosts->take(6);
                        }
                        
                        $gradients = [
                            'from-azul-intenso via-azul-claro to-rosado-pastel',
                            'from-rosado-pastel via-azul-claro to-verde-lima', 
                            'from-verde-lima via-azul-claro to-azul-intenso',
                            'from-azul-claro via-rosado-pastel to-verde-lima',
                            'from-azul-intenso via-verde-lima to-rosado-pastel',
                            'from-rosado-pastel via-verde-lima to-azul-intenso'
                        ];
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($recentPosts as $index => $post)
                            <div class="@if($index === 0) md:col-span-2 lg:col-span-1 @endif group">
                                <article class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                                    <div class="relative">
                                        @if($post->featured_image)
                                            <div class="h-{{ $index === 0 ? '64' : '48' }} bg-cover bg-center relative overflow-hidden"
                                                 style="background-image: url('{{ asset('storage/' . $post->featured_image) }}')">
                                                <div class="absolute inset-0 bg-black/30"></div>
                                                <div class="absolute top-4 left-4">
                                                    <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-bold rounded-full"
                                                          style="color: {{ $post->category->color }}">
                                                        {{ $post->category->name }}
                                                    </span>
                                                </div>
                                                @if($index === 0)
                                                    <div class="absolute bottom-4 right-4">
                                                        <span class="px-3 py-1 bg-black/50 backdrop-blur-sm text-white text-xs rounded-full">
                                                            {{ __('ui.general.featured') }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="h-{{ $index === 0 ? '64' : '48' }} bg-gradient-to-br {{ $gradients[$index % count($gradients)] }} relative overflow-hidden">
                                                <div class="absolute inset-0 bg-black/20"></div>
                                                <div class="absolute top-4 left-4">
                                                    <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-bold rounded-full"
                                                          style="color: {{ $post->category->color }}">
                                                        {{ $post->category->name }}
                                                    </span>
                                                </div>
                                                @if($index === 0)
                                                    <div class="absolute bottom-4 right-4">
                                                        <span class="px-3 py-1 bg-black/50 backdrop-blur-sm text-white text-xs rounded-full">
                                                            {{ __('ui.general.featured') }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-6">
                                        <h3 class="text-{{ $index === 0 ? '2xl' : 'xl' }} font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-azul-intenso dark:group-hover:text-azul-claro transition-colors">
                                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-{{ $index === 0 ? '3' : '2' }} {{ $index === 0 ? '' : 'text-sm' }}">
                                            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                        </p>
                                        <div class="flex items-center justify-between {{ $index === 0 ? '' : 'text-sm' }} text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-brand-gradient rounded-full flex items-center justify-center">
                                                    <span class="text-white text-xs font-bold">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                                                </div>
                                                <span>{{ $post->published_at->diffForHumans() }}</span>
                                            </div>
                                            @if($post->approvedComments->count() == 0)
                                                <span>{{ __('ui.blog.no_comments') }}</span>
                                            @else
                                                <span>{{ $post->approvedComments->count() }} {{ __('ui.blog.comments') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- AdSense Banner in content -->
                    <div class="text-center my-12">
                        <x-adsense-banner slot="auto" class="mb-8" />
                    </div>
                    
                    <div class="text-center mt-12">
                        <a href="{{ route('blog.index') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-bold rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <span>{{ __('ui.blog.view_all_content') }}</span>
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </section>

            </div>
        </main>

        <!-- Footer -->
        <x-footer />

        <!-- GDPR Consent -->
        <x-gdpr-consent />


    </body>
</html>