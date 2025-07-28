<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CategoryTranslation extends Model
{
    protected $fillable = [
        'category_id',
        'language_id',
        'name',
        'slug',
        'description'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
                $translation->slug = Str::slug($translation->name);
            }
        });
        
        static::updating(function ($translation) {
            if ($translation->isDirty('name') && empty($translation->slug)) {
                $translation->slug = Str::slug($translation->name);
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
