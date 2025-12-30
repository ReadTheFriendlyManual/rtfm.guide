<?php

namespace App\Nova\Filters;

use App\Enums\GuideStatus;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class GuideStatusFilter extends Filter
{
    public $name = 'Status';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->where('status', $value);
    }

    public function options(NovaRequest $request): array
    {
        return collect(GuideStatus::cases())
            ->mapWithKeys(fn ($status) => [$status->label() => $status->value])
            ->toArray();
    }
}
