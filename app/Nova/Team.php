<?php

namespace App\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;

class Team extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Team>
     */
    public static $model = \App\Models\Team::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'slug',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            \Laravel\Nova\Fields\ID::make()->sortable(),

            \Laravel\Nova\Fields\Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            \Laravel\Nova\Fields\Text::make('Slug')
                ->sortable()
                ->rules('required', 'max:255', 'unique:teams,slug,{{resourceId}}'),

            \Laravel\Nova\Fields\BelongsTo::make('Owner', 'owner', User::class)
                ->searchable()
                ->sortable(),

            \Laravel\Nova\Fields\Select::make('Plan')
                ->options([
                    'free' => 'Free',
                    'paid' => 'Paid',
                ])
                ->default('free')
                ->displayUsingLabels()
                ->sortable(),

            \Laravel\Nova\Fields\Image::make('Logo')
                ->disk('public')
                ->path('team-logos')
                ->prunable()
                ->nullable()
                ->maxWidth(100),

            \Laravel\Nova\Fields\Code::make('Brand Colors')
                ->json()
                ->nullable()
                ->hideFromIndex(),

            \Laravel\Nova\Fields\Boolean::make('Profanity Filter Enabled')
                ->default(false)
                ->sortable(),

            \Laravel\Nova\Fields\DateTime::make('Created At')
                ->exceptOnForms()
                ->sortable(),

            \Laravel\Nova\Fields\DateTime::make('Updated At')
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
