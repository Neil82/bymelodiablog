@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto">
        
        <!-- Page Header -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-gray-500">Categorías</a>
                    </li>
                    <li>
                        <span class="text-gray-400">/</span>
                    </li>
                    <li>
                        <span class="text-gray-900 dark:text-white">Editar</span>
                    </li>
                </ol>
            </nav>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl">
                Editar Categoría: {{ $category->name }}
            </h2>
        </div>

        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                
                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nombre *
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Descripción
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $category->description) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Descripción opcional de la categoría</p>
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Color *
                    </label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color" name="color" id="color" value="{{ old('color', $category->color) }}" required
                               class="h-10 w-20 border border-gray-300 dark:border-gray-600 rounded-md">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Selecciona un color para identificar la categoría
                        </span>
                    </div>
                </div>

                <!-- Estado -->
                <div class="flex items-center">
                    <input type="checkbox" name="active" id="active" value="1" {{ old('active', $category->active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Categoría activa
                    </label>
                </div>

                <!-- Información adicional -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Información</h3>
                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <p>Posts asociados: {{ $category->posts()->count() }}</p>
                        <p>Creada: {{ $category->created_at->format('d/m/Y H:i') }}</p>
                        <p>Última actualización: {{ $category->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 dark:bg-gray-600 py-2 px-4 border border-gray-300 dark:border-gray-500 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Actualizar Categoría
                </button>
            </div>
        </form>

</div>
@endsection