<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

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
            ->orderBy('order');
    }
}
