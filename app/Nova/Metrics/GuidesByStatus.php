<?php

namespace App\Nova\Metrics;

use App\Enums\GuideStatus;
use App\Models\Guide;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class GuidesByStatus extends Partition
{
    public function calculate(NovaRequest $request): mixed
    {
        return $this->count($request, Guide::class, 'status')
            ->label(function ($value) {
                return GuideStatus::from($value)->label();
            });
    }

    public function cacheFor(): ?\DateTimeInterface
    {
        return now()->addMinutes(5);
    }
}
