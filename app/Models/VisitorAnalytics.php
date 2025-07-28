<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorAnalytics extends Model
{
    protected $fillable = [
        'date',
        'unique_visitors',
        'total_page_views',
        'new_visitors',
        'returning_visitors',
        'avg_session_duration',
        'bounce_rate',
        'avg_pages_per_session',
        'top_pages',
        'top_referrers',
        'top_countries',
        'device_stats',
        'browser_stats',
        'os_stats',
        'traffic_sources',
        'language_stats'
    ];

    protected $casts = [
        'date' => 'date',
        'top_pages' => 'array',
        'top_referrers' => 'array',
        'top_countries' => 'array',
        'device_stats' => 'array',
        'browser_stats' => 'array',
        'os_stats' => 'array',
        'traffic_sources' => 'array',
        'language_stats' => 'array',
        'unique_visitors' => 'integer',
        'total_page_views' => 'integer',
        'new_visitors' => 'integer',
        'returning_visitors' => 'integer',
        'avg_session_duration' => 'integer',
        'bounce_rate' => 'integer',
        'avg_pages_per_session' => 'integer'
    ];

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public static function getTotalStats($days = 30)
    {
        return static::where('date', '>=', now()->subDays($days))
            ->selectRaw('
                SUM(unique_visitors) as total_unique_visitors,
                SUM(total_page_views) as total_page_views,
                AVG(avg_session_duration) as avg_session_duration,
                AVG(bounce_rate) as avg_bounce_rate
            ')
            ->first();
    }

    public static function getGrowthData($days = 30)
    {
        $current = static::where('date', '>=', now()->subDays($days))
            ->sum('unique_visitors');
            
        $previous = static::where('date', '>=', now()->subDays($days * 2))
            ->where('date', '<', now()->subDays($days))
            ->sum('unique_visitors');
            
        $growth = $previous > 0 ? (($current - $previous) / $previous) * 100 : 0;
        
        return [
            'current' => $current,
            'previous' => $previous,
            'growth_percentage' => round($growth, 2)
        ];
    }
}
