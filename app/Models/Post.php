<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'image_position',
        'category_id',
        'user_id',
        'status',
        'published_at',
        'comments_enabled',
        'seo_meta'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'comments_enabled' => 'boolean',
        'seo_meta' => 'array'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('status', 'approved');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function getTranslation($languageCode = null)
    {
        if (!$languageCode) {
            $languageCode = app()->getLocale();
        }
        
        return $this->translations()
            ->whereHas('language', function ($query) use ($languageCode) {
                $query->where('code', $languageCode);
            })
            ->first();
    }

    public function getLocalizedSlug($languageCode = null)
    {
        $translation = $this->getTranslation($languageCode);
        return $translation ? $translation->slug : $this->slug;
    }

    public function getUrl($languageCode = null)
    {
        $slug = $this->getLocalizedSlug($languageCode);
        return route('blog.show', ['post' => $slug]);
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(PostAnalytics::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at <= now();
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Retrieve the model for a bound value.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // First try to find by main slug
        $post = $this->where($field ?? $this->getRouteKeyName(), $value)->first();
        
        if ($post) {
            return $post;
        }

        // If not found, try to find by translation slug
        $translation = \App\Models\PostTranslation::where('slug', $value)->first();
        
        if ($translation) {
            return $translation->post;
        }

        return null;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (empty($post->published_at) && $post->status === 'published') {
                $post->published_at = now();
            }
        });
    }
}
