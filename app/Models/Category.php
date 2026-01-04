<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Category extends Model
{
    use HasFactory, HasSEO;

    protected $with = ['flags'];

    protected $fillable = [
        'parent_id',
        'slug',
        'name',
        'description',
        'icon',
        'order',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function guides(): HasMany
    {
        return $this->hasMany(Guide::class);
    }

    public function featuredGuides(): HasMany
    {
        return $this->hasMany(Guide::class)->where('is_featured', true);
    }

    public function featuredWriters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'category_user')
            ->withTimestamps()
            ->withPivot('order')
            ->orderBy('order');
    }

    public function getDynamicSEOData(): SEOData
    {
        $guideCount = $this->guides_count
            ?? ($this->relationLoaded('guides') ? $this->guides->count() : $this->guides()->count());

        return new SEOData(
            title: $this->name,
            description: $this->description ?? "Browse {$guideCount} guides in {$this->name} category on RTFM.",
            image: route('og-images.category', $this),
            url: route('categories.show', $this),
        );
    }

    public function flags(): BelongsToMany
    {
        return $this->belongsToMany(Flag::class)
            ->withTimestamps()
            ->withPivot('notes')
            ->orderBy('order');
    }
}
