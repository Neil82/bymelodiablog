<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\UserSession;
use App\Models\TrackingEvent;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get basic stats for the dashboard
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_categories' => Category::count(),
            'total_visitors_today' => UserSession::whereDate('started_at', today())->count(),
            'total_page_views_today' => TrackingEvent::where('event_type', 'page_view')
                ->whereDate('event_time', today())->count(),
        ];

        // Get recent posts
        $recentPosts = Post::latest('updated_at')->limit(5)->get();

        // Get recent visitor activity (today)
        $todayVisitors = UserSession::whereDate('started_at', today())
            ->latest('started_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'todayVisitors'));
    }
}