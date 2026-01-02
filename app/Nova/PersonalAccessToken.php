<?php

namespace App\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;

class PersonalAccessToken extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Laravel\Sanctum\PersonalAccessToken>
     */
    public static $model = \Laravel\Sanctum\PersonalAccessToken::class;

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
        'id', 'name',
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

            \Laravel\Nova\Fields\MorphTo::make('Tokenable')
                ->types([
                    User::class,
                ]),

            \Laravel\Nova\Fields\Text::make('Name')
                ->sortable()
                ->rules('required'),

            \Laravel\Nova\Fields\Text::make('Token')
                ->onlyOnForms()
                ->readonly()
                ->help('Token is only visible once upon creation'),

            \Laravel\Nova\Fields\Textarea::make('Abilities')
                ->rows(3)
                ->nullable()
                ->hideFromIndex(),

            \Laravel\Nova\Fields\DateTime::make('Last Used At')
                ->nullable()
                ->readonly()
                ->sortable(),

            \Laravel\Nova\Fields\DateTime::make('Expires At')
                ->nullable()
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
