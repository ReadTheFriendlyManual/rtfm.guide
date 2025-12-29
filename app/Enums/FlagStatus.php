<?php

namespace App\Enums;

enum FlagStatus: string
{
    case Pending = 'pending';
    case Reviewed = 'reviewed';
    case Resolved = 'resolved';
    case Dismissed = 'dismissed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending Review',
            self::Reviewed => 'Reviewed',
            self::Resolved => 'Resolved',
            self::Dismissed => 'Dismissed',
        };
    }
}
