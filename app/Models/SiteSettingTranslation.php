<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettingTranslation extends Model
{
    protected $fillable = [
        'site_setting_id',
        'language_id',
        'value',
    ];

    /**
     * Get the site setting this translation belongs to.
     */
    public function siteSetting()
    {
        return $this->belongsTo(SiteSetting::class);
    }

    /**
     * Get the language this translation is for.
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}