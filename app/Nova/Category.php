<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Category>
     */
    public static $model = \App\Models\Category::class;

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

            BelongsTo::make('Parent Category', 'parent', Category::class)
                ->nullable()
                ->searchable(),

            Text::make('Slug')
                ->sortable()
                ->rules('required', 'max:255', 'unique:categories,slug,{{resourceId}}'),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->rows(3)
                ->nullable(),

            Text::make('Icon')
                ->nullable()
                ->help('Icon identifier or emoji'),

            Number::make('Order')
                ->default(0)
                ->sortable()
                ->min(0)
                ->step(1),

            HasMany::make('Subcategories', 'children', Category::class),

            HasMany::make('Guides'),

            BelongsToMany::make('Featured Writers', 'featuredWriters', User::class)
                ->fields(function () {
                    return [
                        Number::make('Order')
                            ->help('Display order on category landing page (lower numbers appear first)')
                            ->default(0)
                            ->min(0)
                            ->step(1)
                            ->rules('required', 'integer', 'min:0'),
                    ];
                })
                ->searchable(),
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
