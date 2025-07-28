<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PostTranslation extends Model
{
    protected $fillable = [
        'post_id',
        'language_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'seo_meta'
    ];

    protected $casts = [
        'seo_meta' => 'array'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($translation) {
            if (empty($translation->slug)) {
                $translation->slug = Str::slug($translation->title);
            }
        });
        
        static::updating(function ($translation) {
            if ($translation->isDirty('title') && empty($translation->slug)) {
                $translation->slug = Str::slug($translation->title);
            }
        });
    }

    public function scopeByLanguage($query, $languageCode)
    {
        return $query->whereHas('language', function ($q) use ($languageCode) {
            $q->where('code', $languageCode);
        });
    }
}
