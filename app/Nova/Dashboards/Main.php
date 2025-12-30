<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\GuidesByCategory;
use App\Nova\Metrics\GuidesByDifficulty;
use App\Nova\Metrics\GuidesByStatus;
use App\Nova\Metrics\GuidesPerDay;
use App\Nova\Metrics\TotalGuides;
use App\Nova\Metrics\TotalUsers;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(): array
    {
        return [
            TotalUsers::make(),
            TotalGuides::make(),
            GuidesPerDay::make()->width('full'),
            GuidesByStatus::make(),
            GuidesByDifficulty::make(),
            GuidesByCategory::make(),
        ];
    }
}
