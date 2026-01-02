<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_user_id',
        'plan',
        'logo',
        'brand_colors',
        'profanity_filter_enabled',
    ];

    protected function casts(): array
    {
        return [
            'brand_colors' => 'array',
            'profanity_filter_enabled' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function guides(): HasMany
    {
        return $this->hasMany(Guide::class);
    }
}
