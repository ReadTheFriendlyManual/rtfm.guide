<?php

namespace App\Nova\Actions;

use App\Enums\FlagStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ReviewFlag extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Review Flag';

    public function handle(ActionFields $fields, Collection $models): mixed
    {
        foreach ($models as $model) {
            $model->update([
                'status' => $fields->status,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        }

        return ActionResponse::message('Flags reviewed successfully!');
    }

    public function fields(NovaRequest $request): array
    {
        return [
            Select::make('Status')
                ->options([
                    FlagStatus::Reviewed->value => FlagStatus::Reviewed->label(),
                    FlagStatus::Resolved->value => FlagStatus::Resolved->label(),
                    FlagStatus::Dismissed->value => FlagStatus::Dismissed->label(),
                ])
                ->rules('required'),
        ];
    }
}
