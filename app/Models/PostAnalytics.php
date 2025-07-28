<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostAnalytics extends Model
{
    protected $fillable = [
        'post_id',
        'date',
        'unique_views',
        'total_views',
        'avg_time_on_page',
        'total_time_on_page',
        'bounce_rate',
        'scroll_depth_avg',
        'social_shares',
        'comments_count',
        'traffic_sources',
        'device_breakdown',
        'country_breakdown',
        'referrer_breakdown'
    ];

    protected $casts = [
        'date' => 'date',
        'traffic_sources' => 'array',
        'device_breakdown' => 'array',
        'country_breakdown' => 'array',
        'referrer_breakdown' => 'array',
        'unique_views' => 'integer',
        'total_views' => 'integer',
        'avg_time_on_page' => 'integer',
        'total_time_on_page' => 'integer',
        'bounce_rate' => 'integer',
        'scroll_depth_avg' => 'integer',
        'social_shares' => 'integer',
        'comments_count' => 'integer'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    public static function getMostViewedPosts($limit = 10, $days = 30)
    {
        return static::where('date', '>=', now()->subDays($days))
            ->selectRaw('post_id, SUM(unique_views) as total_unique_views')
            ->groupBy('post_id')
            ->orderByDesc('total_unique_views')
            ->limit($limit)
            ->with('post')
            ->get();
    }
}
