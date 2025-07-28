<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'author_name',
        'author_email',
        'content',
        'status',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
