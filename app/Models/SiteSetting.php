<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description'
    ];

    protected $casts = [
        'value' => 'string'
    ];

    /**
     * Get the translations for this setting.
     */
    public function translations()
    {
        return $this->hasMany(SiteSettingTranslation::class);
    }

    /**
     * Get a specific translation for this setting.
     */
    public function getTranslation($languageCode)
    {
        $language = Language::where('code', $languageCode)->where('is_active', true)->first();
        
        if (!$language) {
            return null;
        }
        
        return $this->translations()
            ->where('language_id', $language->id)
            ->first();
    }

    // Get setting value by key with caching and translation support
    public static function get($key, $default = null)
    {
        $currentLang = app()->getLocale();
        $cacheKey = "setting_{$key}_{$currentLang}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default, $currentLang) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            // Try to get translation for current language
            $translation = $setting->getTranslation($currentLang);
            
            if ($translation) {
                return $translation->value;
            }
            
            // Fallback to default value
            return $setting->value;
        });
    }

    // Set setting value
    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear cache for all language versions
        $languages = Language::where('is_active', true)->pluck('code');
        Cache::forget("setting_{$key}");
        foreach ($languages as $lang) {
            Cache::forget("setting_{$key}_{$lang}");
        }
        
        return $setting;
    }

    // Get all settings by group
    public static function getByGroup($group)
    {
        return Cache::remember("settings_group_{$group}", 3600, function () use ($group) {
            return self::where('group', $group)->get()->keyBy('key');
        });
    }

    // Clear all settings cache
    public static function clearCache()
    {
        $keys = self::pluck('key');
        $languages = Language::where('is_active', true)->pluck('code');
        
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
            // Clear cache for all language versions
            foreach ($languages as $lang) {
                Cache::forget("setting_{$key}_{$lang}");
            }
        }
        
        $groups = self::distinct('group')->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("settings_group_{$group}");
        }
    }

    // Boot method to clear cache on save
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            $languages = Language::where('is_active', true)->pluck('code');
            Cache::forget("setting_{$setting->key}");
            foreach ($languages as $lang) {
                Cache::forget("setting_{$setting->key}_{$lang}");
            }
            Cache::forget("settings_group_{$setting->group}");
        });

        static::deleted(function ($setting) {
            $languages = Language::where('is_active', true)->pluck('code');
            Cache::forget("setting_{$setting->key}");
            foreach ($languages as $lang) {
                Cache::forget("setting_{$setting->key}_{$lang}");
            }
            Cache::forget("settings_group_{$setting->group}");
        });
    }
}
