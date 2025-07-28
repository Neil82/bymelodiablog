@extends('layouts.admin')

@section('title', 'Comments')

@section('content')
        
        <!-- Page Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Comentarios
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <!-- Filter Tabs -->
                <div class="flex space-x-2">
                    <a href="{{ route('admin.comments.index', ['status' => 'all']) }}" 
                       class="px-3 py-2 text-sm font-medium rounded-lg {{ $status === 'all' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        Todos
                    </a>
                    <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" 
                       class="px-3 py-2 text-sm font-medium rounded-lg {{ $status === 'pending' ? 'bg-yellow-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        Pendientes
                        @if($pendingCount > 0)
                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}" 
                       class="px-3 py-2 text-sm font-medium rounded-lg {{ $status === 'approved' ? 'bg-green-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        Aprobados
                    </a>
                    <a href="{{ route('admin.comments.index', ['status' => 'rejected']) }}" 
                       class="px-3 py-2 text-sm font-medium rounded-lg {{ $status === 'rejected' ? 'bg-red-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        Rechazados
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Comments List -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($comments as $comment)
                    <li class="px-6 py-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-pink-400 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white text-xs font-bold">{{ strtoupper(substr($comment->author_name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $comment->author_name }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $comment->author_email }} • {{ $comment->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="ml-4 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                        {{ $comment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $comment->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $comment->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($comment->status) }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-700 dark:text-gray-300 mb-3">
                                    {{ $comment->content }}
                                </p>
                                
                                <div class="text-sm">
                                    <a href="{{ route('blog.show', $comment->post->slug) }}#comment-{{ $comment->id }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        En: "{{ $comment->post->title }}"
                                        <span class="inline-flex items-center px-2 py-0.5 ml-2 rounded-full text-xs font-medium" 
                                              style="background-color: {{ $comment->post->category->color }}20; color: {{ $comment->post->category->color }};">
                                            {{ $comment->post->category->name }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                @if($comment->status === 'pending')
                                    <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Aprobar">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.comments.reject', $comment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Rechazar">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @elseif($comment->status === 'rejected')
                                    <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Aprobar">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.comments.reject', $comment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300" title="Rechazar">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                                
                                <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este comentario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Eliminar">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-12 text-center">
                        <div class="text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="text-lg font-medium mb-2">No hay comentarios</h3>
                            <p>Los comentarios aparecerán aquí cuando los usuarios comenten en tus posts</p>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>

        @if($comments->hasPages())
            <div class="mt-6">
                {{ $comments->links() }}
            </div>
        @endif

@endsection