<?php

namespace App\Nova\Metrics;

use App\Models\Guide;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalGuides extends Value
{
    public function calculate(NovaRequest $request): mixed
    {
        return $this->count($request, Guide::class);
    }

    public function ranges(): array
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            90 => '90 Days',
            365 => '365 Days',
            'TODAY' => 'Today',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
        ];
    }

    public function cacheFor(): ?\DateTimeInterface
    {
        return now()->addMinutes(5);
    }
}
