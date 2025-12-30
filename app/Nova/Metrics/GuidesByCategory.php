<?php

namespace App\Nova\Metrics;

use App\Models\Category;
use App\Models\Guide;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class GuidesByCategory extends Partition
{
    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): PartitionResult
    {
        return $this->count($request, Guide::class, 'category_id')
            ->label(function ($categoryId) {
                $category = Category::find($categoryId);

                return $category?->name ?? 'Uncategorized';
            });
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     */
    public function cacheFor(): ?\DateTimeInterface
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     */
    public function uriKey(): string
    {
        return 'guides-by-category';
    }
}
