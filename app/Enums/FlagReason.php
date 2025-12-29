<?php

namespace App\Enums;

enum FlagReason: string
{
    case Spam = 'spam';
    case Harassment = 'harassment';
    case OffensiveContent = 'offensive_content';
    case Misinformation = 'misinformation';
    case OffTopic = 'off_topic';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Spam => 'Spam',
            self::Harassment => 'Harassment or Bullying',
            self::OffensiveContent => 'Offensive Content',
            self::Misinformation => 'Misinformation',
            self::OffTopic => 'Off Topic',
            self::Other => 'Other',
        };
    }
}
