<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::published()
                    ->select(['slug', 'updated_at', 'created_at'])
                    ->orderBy('updated_at', 'desc')
                    ->get();
        
        $categories = Category::where('active', true)
                             ->select(['slug', 'updated_at', 'created_at'])
                             ->orderBy('updated_at', 'desc')
                             ->get();

        return response()->view('sitemap.index', compact('posts', 'categories'))
                        ->header('Content-Type', 'application/xml');
    }

    public function posts()
    {
        $posts = Post::published()
                    ->with(['category', 'user'])
                    ->select(['slug', 'title', 'excerpt', 'updated_at', 'published_at', 'category_id'])
                    ->orderBy('updated_at', 'desc')
                    ->get();

        return response()->view('sitemap.posts', compact('posts'))
                        ->header('Content-Type', 'application/xml');
    }
}