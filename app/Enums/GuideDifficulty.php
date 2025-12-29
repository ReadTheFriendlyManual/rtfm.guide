<?php

namespace App\Enums;

enum GuideDifficulty: string
{
    case Beginner = 'beginner';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';

    public function label(): string
    {
        return match ($this) {
            self::Beginner => 'Beginner',
            self::Intermediate => 'Intermediate',
            self::Advanced => 'Advanced',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Beginner => 'Perfect for those new to the topic',
            self::Intermediate => 'Requires some basic knowledge',
            self::Advanced => 'For experienced users',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Beginner => 'green',
            self::Intermediate => 'blue',
            self::Advanced => 'red',
        };
    }
}
