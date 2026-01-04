<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Flag extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Flag>
     */
    public static $model = \App\Models\Flag::class;

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
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Slug')
                ->sortable()
                ->rules('required', 'max:255', 'unique:flags,slug,{{resourceId}}'),

            Textarea::make('Description')
                ->rows(3)
                ->nullable()
                ->help('Brief description of what this flag indicates'),

            Text::make('Color')
                ->default('red')
                ->rules('required', 'max:255')
                ->help('Color for the warning banner (e.g., red, yellow, blue)'),

            Text::make('Icon')
                ->nullable()
                ->help('Icon identifier or emoji'),

            Number::make('Order')
                ->default(0)
                ->sortable()
                ->min(0)
                ->step(1)
                ->help('Display order (lower numbers appear first)'),

            BelongsToMany::make('Guides')
                ->fields(function () {
                    return [
                        Textarea::make('Notes')
                            ->help('Optional notes about this flag (e.g., "Applies to nginx v1.x only")')
                            ->nullable(),
                    ];
                }),

            BelongsToMany::make('Categories')
                ->fields(function () {
                    return [
                        Textarea::make('Notes')
                            ->help('Optional notes about this flag (e.g., "Applies to all guides in this category")')
                            ->nullable(),
                    ];
                }),
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
