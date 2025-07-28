<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedPost;
use App\Models\Post;
use Illuminate\Http\Request;

class FeaturedPostController extends Controller
{
    public function index()
    {
        $featuredPosts = FeaturedPost::with('post.category', 'post.user')
            ->orderBy('order')
            ->get();
            
        $availablePosts = Post::published()
            ->whereNotIn('id', $featuredPosts->pluck('post_id'))
            ->with('category')
            ->latest('published_at')
            ->get();

        return view('admin.featured-posts.index', compact('featuredPosts', 'availablePosts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        // Check if we already have 6 featured posts
        if (FeaturedPost::count() >= 6) {
            return redirect()->back()->with('error', 'Solo se pueden destacar máximo 6 posts.');
        }

        // Check if post is already featured
        if (FeaturedPost::where('post_id', $request->post_id)->exists()) {
            return redirect()->back()->with('error', 'Este post ya está destacado.');
        }

        $nextOrder = FeaturedPost::max('order') + 1;

        FeaturedPost::create([
            'post_id' => $request->post_id,
            'order' => $nextOrder
        ]);

        return redirect()->back()->with('success', 'Post agregado a destacados.');
    }

    public function destroy(FeaturedPost $featuredPost)
    {
        $featuredPost->delete();
        
        // Reorder remaining posts
        $this->reorderPosts();

        return redirect()->back()->with('success', 'Post removido de destacados.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:featured_posts,id'
        ]);

        foreach ($request->order as $index => $featuredPostId) {
            FeaturedPost::where('id', $featuredPostId)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    private function reorderPosts()
    {
        $featuredPosts = FeaturedPost::orderBy('order')->get();
        
        foreach ($featuredPosts as $index => $featuredPost) {
            $featuredPost->update(['order' => $index + 1]);
        }
    }
}
