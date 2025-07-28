<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Comment;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share pending comments count with admin navigation component
        View::composer('components.admin-nav', function ($view) {
            $pendingCommentsCount = Comment::where('status', 'pending')->count();
            $view->with('pendingCommentsCount', $pendingCommentsCount);
        });
    }
}
