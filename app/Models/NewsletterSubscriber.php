<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'name',
        'confirmation_token',
        'confirmed_at',
        'language_code',
        'is_active',
        'ip_address',
        'user_agent',
        'unsubscribed_at',
        'unsubscribe_token',
        'preferences'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'is_active' => 'boolean',
        'preferences' => 'array'
    ];

    /**
     * Scope for confirmed subscribers
     */
    public function scopeConfirmed($query)
    {
        return $query->whereNotNull('confirmed_at');
    }

    /**
     * Scope for active subscribers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->whereNotNull('confirmed_at')
                    ->whereNull('unsubscribed_at');
    }

    /**
     * Check if subscriber is confirmed
     */
    public function isConfirmed()
    {
        return $this->confirmed_at !== null;
    }

    /**
     * Check if subscriber is active
     */
    public function isActive()
    {
        return $this->is_active && 
               $this->isConfirmed() && 
               $this->unsubscribed_at === null;
    }

    /**
     * Generate confirmation token
     */
    public static function generateConfirmationToken()
    {
        return Str::random(64);
    }

    /**
     * Generate unsubscribe token
     */
    public static function generateUnsubscribeToken()
    {
        return Str::random(32);
    }

    /**
     * Confirm subscription
     */
    public function confirm()
    {
        $this->update([
            'confirmed_at' => now(),
            'confirmation_token' => null
        ]);
    }

    /**
     * Unsubscribe
     */
    public function unsubscribe()
    {
        $this->update([
            'is_active' => false,
            'unsubscribed_at' => now()
        ]);
    }
}