<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class UserSession extends Model
{
    protected $fillable = [
        'session_id',
        'ip_address',
        'user_agent',
        'country_code',
        'country_name',
        'city',
        'region',
        'latitude',
        'longitude',
        'device_type',
        'browser',
        'browser_version',
        'os',
        'os_version',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'language_code',
        'started_at',
        'last_activity_at',
        'ended_at',
        'total_duration',
        'page_views',
        'is_bot'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'ended_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'total_duration' => 'integer',
        'page_views' => 'integer',
        'is_bot' => 'boolean'
    ];

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(TrackingEvent::class);
    }

    public function updateActivity()
    {
        $now = now();
        $this->last_activity_at = $now;
        $this->total_duration = $this->started_at->diffInSeconds($now);
        $this->save();
    }

    public function endSession()
    {
        $this->ended_at = now();
        $this->total_duration = $this->started_at->diffInSeconds($this->ended_at);
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->whereNull('ended_at')
                    ->where('last_activity_at', '>', now()->subMinutes(30));
    }

    public function scopeByCountry($query, $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }

    public function scopeByDevice($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }
}
