<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Language;
use App\Models\PostTranslation;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'user'])
                    ->latest()
                    ->paginate(10);
                    
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::where('active', true)->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'image_position' => 'required|in:left,right,top,bottom',
            'status' => 'required|in:draft,published,archived',
            'comments_enabled' => 'boolean'
        ]);

        $validated['user_id'] = auth()->id();
        $validated['comments_enabled'] = $request->has('comments_enabled');
        
        // Set published_at when status is published
        if ($validated['status'] === 'published' && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Handle image upload with WebP conversion
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $this->handleImageUpload($request->file('featured_image'));
        }

        $post = Post::create($validated);

        return redirect()->route('admin.posts.index')
                        ->with('success', 'Post creado exitosamente.');
    }

    public function edit(Request $request, Post $post)
    {
        $categories = Category::where('active', true)->get();
        $languages = Language::where('is_active', true)->get();
        
        // Get current language from request or default to spanish
        $currentLanguage = $request->get('lang', 'es');
        $currentLang = Language::where('code', $currentLanguage)->first();
        
        // Get translation for current language
        $translation = $post->getTranslation($currentLanguage);
        
        // If no translation exists and not the main language, get main post data
        $postData = $translation ?: $post;
        
        return view('admin.posts.edit', compact(
            'post', 'categories', 'languages', 'currentLang', 
            'translation', 'postData', 'currentLanguage'
        ));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'image_position' => 'required|in:left,right,top,bottom',
            'status' => 'required|in:draft,published,archived',
            'comments_enabled' => 'boolean',
            'language' => 'required|exists:languages,code'
        ]);

        $validated['comments_enabled'] = $request->has('comments_enabled');
        $currentLanguage = $validated['language'];
        
        // Set published_at when changing status to published
        if ($validated['status'] === 'published' && $post->status !== 'published') {
            $validated['published_at'] = now();
        } elseif ($validated['status'] !== 'published') {
            $validated['published_at'] = null;
        }

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $this->handleImageUpload($request->file('featured_image'));
        }

        // If updating main language (spanish), update post directly
        if ($currentLanguage === 'es') {
            unset($validated['language']); // Remove language from main post data
            $post->update($validated);
        } else {
            // Update translation
            $language = Language::where('code', $currentLanguage)->first();
            
            $translationData = [
                'title' => $validated['title'],
                'excerpt' => $validated['excerpt'],
                'content' => $validated['content'],
                'slug' => \Str::slug($validated['title'])
            ];
            
            PostTranslation::updateOrCreate(
                [
                    'post_id' => $post->id,
                    'language_id' => $language->id
                ],
                $translationData
            );
            
            // Update main post metadata (category, image, status, etc.)
            $mainPostData = array_diff_key($validated, array_flip(['title', 'excerpt', 'content', 'language']));
            $post->update($mainPostData);
        }

        $redirectUrl = route('admin.posts.edit', ['post' => $post->slug, 'lang' => $currentLanguage]);
        return redirect($redirectUrl)->with('success', 'Post actualizado exitosamente.');
    }

    public function destroy(Post $post)
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();

        return redirect()->route('admin.posts.index')
                        ->with('success', 'Post eliminado exitosamente.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        $path = $this->handleImageUpload($request->file('file'));
        
        return response()->json([
            'location' => asset('storage/' . $path)
        ]);
    }

    private function handleImageUpload($file)
    {
        $filename = time() . '_' . uniqid() . '.webp';
        $path = 'images/' . $filename;

        // Create WebP image with 95% quality
        $image = Image::make($file)
                     ->encode('webp', 95)
                     ->resize(1200, null, function ($constraint) {
                         $constraint->aspectRatio();
                         $constraint->upsize();
                     });

        Storage::disk('public')->put($path, $image);

        return $path;
    }
}
