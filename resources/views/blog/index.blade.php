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
    <title>{{ __('ui.nav.blog') }} - ByMelodia</title>
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
            
            <!-- Centered Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}">
                    <img 
                        id="logo" 
                        src="/images/bymelodia_negro.png" 
                        alt="ByMelodia" 
                        class="h-12 w-auto mx-auto transition-all duration-300 hover:scale-105"
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
                <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-azul-intenso dark:hover:text-azul-claro transition-colors font-medium text-lg">
                    {{ __('ui.nav.about') }}
                </a>
            </nav>
        </div>
    </header>

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-900 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">{{ __('ui.blog.title') }}</h1>
            <p class="text-xl text-gray-600 dark:text-gray-400">{{ __('ui.blog.subtitle') }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Posts Grid -->
            <div class="flex-1">
                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
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