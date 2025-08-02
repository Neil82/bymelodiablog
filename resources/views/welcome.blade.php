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
        
        <!-- Post ID for analytics (if applicable) -->
        <meta name="post-id" content="">
        
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
        <header class="bg-white dark:bg-gray-900 py-8">
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
                    <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-azul-intenso dark:hover:text-azul-claro transition-colors font-medium text-lg">
                        {{ __('ui.nav.about') }}
                    </a>
                </nav>
            </div>
        </header>

        <!-- Banner Principal -->
        @php
            $bannerImageDesktop = App\Models\SiteSetting::get('banner_image_desktop');
            $bannerImageMobile = App\Models\SiteSetting::get('banner_image_mobile');
            $bannerTitle = App\Models\SiteSetting::get('banner_title', __('ui.home.title'));
            $bannerSubtitle = App\Models\SiteSetting::get('banner_subtitle', __('ui.home.main_headline') . "\n\n" . __('ui.home.sub_headline') . "\n\n" . __('ui.home.description'));
            $bannerButtonText = App\Models\SiteSetting::get('banner_button_text', __('ui.home.explore_content'));
            $bannerButtonUrl = App\Models\SiteSetting::get('banner_button_url', '/blog');
        @endphp
        
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-azul-claro/20 via-white to-rosado-pastel/15 dark:from-azul-intenso/20 dark:via-gray-900 dark:to-azul-claro/10 py-20 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-30">
                <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
                            <path d="M 60 0 L 0 0 0 60" fill="none" stroke="#a7b9e9" stroke-width="1" opacity="0.3"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
            <!-- Floating elements -->
            <div class="absolute inset-0">
                <div class="absolute top-20 left-10 w-32 h-32 bg-azul-claro/40 rounded-full blur-2xl animate-pulse"></div>
                <div class="absolute bottom-20 right-20 w-40 h-40 bg-rosado-pastel/40 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute top-1/3 right-1/4 w-20 h-20 bg-verde-lima/50 rounded-full blur-xl animate-bounce" style="animation-delay: 2s;"></div>
            </div>
            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- Status Badge -->
                <div class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-verde-lima/20 to-azul-claro/20 border-2 border-verde-lima/30 backdrop-blur-md text-sm font-medium mb-8 shadow-xl">
                    <span class="w-3 h-3 bg-gradient-to-r from-verde-lima to-azul-claro rounded-full mr-3 animate-pulse shadow-md"></span>
                    <span class="text-azul-intenso dark:text-azul-claro font-semibold">{{ App\Models\SiteSetting::get('home_status_text', __('ui.home.new_content_daily')) }}</span>
                </div>
                
                <!-- Content Cards -->
                <div class="space-y-6 mb-12">
                    <!-- Card 1 -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 md:p-8 shadow-xl border border-azul-claro/30 transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-3xl mr-3">🎵</span>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white text-center">
                                Descubre la cultura juvenil que está definiendo el futuro
                            </h3>
                        </div>
                    </div>
                    
                    <!-- Card 2 -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 md:p-8 shadow-xl border border-rosado-pastel/30 transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-3xl mr-3">✨</span>
                            <h3 class="text-lg md:text-xl font-semibold text-gray-700 dark:text-gray-200 text-center">
                                Tendencias, música, lifestyle y todo lo que mueve a la <strong>Generación Z</strong>
                            </h3>
                        </div>
                    </div>
                    
                    <!-- Card 3 -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 md:p-8 shadow-xl border border-verde-lima/30 transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-3xl mr-3">🚀</span>
                            <h3 class="text-lg md:text-xl font-semibold text-gray-700 dark:text-gray-200 text-center">
                                Contenido fresco, auténtico y siempre actualizado para mantenerte <strong>al día</strong>
                            </h3>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ $bannerButtonUrl }}" class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-azul-intenso to-azul-intenso/90 hover:from-azul-intenso/90 hover:to-azul-intenso text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <span>{{ $bannerButtonText }}</span>
                        <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="#latest" class="group inline-flex items-center px-8 py-4 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-semibold rounded-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-lg">
                        <span>{{ __('ui.home.latest_posts') }}</span>
                        <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- AdSense Banner -->
        <div class="bg-gradient-to-r from-azul-claro/10 via-gray-50 to-rosado-pastel/10 dark:from-azul-intenso/20 dark:via-gray-800 dark:to-azul-claro/20 py-8 border-y border-azul-claro/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-adsense-banner slot="auto" format="horizontal" class="text-center" />
            </div>
        </div>

        <!-- Main Content -->
        <main class="py-16 bg-gradient-to-b from-gray-50 via-white to-azul-claro/5 dark:from-gray-800 dark:via-gray-900 dark:to-azul-intenso/10 relative">
            <!-- Background decoration -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 left-1/4 w-64 h-64 bg-rosado-pastel rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-verde-lima rounded-full blur-3xl"></div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Posts Recientes -->
                <section id="latest" class="mb-20">
                    <div class="relative text-center mb-16">
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-8 shadow-2xl border border-azul-claro/30">
                            <h2 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-azul-intenso via-azul-claro to-rosado-pastel bg-clip-text text-transparent mb-4">
                                {{ App\Models\SiteSetting::get('home_main_title', __('ui.home.fresh_content')) }}
                            </h2>
                            <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                                {{ App\Models\SiteSetting::get('home_main_subtitle', __('ui.home.fresh_content_desc')) }}
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
                        <a href="{{ route('blog.index') }}" class="inline-flex items-center px-8 py-4 bg-brand-gradient-alt hover:opacity-90 text-white font-bold rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
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

        <!-- Analytics Tracking -->
        <script src="{{ asset('js/analytics-tracker.js') }}"></script>

    </body>
</html>