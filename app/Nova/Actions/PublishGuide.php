<?php

namespace App\Nova\Actions;

use App\Enums\GuideStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class PublishGuide extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Publish Guide';

    public function handle(ActionFields $fields, Collection $models): mixed
    {
        foreach ($models as $model) {
            $model->update([
                'status' => GuideStatus::Published,
                'published_at' => now(),
            ]);
        }

        return ActionResponse::message('Guides published successfully!');
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
