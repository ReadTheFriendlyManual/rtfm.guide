<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuideTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'structure',
        'category_id',
        'is_official',
        'created_by_user_id',
        'usage_count',
    ];

    protected function casts(): array
    {
        return [
            'structure' => 'array',
            'is_official' => 'boolean',
            'usage_count' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function guides(): HasMany
    {
        return $this->hasMany(Guide::class, 'template_id');
    }
}
