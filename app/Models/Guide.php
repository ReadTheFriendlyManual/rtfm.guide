<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Guide extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id',
        'team_id',
        'slug',
        'title',
        'tldr',
        'content',
        'category_id',
        'difficulty',
        'estimated_minutes',
        'os_tags',
        'status',
        'visibility',
        'template_id',
        'view_count',
        'share_count',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'os_tags' => 'array',
            'published_at' => 'datetime',
            'view_count' => 'integer',
            'share_count' => 'integer',
            'estimated_minutes' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'tldr' => $this->tldr,
            'content' => $this->content,
            'category' => $this->category->name,
            'difficulty' => $this->difficulty,
            'status' => $this->status,
        ];
    }
}
