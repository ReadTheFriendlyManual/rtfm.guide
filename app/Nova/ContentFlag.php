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
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ContentFlag>
     */
    public static $model = \App\Models\ContentFlag::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'description',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Reported By', 'user', User::class)
                ->searchable()
                ->sortable(),

            MorphTo::make('Flaggable')
                ->types([
                    Guide::class,
                    Comment::class,
                ]),

            Select::make('Reason')
                ->options(collect(FlagReason::cases())->mapWithKeys(function ($case) {
                    return [$case->value => $case->label()];
                }))
                ->displayUsingLabels()
                ->rules('required')
                ->sortable(),

            Textarea::make('Description')
                ->rows(3)
                ->nullable(),

            Select::make('Status')
                ->options(collect(FlagStatus::cases())->mapWithKeys(function ($case) {
                    return [$case->value => $case->label()];
                }))
                ->default(FlagStatus::Pending->value)
                ->displayUsingLabels()
                ->sortable(),

            BelongsTo::make('Reviewed By', 'reviewer', User::class)
                ->nullable()
                ->searchable()
                ->hideWhenCreating(),

            DateTime::make('Reviewed At')
                ->nullable()
                ->hideWhenCreating()
                ->sortable(),

            DateTime::make('Created At')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Updated At')
                ->exceptOnForms()
                ->sortable(),
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
