@extends('layouts.admin')

@section('title', 'Edit Post')

@push('styles')
<style>
.tox-tinymce {
    border-radius: 6px !important;
}
.tox .tox-editor-header {
    border-radius: 6px 6px 0 0 !important;
}
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Editar Post</h2>
                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Ver Post
                    </a>
                </div>

                <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Título *
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Slug *
                        </label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $post->slug) }}" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category and Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Categoría *
                            </label>
                            <select id="category_id" name="category_id" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Selecciona una categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Estado *
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Borrador</option>
                                <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Publicado</option>
                                <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Archivado</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Extracto
                        </label>
                        <textarea id="excerpt" name="excerpt" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('excerpt', $post->excerpt) }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Featured Image and Position -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Imagen Destacada
                            </label>
                            @if($post->featured_image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Imagen actual" class="h-20 w-20 object-cover rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Imagen actual</p>
                                </div>
                            @endif
                            <input type="file" id="featured_image" name="featured_image" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Se convertirá automáticamente a WebP con 95% de calidad</p>
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image_position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Posición de Imagen *
                            </label>
                            <select id="image_position" name="image_position" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="top" {{ old('image_position', $post->image_position) == 'top' ? 'selected' : '' }}>Superior</option>
                                <option value="bottom" {{ old('image_position', $post->image_position) == 'bottom' ? 'selected' : '' }}>Inferior</option>
                                <option value="left" {{ old('image_position', $post->image_position) == 'left' ? 'selected' : '' }}>Izquierda</option>
                                <option value="right" {{ old('image_position', $post->image_position) == 'right' ? 'selected' : '' }}>Derecha</option>
                            </select>
                            @error('image_position')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Published At -->
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fecha de Publicación
                        </label>
                        <input type="datetime-local" id="published_at" name="published_at" 
                               value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content Editor -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contenido *
                        </label>
                        <textarea id="content" name="content" required class="w-full">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comments Enable -->
                    <div class="flex items-center">
                        <input type="checkbox" id="comments_enabled" name="comments_enabled" value="1" 
                               {{ old('comments_enabled', $post->comments_enabled) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="comments_enabled" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Permitir comentarios
                        </label>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.posts.index') }}" 
                           class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                            Cancelar
                        </a>
                        <div class="flex space-x-3">
                            <button type="submit" name="action" value="update"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Actualizar Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-generate slug from title
            document.getElementById('title').addEventListener('input', function() {
                const title = this.value;
                const slug = title.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                document.getElementById('slug').value = slug;
            });

            // Initialize TinyMCE Editor
            tinymce.init({
                selector: '#content',
                height: 500,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
                ],
                toolbar: 'undo redo | blocks | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | fullscreen | code',
            content_style: `
                body { 
                    font-family: 'Inter', system-ui, -apple-system, sans-serif; 
                    font-size: 16px; 
                    line-height: 1.6; 
                    color: #374151;
                }
                h1, h2, h3, h4, h5, h6 { 
                    color: #111827; 
                    margin-top: 1.5em; 
                    margin-bottom: 0.5em; 
                    font-weight: 600;
                }
                p { margin-bottom: 1em; }
                blockquote {
                    border-left: 4px solid #e5e7eb;
                    margin: 1.5em 0;
                    padding: 0.5em 1em;
                    background: #f9fafb;
                    font-style: italic;
                }
                code {
                    background: #f3f4f6;
                    padding: 0.2em 0.4em;
                    border-radius: 3px;
                    font-size: 0.9em;
                }
                pre {
                    background: #1f2937;
                    color: #f9fafb;
                    padding: 1em;
                    border-radius: 6px;
                    overflow-x: auto;
                }
                ul, ol {
                    padding-left: 1.5em;
                    margin-bottom: 1em;
                }
                li {
                    margin-bottom: 0.5em;
                }
                a {
                    color: #3b82f6;
                    text-decoration: none;
                }
                a:hover {
                    text-decoration: underline;
                }
                img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 6px;
                    margin: 1em 0;
                }
                table {
                    border-collapse: collapse;
                    width: 100%;
                    margin: 1em 0;
                }
                table, th, td {
                    border: 1px solid #e5e7eb;
                }
                th, td {
                    padding: 0.75em;
                    text-align: left;
                }
                th {
                    background: #f9fafb;
                    font-weight: 600;
                }
            `,
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            },
            branding: false,
            promotion: false,
            resize: true,
            elementpath: false,
            statusbar: true,
            paste_as_text: false,
            automatic_uploads: false,
            file_picker_types: 'image',
            images_upload_handler: function (blobInfo, success, failure) {
                // Handle image uploads here if needed
                success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
            }
            });
        });
    </script>
@endpush

@endsection