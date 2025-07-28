<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingEvent extends Model
{
    protected $fillable = [
        'user_session_id',
        'event_type',
        'url',
        'page_title',
        'post_id',
        'time_on_page',
        'scroll_depth',
        'event_data',
        'element_clicked',
        'event_time'
    ];

    protected $casts = [
        'event_data' => 'array',
        'event_time' => 'datetime',
        'time_on_page' => 'integer',
        'scroll_depth' => 'integer'
    ];

    public function userSession(): BelongsTo
    {
        return $this->belongsTo(UserSession::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function scopeByPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('event_time', [$startDate, $endDate]);
    }
}
