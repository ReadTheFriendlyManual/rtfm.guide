<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RtfmMessage extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'is_approved',
        'is_nsfw',
        'usage_count',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'is_nsfw' => 'boolean',
        ];
    }
}
