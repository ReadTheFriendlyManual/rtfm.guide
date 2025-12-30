<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ApproveRtfmMessage extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Approve Message';

    public function handle(ActionFields $fields, Collection $models): mixed
    {
        foreach ($models as $model) {
            $model->update(['is_approved' => true]);
        }

        return ActionResponse::message('Messages approved successfully!');
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
