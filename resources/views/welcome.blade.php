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
        
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
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
                    <img 
                        id="logo" 
                        src="/images/bymelodia_negro.png" 
                        alt="ByMelodia" 
                        class="h-20 md:h-24 lg:h-28 w-auto mx-auto transition-all duration-300 hover:scale-105"
                    >
                </div>
                
                <!-- Navigation Menu - Below Logo -->
                <nav class="flex flex-wrap justify-center items-center gap-4 md:gap-8">
                    <a href="{{ route('home') }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                        {{ __('ui.nav.home') }}
                    </a>
                    <a href="{{ route('blog.index') }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                        {{ __('ui.nav.blog') }}
                    </a>
                    <a href="#" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                        {{ __('ui.nav.about') }}
                    </a>
                    
                    @auth
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <a href="{{ route('admin.posts.index') }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                            {{ __('ui.nav.admin') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium">
                                {{ __('ui.nav.logout') }}
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

        <!-- Banner Principal -->
        @php
            $bannerImageDesktop = App\Models\SiteSetting::get('banner_image_desktop');
            $bannerImageMobile = App\Models\SiteSetting::get('banner_image_mobile');
            $bannerTitle = App\Models\SiteSetting::get('banner_title', __('ui.home.title'));
            $bannerSubtitle = App\Models\SiteSetting::get('banner_subtitle', __('ui.home.main_headline') . "\n\n" . __('ui.home.sub_headline') . "\n\n" . __('ui.home.description'));
            $bannerButtonText = App\Models\SiteSetting::get('banner_button_text', __('ui.home.explore_content'));
            $bannerButtonUrl = App\Models\SiteSetting::get('banner_button_url', '/blog');
        @endphp
        
        <section class="relative overflow-hidden text-white">
            <!-- Animated Gradient Background -->
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900"></div>
                <div class="absolute inset-0 bg-gradient-to-tr from-pink-600/30 via-transparent to-cyan-600/30 animate-gradient-shift"></div>
            </div>
            
            <!-- Dynamic Background Images -->
            @if($bannerImageDesktop || $bannerImageMobile)
                <div class="absolute inset-0">
                    @if($bannerImageDesktop)
                        <div class="hidden md:block absolute inset-0 bg-cover bg-center parallax" 
                             style="background-image: url('{{ asset('storage/' . $bannerImageDesktop) }}');"></div>
                    @endif
                    @if($bannerImageMobile)
                        <div class="md:hidden absolute inset-0 bg-cover bg-center" 
                             style="background-image: url('{{ asset('storage/' . $bannerImageMobile) }}');"></div>
                    @endif
                    <div class="absolute inset-0 bg-black/40"></div>
                </div>
            @else
                <!-- Animated Background Pattern (fallback) -->
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                </div>
            @endif
            
            <!-- Animated Floating Elements -->
            <div class="absolute top-10 -left-10 w-40 h-40 bg-pink-500/40 rounded-full blur-3xl animate-float"></div>
            <div class="absolute top-20 -right-10 w-64 h-64 bg-cyan-500/30 rounded-full blur-3xl animate-float-delayed"></div>
            <div class="absolute -bottom-20 left-1/4 w-56 h-56 bg-yellow-500/30 rounded-full blur-3xl animate-float-slow"></div>
            <div class="absolute top-1/2 right-1/3 w-48 h-48 bg-purple-500/40 rounded-full blur-3xl animate-pulse"></div>
            
            <!-- Animated Particles -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="particle particle-1"></div>
                <div class="particle particle-2"></div>
                <div class="particle particle-3"></div>
                <div class="particle particle-4"></div>
                <div class="absolute top-1/3 left-1/2 w-2 h-2 bg-white rounded-full opacity-70 animate-ping"></div>
                <div class="absolute top-2/3 left-1/4 w-1 h-1 bg-white rounded-full opacity-50 animate-ping delay-500"></div>
                <div class="absolute top-1/2 left-3/4 w-2 h-2 bg-white rounded-full opacity-60 animate-ping delay-1000"></div>
            </div>
            
            <!-- Mesh Gradient Overlay -->
            <div class="absolute inset-0 opacity-30">
                <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ec4899;stop-opacity:0.3" />
                            <stop offset="50%" style="stop-color:#8b5cf6;stop-opacity:0.2" />
                            <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:0.3" />
                        </linearGradient>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grad1)" />
                </svg>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 text-center">
                <div class="space-y-6">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        {{ App\Models\SiteSetting::get('home_status_text', __('ui.home.new_content_daily')) }}
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
                        <span class="bg-gradient-to-r from-pink-400 via-purple-400 to-cyan-400 bg-clip-text text-transparent">
                            {{ $bannerTitle }}
                        </span>
                    </h1>
                    
                    <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto leading-relaxed font-light">
                        {!! nl2br(e($bannerSubtitle)) !!}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                        <a href="{{ $bannerButtonUrl }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-semibold rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <span>{{ $bannerButtonText }}</span>
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="#latest" class="inline-flex items-center px-8 py-4 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-semibold rounded-full transition-all duration-300 border border-white/20 hover:border-white/40">
                            <span>{{ __('ui.home.latest_posts') }}</span>
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- AdSense Banner after Header -->
        <div class="bg-gray-50 dark:bg-gray-900 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <x-adsense-banner slot="auto" format="horizontal" class="text-center" />
            </div>
        </div>

        <!-- Contenido Principal -->
        <main class="py-20 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Posts Recientes -->
                <section id="latest" class="mb-20">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6">
                            <span class="bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">
                                {{ App\Models\SiteSetting::get('home_main_title', __('ui.home.fresh_content')) }}
                            </span>
                        </h2>
                        <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                            {{ App\Models\SiteSetting::get('home_main_subtitle', __('ui.home.fresh_content_desc')) }}
                        </p>
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
                            'from-pink-400 via-purple-500 to-indigo-600',
                            'from-cyan-400 via-blue-500 to-purple-600', 
                            'from-orange-400 via-red-500 to-pink-600',
                            'from-green-400 via-teal-500 to-blue-600',
                            'from-yellow-400 via-orange-500 to-red-600',
                            'from-indigo-400 via-purple-500 to-pink-600'
                        ];
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($recentPosts as $index => $post)
                            <div class="@if($index === 0) md:col-span-2 lg:col-span-1 @endif group">
                                <article class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-2">
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
                                        <h3 class="text-{{ $index === 0 ? '2xl' : 'xl' }} font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-{{ $index === 0 ? '3' : '2' }} {{ $index === 0 ? '' : 'text-sm' }}">
                                            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                        </p>
                                        <div class="flex items-center justify-between {{ $index === 0 ? '' : 'text-sm' }} text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full flex items-center justify-center">
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

        <!-- Analytics Tracking -->
        <script src="{{ asset('js/analytics-tracker.js') }}"></script>

    </body>
</html>