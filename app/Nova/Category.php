<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
    public static $model = \App\Models\Category::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'slug', 'description',
    ];

    public static function label(): string
    {
        return 'Categories';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Parent Category', 'parent', Category::class)
                ->nullable()
                ->searchable()
                ->hideFromIndex(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Slug')
                ->sortable()
                ->rules('required', 'max:255')
                ->creationRules('unique:categories,slug')
                ->updateRules('unique:categories,slug,{{resourceId}}'),

            Textarea::make('Description')
                ->nullable()
                ->rows(3)
                ->hideFromIndex(),

            Text::make('Icon')
                ->nullable()
                ->help('Icon identifier or emoji for this category')
                ->hideFromIndex(),

            Number::make('Order')
                ->default(0)
                ->sortable()
                ->help('Display order of this category'),

            DateTime::make('Created At')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Updated At')
                ->exceptOnForms()
                ->hideFromIndex(),

            HasMany::make('Child Categories', 'children', Category::class),
            HasMany::make('Guides'),
            HasMany::make('Guide Templates', 'guideTemplates', GuideTemplate::class),
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
        return [];
    }
}
