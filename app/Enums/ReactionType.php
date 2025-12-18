<?php

namespace App\Enums;

enum ReactionType: string
{
    case HELPFUL = 'helpful';
    case SAVED_ME = 'saved_me';
    case OUTDATED = 'outdated';
    case NEEDS_UPDATE = 'needs_update';

    public function label(): string
    {
        return match ($this) {
            self::HELPFUL => 'This helped me',
            self::SAVED_ME => 'This saved me time',
            self::OUTDATED => 'This is outdated',
            self::NEEDS_UPDATE => 'Needs updating',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::HELPFUL => '👍',
            self::SAVED_ME => '💾',
            self::OUTDATED => '⏰',
            self::NEEDS_UPDATE => '🔄',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::HELPFUL => 'green',
            self::SAVED_ME => 'blue',
            self::OUTDATED => 'yellow',
            self::NEEDS_UPDATE => 'orange',
        };
    }
}
