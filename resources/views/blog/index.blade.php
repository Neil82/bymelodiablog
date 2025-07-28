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
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">

    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Language Selector -->
            <div class="absolute top-4 left-4">
                @include('components.language-selector')
            </div>
            
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
                    {{ __('ui.nav.home') }}
                </a>
                <a href="{{ route('blog.index') }}" class="text-blue-600 dark:text-blue-400 font-semibold">
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
                        {{ __('ui.nav.login') }}
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-800 dark:to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">{{ __('ui.blog.title') }}</h1>
            <p class="text-xl opacity-90">{{ __('ui.blog.subtitle') }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Posts Grid -->
            <div class="flex-1">
                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        @foreach($posts as $post)
                            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
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
                                    
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
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
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('ui.blog.categories') }}</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <a href="{{ route('blog.category', $category->slug) }}" 
                               class="flex items-center justify-between p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <span class="text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                                <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-400 px-2 py-1 rounded-full">
                                    {{ $category->published_posts_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </main>

</body>
</html>