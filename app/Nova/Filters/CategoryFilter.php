<?php

namespace App\Nova\Filters;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class CategoryFilter extends Filter
{
    public $name = 'Category';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->where('category_id', $value);
    }

    public function options(NovaRequest $request): array
    {
        return Category::pluck('name', 'id')->toArray();
    }
}
