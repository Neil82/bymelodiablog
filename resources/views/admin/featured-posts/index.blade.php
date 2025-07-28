@extends('layouts.admin')

@section('title', 'Featured Posts')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endpush

@section('content')

    <main class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl">
                Posts Destacados en Home
            </h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Gestiona los 6 posts que se mostrarán en la página de inicio. Puedes reordenarlos arrastrando.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Featured Posts List -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Posts Destacados ({{ $featuredPosts->count() }}/6)
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($featuredPosts->count() > 0)
                        <div id="featured-posts-list" class="space-y-4">
                            @foreach($featuredPosts as $featured)
                                <div class="featured-post-item flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-move" data-id="{{ $featured->id }}">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                        </svg>
                                    </div>
                                    
                                    @if($featured->post->featured_image)
                                        <img src="{{ asset('storage/' . $featured->post->featured_image) }}" 
                                             alt="{{ $featured->post->title }}" 
                                             class="w-16 h-12 object-cover rounded mr-4">
                                    @else
                                        <div class="w-16 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded mr-4 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">{{ substr($featured->post->title, 0, 2) }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $featured->post->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                                                  style="background-color: {{ $featured->post->category->color }}20; color: {{ $featured->post->category->color }};">
                                                {{ $featured->post->category->name }}
                                            </span>
                                        </p>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('admin.featured-posts.destroy', $featured) }}" class="ml-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" 
                                                onclick="return confirm('¿Estás seguro de quitar este post de destacados?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay posts destacados</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comienza agregando posts desde la lista de la derecha.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Posts -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Posts Disponibles
                    </h3>
                </div>
                
                <div class="p-6 max-h-96 overflow-y-auto">
                    @if($availablePosts->count() > 0)
                        <div class="space-y-3">
                            @foreach($availablePosts as $post)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    @if($post->featured_image)
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                             alt="{{ $post->title }}" 
                                             class="w-12 h-9 object-cover rounded mr-3">
                                    @else
                                        <div class="w-12 h-9 bg-gradient-to-br from-purple-500 to-blue-500 rounded mr-3 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">{{ substr($post->title, 0, 2) }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $post->title }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                                                  style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }};">
                                                {{ $post->category->name }}
                                            </span>
                                        </p>
                                    </div>
                                    
                                    @if($featuredPosts->count() < 6)
                                        <form method="POST" action="{{ route('admin.featured-posts.store') }}" class="ml-3">
                                            @csrf
                                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                                            <button type="submit" class="text-blue-600 hover:text-blue-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="ml-3 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay posts disponibles</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Todos los posts publicados ya están destacados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const featuredPostsList = document.getElementById('featured-posts-list');
            
            if (featuredPostsList) {
                const sortable = Sortable.create(featuredPostsList, {
                    handle: '.featured-post-item',
                    animation: 150,
                    onEnd: function(evt) {
                        const order = Array.from(featuredPostsList.children).map(item => 
                            parseInt(item.getAttribute('data-id'))
                        );
                        
                        fetch('{{ route("admin.featured-posts.order") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ order: order })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                console.error('Error updating order');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                });
            }
        });
    </script>

@endsection