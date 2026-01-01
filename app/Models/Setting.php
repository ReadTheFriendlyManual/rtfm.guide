<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'registration_enabled',
        'login_enabled',
        'registration_disabled_message',
        'login_disabled_message',
    ];

    protected function casts(): array
    {
        return [
            'registration_enabled' => 'boolean',
            'login_enabled' => 'boolean',
        ];
    }

    /**
     * Get the application settings (cached).
     */
    public static function current(): self
    {
        return Cache::rememberForever('app_settings', function () {
            return self::first() ?? new self([
                'registration_enabled' => false,
                'login_enabled' => true,
            ]);
        });
    }

    /**
     * Clear the settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('app_settings');
    }

    protected static function booted(): void
    {
        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}
