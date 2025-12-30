<?php

namespace App\Nova\Metrics;

use App\Models\Guide;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class GuidesPerDay extends Trend
{
    public function calculate(NovaRequest $request): mixed
    {
        return $this->countByDays($request, Guide::class);
    }

    public function ranges(): array
    {
        return [
            7 => '7 Days',
            14 => '14 Days',
            30 => '30 Days',
            60 => '60 Days',
            90 => '90 Days',
        ];
    }

    public function cacheFor(): ?\DateTimeInterface
    {
        return now()->addMinutes(5);
    }
}
