<?php

namespace App\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;

class GuideTemplate extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\GuideTemplate>
     */
    public static $model = \App\Models\GuideTemplate::class;

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
                ->rules('required', 'max:255', 'unique:guide_templates,slug,{{resourceId}}'),

            \Laravel\Nova\Fields\Textarea::make('Description')
                ->rows(3)
                ->rules('required'),

            \Laravel\Nova\Fields\Code::make('Structure')
                ->json()
                ->rules('required')
                ->hideFromIndex(),

            \Laravel\Nova\Fields\BelongsTo::make('Category')
                ->searchable()
                ->sortable(),

            \Laravel\Nova\Fields\Boolean::make('Is Official')
                ->default(false)
                ->sortable(),

            \Laravel\Nova\Fields\BelongsTo::make('Created By', 'createdBy', User::class)
                ->searchable()
                ->sortable(),

            \Laravel\Nova\Fields\Number::make('Usage Count')
                ->default(0)
                ->readonly()
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
