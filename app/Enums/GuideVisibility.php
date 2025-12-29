<?php

namespace App\Enums;

enum GuideVisibility: string
{
    case Public = 'public';
    case Private = 'private';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Public',
            self::Private => 'Private',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Public => 'Anyone can view this guide',
            self::Private => 'Only you and your team can view this guide',
        };
    }
}
