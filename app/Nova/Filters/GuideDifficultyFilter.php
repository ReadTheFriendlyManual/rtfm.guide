<?php

namespace App\Nova\Filters;

use App\Enums\GuideDifficulty;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class GuideDifficultyFilter extends Filter
{
    public $name = 'Difficulty';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->where('difficulty', $value);
    }

    public function options(NovaRequest $request): array
    {
        return collect(GuideDifficulty::cases())
            ->mapWithKeys(fn ($difficulty) => [$difficulty->label() => $difficulty->value])
            ->toArray();
    }
}
