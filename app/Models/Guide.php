<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Guide extends Model
{
    /** @use HasFactory<\Database\Factories\GuideFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Guide $guide): void {
            if (blank($guide->slug)) {
                $guide->slug = Str::slug($guide->title).'-'.Str::random(6);
            }
        });
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'slug',
        'title',
        'tldr',
        'content',
        'difficulty',
        'estimated_minutes',
        'os_tags',
        'status',
        'visibility',
        'view_count',
        'share_count',
        'prerequisites',
        'troubleshooting',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'os_tags' => 'array',
            'prerequisites' => 'array',
            'troubleshooting' => 'array',
            'published_at' => 'datetime',
            'view_count' => 'integer',
            'share_count' => 'integer',
            'estimated_minutes' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo<User, Guide>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Category, Guide>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<Reaction>
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    /**
     * @return BelongsToMany<User>
     */
    public function savedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_guides')->withTimestamps();
    }

    /**
     * @return Builder<Guide>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('visibility', 'public');
    }

    /**
     * @return Builder<Guide>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($term): void {
            $builder->where('title', 'like', "%{$term}%")
                ->orWhere('tldr', 'like', "%{$term}%")
                ->orWhereJsonContains('os_tags', $term);
        });
    }

    /**
     * @return Builder<Guide>
     */
    public function scopeForDifficulty(Builder $query, ?string $difficulty): Builder
    {
        if (blank($difficulty)) {
            return $query;
        }

        return $query->where('difficulty', $difficulty);
    }

    /**
     * @return Builder<Guide>
     */
    public function scopeForCategory(Builder $query, ?Category $category): Builder
    {
        if ($category === null) {
            return $query;
        }

        $ids = $category->children()->pluck('id')->push($category->id);

        return $query->whereIn('category_id', $ids);
    }

    /**
     * @return Builder<Guide>
     */
    public function scopeTrending(Builder $query): Builder
    {
        return $query->orderByDesc('view_count')
            ->orderByDesc('share_count')
            ->orderByDesc('published_at');
    }

    public function contentAsHtml(): HtmlString
    {
        return new HtmlString(Str::markdown($this->content));
    }

    /**
     * @return Collection<Guide>
     */
    public function related(int $limit = 3): Collection
    {
        return self::query()
            ->published()
            ->where('id', '!=', $this->id)
            ->where('category_id', $this->category_id)
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function reactionCount(string $type): int
    {
        if (! in_array($type, Reaction::allowedTypes(), true)) {
            return 0;
        }

        $attribute = "{$type}_reactions_count";

        if (isset($this->{$attribute})) {
            return (int) $this->{$attribute};
        }

        if ($this->relationLoaded('reactions')) {
            return $this->reactions->where('type', $type)->count();
        }

        return $this->reactions()->where('type', $type)->count();
    }

    public function savedCount(): int
    {
        if (isset($this->saved_by_count)) {
            return (int) $this->saved_by_count;
        }

        if ($this->relationLoaded('savedBy')) {
            return $this->savedBy->count();
        }

        return $this->savedBy()->count();
    }
}
