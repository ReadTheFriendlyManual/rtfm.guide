<?php

namespace App\Models;

use App\Enums\GuideDifficulty;
use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
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
        'tldr_nsfw',
        'content',
        'content_nsfw',
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
            'difficulty' => GuideDifficulty::class,
            'status' => GuideStatus::class,
            'visibility' => GuideVisibility::class,
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

    public function revisions(): HasMany
    {
        return $this->hasMany(GuideRevision::class);
    }

    public function shareLinks(): HasMany
    {
        return $this->hasMany(ShareLink::class);
    }

    public function pendingRevision(): ?GuideRevision
    {
        return $this->revisions()->pending()->latest()->first();
    }

    public function hasPendingRevision(): bool
    {
        return $this->revisions()->pending()->exists();
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'tldr' => $this->tldr,
            'content' => strip_tags($this->content), // Remove markdown tags for better search
            'category' => $this->category->name,
            'category_slug' => $this->category->slug,
            'difficulty' => $this->difficulty,
            'os_tags' => $this->os_tags,
            'view_count' => $this->view_count,
            'published_at' => $this->published_at?->timestamp,
        ];
    }

    public function searchableAs(): string
    {
        return 'guides_index';
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === GuideStatus::Published && $this->visibility === GuideVisibility::Public;
    }
}
