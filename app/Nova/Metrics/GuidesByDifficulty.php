<?php

namespace App\Nova\Metrics;

use App\Enums\GuideDifficulty;
use App\Models\Guide;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class GuidesByDifficulty extends Partition
{
    public function calculate(NovaRequest $request): mixed
    {
        return $this->count($request, Guide::class, 'difficulty')
            ->label(function ($value) {
                return GuideDifficulty::from($value)->label();
            });
    }

    public function cacheFor(): ?\DateTimeInterface
    {
        return now()->addMinutes(5);
    }
}
