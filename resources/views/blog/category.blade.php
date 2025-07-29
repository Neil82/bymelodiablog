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
    <title>{{ $category->name }} - ByMelodia</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logo_bymelodia_blanco.png">
    <link rel="apple-touch-icon" href="/images/logo_bymelodia_blanco.png">
    <meta name="description" content="{{ $category->description }}">
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
                <a href="{{ route('blog.index') }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                    {{ __('ui.nav.blog') }}
                </a>
                <a href="#" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                    {{ __('ui.nav.about') }}
                </a>
                
            </nav>
        </div>
    </header>

    <!-- Category Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 text-white py-20">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
        </div>
        
        <!-- Floating Elements with Category Color -->
        <div class="absolute top-20 left-10 w-20 h-20 rounded-full blur-xl animate-pulse" 
             style="background-color: {{ $category->color }}40;"></div>
        <div class="absolute top-40 right-20 w-32 h-32 rounded-full blur-xl animate-pulse delay-1000" 
             style="background-color: {{ $category->color }}30;"></div>
        <div class="absolute bottom-20 left-1/4 w-16 h-16 rounded-full blur-xl animate-pulse delay-2000" 
             style="background-color: {{ $category->color }}50;"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="space-y-6">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-sm font-medium mb-4">
                    <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $category->color }};"></span>
                    {{ __('ui.blog.category') }}
                </div>
                
                <h1 class="text-5xl md:text-6xl font-black mb-4 leading-tight">
                    <span class="bg-gradient-to-r from-white via-pink-200 to-purple-200 bg-clip-text text-transparent">
                        {{ $category->name }}
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto leading-relaxed font-light">
                    {{ $category->description }}
                </p>
                
                <div class="flex items-center justify-center space-x-6 mt-8 text-sm opacity-80">
                    <span>{{ $posts->total() }} {{ __('ui.blog.articles') }}</span>
                    <span>•</span>
                    <span>{{ __('ui.blog.updated_regularly') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ __('ui.nav.home') }}</a></li>
                <li><span>/</span></li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ __('ui.nav.blog') }}</a></li>
                <li><span>/</span></li>
                <li class="text-gray-700 dark:text-gray-300">{{ $category->name }}</li>
            </ol>
        </nav>

        @if($posts->count() > 0)
            <!-- Posts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($posts as $post)
                    <article class="group bg-white dark:bg-gray-800 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-2">
                        <div class="relative">
                            @if($post->featured_image)
                                <div class="h-48 bg-cover bg-center relative overflow-hidden"
                                     style="background-image: url('{{ asset('storage/' . $post->featured_image) }}')">
                                    <div class="absolute inset-0 bg-black/30"></div>
                            @else
                                <div class="h-48 bg-gradient-to-br relative overflow-hidden"
                                     style="background: linear-gradient(135deg, {{ $category->color }}80, {{ $category->color }}40);">
                                    <div class="absolute inset-0 bg-black/10"></div>
                            @endif
                                    <div class="absolute top-4 left-4">
                                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-bold rounded-full"
                                              style="color: {{ $category->color }}">
                                            {{ $category->name }}
                                        </span>
                                    </div>
                                </div>
                        </div>
                        
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                            
                            @if($post->excerpt)
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                                    {{ $post->excerpt }}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                         style="background-color: {{ $category->color }}">
                                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $post->published_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span>{{ $post->views }} {{ __('ui.blog.views') }}</span>
                                    <span>{{ $post->approvedComments->count() }} {{ __('ui.blog.comments') }}</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full flex items-center justify-center"
                     style="background-color: {{ $category->color }}20;">
                    <span class="text-4xl" style="color: {{ $category->color }};">📝</span>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('ui.blog.no_content_in_category') }} {{ $category->name }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    {{ __('ui.blog.working_on_content') }} {{ strtolower($category->name) }}!
                </p>
                <a href="{{ route('blog.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-semibold rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    {{ __('ui.blog.view_all_blog') }}
                </a>
            </div>
        @endif
        
        <!-- Related Categories -->
        @php
            $relatedCategories = App\Models\Category::where('active', true)
                                                   ->where('id', '!=', $category->id)
                                                   ->withCount('publishedPosts')
                                                   ->limit(4)
                                                   ->get();
        @endphp
        
        @if($relatedCategories->count() > 0)
            <section class="mt-16 pt-12 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                    Explora otras categorías
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedCategories as $relatedCategory)
                        <a href="{{ route('blog.category', $relatedCategory->slug) }}" 
                           class="group p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 text-center">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-full flex items-center justify-center"
                                 style="background-color: {{ $relatedCategory->color }}20;">
                                <span class="text-xl" style="color: {{ $relatedCategory->color }};">📂</span>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $relatedCategory->name }}
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $relatedCategory->published_posts_count }} artículos
                            </p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

    <!-- Footer -->
    <x-footer />

</body>
</html>