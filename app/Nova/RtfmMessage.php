<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class RtfmMessage extends Resource
{
    public static $model = \App\Models\RtfmMessage::class;

    public static $title = 'message';

    public static $search = [
        'id', 'message',
    ];

    public static function label(): string
    {
        return 'RTFM Messages';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User', 'user', User::class)
                ->sortable()
                ->searchable()
                ->nullable()
                ->help('User who submitted this message'),

            Textarea::make('Message')
                ->rules('required')
                ->rows(3)
                ->alwaysShow(),

            Boolean::make('Is Approved')
                ->sortable()
                ->filterable()
                ->help('Approve this message to make it available'),

            Boolean::make('Is NSFW')
                ->sortable()
                ->filterable()
                ->help('Mark if this message contains NSFW content'),

            Number::make('Usage Count')
                ->default(0)
                ->sortable()
                ->exceptOnForms()
                ->help('Number of times this message has been used'),

            DateTime::make('Created At')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Updated At')
                ->exceptOnForms()
                ->hideFromIndex(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            new Actions\ApproveRtfmMessage,
        ];
    }
}
