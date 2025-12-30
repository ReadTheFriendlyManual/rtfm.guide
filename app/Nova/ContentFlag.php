<?php

namespace App\Nova;

use App\Enums\FlagReason;
use App\Enums\FlagStatus;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class ContentFlag extends Resource
{
    public static $model = \App\Models\ContentFlag::class;

    public static $title = 'id';

    public static $search = [
        'id', 'description',
    ];

    public static function label(): string
    {
        return 'Content Flags';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Reporter', 'user', User::class)
                ->sortable()
                ->searchable()
                ->rules('required'),

            MorphTo::make('Flaggable')
                ->types([
                    Guide::class,
                    Comment::class,
                ])
                ->searchable()
                ->rules('required'),

            Select::make('Reason')
                ->options(FlagReason::class)
                ->displayUsingLabels()
                ->sortable()
                ->filterable()
                ->rules('required'),

            Textarea::make('Description')
                ->nullable()
                ->rows(3)
                ->help('Additional details about why this content was flagged'),

            Select::make('Status')
                ->options(FlagStatus::class)
                ->displayUsingLabels()
                ->default(FlagStatus::Pending->value)
                ->sortable()
                ->filterable()
                ->rules('required'),

            BelongsTo::make('Reviewed By', 'reviewer', User::class)
                ->nullable()
                ->searchable()
                ->hideFromIndex(),

            DateTime::make('Reviewed At')
                ->nullable()
                ->sortable()
                ->hideFromIndex(),

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
            new Actions\ReviewFlag,
        ];
    }
}
