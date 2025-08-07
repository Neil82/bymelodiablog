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
        :title="__('ui.nav.blog')"
        :description="'Descubre los últimos artículos sobre cultura juvenil, música y tendencias en el blog de ByMelodia.'"
        :url="route('blog.index')"
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
            <!-- Language Selector -->
            <div class="absolute top-6 left-6">
                @include('components.language-selector')
            </div>
            
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
                <a href="{{ route('blog.index') }}" class="text-azul-intenso dark:text-azul-claro font-semibold text-lg border-b-2 border-azul-intenso dark:border-azul-claro pb-1">
                    {{ __('ui.nav.blog') }}
                </a>
                <a href="{{ route('about') }}" class="text-gray-700 dark:text-gray-300 hover:text-azul-intenso dark:hover:text-azul-claro transition-colors font-medium text-lg">
                    {{ __('ui.nav.about') }}
                </a>
            </nav>
        </div>
    </header>

    <!-- Page Header -->
    <div class="relative bg-gradient-to-br from-azul-claro/20 via-white to-rosado-pastel/15 dark:from-azul-intenso/20 dark:via-gray-900 dark:to-azul-claro/10 py-16 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="blog-grid" width="60" height="60" patternUnits="userSpaceOnUse">
                        <path d="M 60 0 L 0 0 0 60" fill="none" stroke="#a7b9e9" stroke-width="1" opacity="0.3"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#blog-grid)" />
            </svg>
        </div>
        
        <!-- Floating elements -->
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-24 h-24 bg-verde-lima/40 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute bottom-10 right-20 w-32 h-32 bg-rosado-pastel/40 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <!-- Description Card -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-3xl p-8 shadow-xl border border-azul-claro/30">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-medium leading-tight">
                    <span class="text-gray-900 dark:text-white">Bienvenida a </span>
                    <span class="text-red-500">tu nueva versión</span>
                </h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-gradient-to-b from-gray-50 via-white to-azul-claro/5 dark:from-gray-800 dark:via-gray-900 dark:to-azul-intenso/10 min-h-screen relative">
        <!-- Background decoration -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 right-1/4 w-40 h-40 bg-rosado-pastel rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-1/4 w-60 h-60 bg-verde-lima rounded-full blur-3xl"></div>
        </div>
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Posts Grid -->
            <div class="relative flex-1">
                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        @foreach($posts as $post)
                            <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-200 dark:border-gray-700">
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-400">{{ __('ui.blog.no_image') }}</span>
                                    </div>
                                @endif
                                
                                <div class="p-6">
                                    <div class="flex items-center mb-3">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full" 
                                              style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }};">
                                            {{ $post->category->name }}
                                        </span>
                                    </div>
                                    
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 hover:text-azul-intenso dark:hover:text-azul-claro transition-colors">
                                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h2>
                                    
                                    @if($post->excerpt)
                                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">{{ Str::limit($post->excerpt, 120) }}</p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $post->published_at->diffForHumans() }}</span>
                                        <span>{{ $post->approvedComments->count() }} {{ __('ui.blog.comments') }}</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    {{ $posts->links() }}
                @else
                    <div class="text-center py-12">
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">{{ __('ui.blog.no_posts') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('ui.blog.check_back_soon') }}</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside class="w-full lg:w-80 space-y-6">
                <!-- Categories -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('ui.blog.categories') }}</h3>
                    <div class="space-y-1">
                        @foreach($categories as $category)
                            <a href="{{ route('blog.category', $category->slug) }}" 
                               class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 group">
                                <span class="text-gray-700 dark:text-gray-300 group-hover:text-azul-intenso dark:group-hover:text-azul-claro font-medium">{{ $category->name }}</span>
                                <span class="text-xs bg-azul-intenso/10 dark:bg-azul-claro/20 text-azul-intenso dark:text-azul-claro px-2 py-1 rounded-full font-medium">
                                    {{ $category->published_posts_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- Footer -->
    <x-footer />

</body>
</html>