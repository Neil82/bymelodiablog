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
    <meta name="post-id" content="{{ $post->id }}">
    <title>{{ $post->title }} - ByMelodia</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logo_bymelodia_blanco.png">
    <link rel="apple-touch-icon" href="/images/logo_bymelodia_blanco.png">
    <meta name="description" content="{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
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
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ __('ui.nav.home') }}</a></li>
                <li><span>/</span></li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ __('ui.nav.blog') }}</a></li>
                <li><span>/</span></li>
                <li><a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ $post->category->name }}</a></li>
                <li><span>/</span></li>
                <li class="text-gray-700 dark:text-gray-300">{{ $post->title }}</li>
            </ol>
        </nav>

        <!-- Post Header -->
        <header class="mb-8">
            <div class="flex items-center mb-4">
                <span class="px-3 py-1 text-sm font-medium rounded-full" 
                      style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }};">
                    {{ $post->category->name }}
                </span>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>
            
            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 space-x-4">
                <span>{{ __('ui.blog.by_author') }} {{ $post->user->name }}</span>
                <span>•</span>
                <span>{{ $post->published_at->format('d M Y') }}</span>
                <span>•</span>
                <span>{{ $post->views }} {{ __('ui.blog.views') }}</span>
                @if($post->approvedComments->count() > 0)
                    <span>•</span>
                    <span>{{ $post->approvedComments->count() }} {{ __('ui.blog.comments') }}</span>
                @endif
            </div>
        </header>

        <!-- Post Content with Flexible Image Position -->
        <article class="prose prose-lg dark:prose-invert max-w-none">
            @if($post->featured_image)
                @php
                    $imageElement = '<img src="' . asset('storage/' . $post->featured_image) . '" alt="' . $post->title . '" class="w-full rounded-lg shadow-lg">';
                @endphp
                
                @if($post->image_position === 'top')
                    <div class="mb-8">
                        {!! $imageElement !!}
                    </div>
                    <div class="post-content">
                        {!! $post->content !!}
                    </div>
                @elseif($post->image_position === 'bottom')
                    <div class="post-content mb-8">
                        {!! $post->content !!}
                    </div>
                    <div>
                        {!! $imageElement !!}
                    </div>
                @elseif($post->image_position === 'left')
                    <div class="flex flex-col lg:flex-row gap-8">
                        <div class="lg:w-1/3">
                            {!! $imageElement !!}
                        </div>
                        <div class="lg:w-2/3 post-content">
                            {!! $post->content !!}
                        </div>
                    </div>
                @elseif($post->image_position === 'right')
                    <div class="flex flex-col lg:flex-row-reverse gap-8">
                        <div class="lg:w-1/3">
                            {!! $imageElement !!}
                        </div>
                        <div class="lg:w-2/3 post-content">
                            {!! $post->content !!}
                        </div>
                    </div>
                @endif
            @else
                <div class="post-content">
                    {!! $post->content !!}
                </div>
            @endif
        </article>

        <!-- Comments Section -->
        @if($post->comments_enabled)
            <section class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    {{ __('ui.blog.comments') }} ({{ $post->approvedComments->count() }})
                </h3>

                <!-- Comment Form -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('ui.blog.leave_comment') }}</h4>
                    
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('blog.comments.store', $post->slug) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="author_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('ui.blog.your_name') }} *
                                </label>
                                <input type="text" id="author_name" name="author_name" value="{{ old('author_name') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                @error('author_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="author_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('ui.blog.your_email') }} *
                                </label>
                                <input type="email" id="author_email" name="author_email" value="{{ old('author_email') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                @error('author_email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('ui.blog.your_comment') }} *
                            </label>
                            <textarea id="content" name="content" rows="4" required
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('ui.blog.comment_moderation') }}
                        </div>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            {{ __('ui.blog.submit_comment') }}
                        </button>
                    </form>
                </div>

                <!-- Comments List -->
                @if($post->approvedComments->count() > 0)
                    <div class="space-y-6">
                        @foreach($post->approvedComments as $comment)
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-medium">
                                            {{ strtoupper(substr($comment->author_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h5 class="font-medium text-gray-900 dark:text-white">{{ $comment->author_name }}</h5>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                        {{ __('ui.blog.no_comments_yet') }}
                    </p>
                @endif
            </section>
        @endif

        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
            <section class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('ui.blog.related_posts') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $relatedPost)
                        <article class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            @if($relatedPost->featured_image)
                                <img src="{{ asset('storage/' . $relatedPost->featured_image) }}" alt="{{ $relatedPost->title }}" class="w-full h-32 object-cover">
                            @else
                                <div class="w-full h-32 bg-gray-200 dark:bg-gray-700"></div>
                            @endif
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    <a href="{{ route('blog.show', $relatedPost->slug) }}">{{ Str::limit($relatedPost->title, 50) }}</a>
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $relatedPost->published_at->diffForHumans() }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Analytics Tracking -->
    <script src="{{ asset('js/analytics-tracker.js') }}"></script>

</body>
</html>