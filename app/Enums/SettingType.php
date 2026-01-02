<?php

namespace App\Enums;

use Illuminate\Support\Stringable;

enum SettingType: string
{
    case Boolean = 'boolean';
    case Text = 'text';
    case Integer = 'integer';
    case Json = 'json';

    public function toDisplay(mixed $value): int|string|Stringable
    {
        return match ($this) {
            SettingType::Boolean => $value ? '✅' : '❌',
            SettingType::Integer => str($value)->toInteger(),
            SettingType::Json, SettingType::Text => str($value)->limit(50),
            default => throw new \RuntimeException('Unsupported setting type: '.get_class($this)),
        };
    }
}
