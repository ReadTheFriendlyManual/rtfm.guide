<?php

namespace App\Models;

use App\Enums\SettingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'type',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'type' => SettingType::class,
        ];
    }

    /**
     * Get all settings as a cached collection.
     */
    public static function all($columns = ['*']): Collection
    {
        return Cache::rememberForever('app_settings', function () use ($columns) {
            return parent::all($columns);
        });
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = self::all()->firstWhere('key', $key);

        if (! $setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value, ?SettingType $type = null): self
    {
        return self::withoutGlobalScopes()->updateOrCreate(
            ['key' => $key],
            [
                'type' => $type ?? self::inferType($value),
                'value' => self::encodeValue($value),
            ]
        );
    }

    /**
     * Check if a setting exists.
     */
    public static function has(string $key): bool
    {
        return self::all()->contains('key', $key);
    }

    /**
     * Cast value based on type.
     */
    protected static function castValue(mixed $value, SettingType $type): mixed
    {
        return match ($type) {
            SettingType::Boolean => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            SettingType::Integer => (int) $value,
            SettingType::Json => json_decode($value, true),
            SettingType::Text => (string) $value,
        };
    }

    /**
     * Encode value for storage.
     */
    protected static function encodeValue(mixed $value): string
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return (string) $value;
    }

    /**
     * Infer the type from the value.
     */
    protected static function inferType(mixed $value): SettingType
    {
        return match (true) {
            is_bool($value) => SettingType::Boolean,
            is_int($value) => SettingType::Integer,
            is_array($value) || is_object($value) => SettingType::Json,
            default => SettingType::Text,
        };
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
