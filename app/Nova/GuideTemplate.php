<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class GuideTemplate extends Resource
{
    public static $model = \App\Models\GuideTemplate::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'slug', 'description',
    ];

    public static function label(): string
    {
        return 'Guide Templates';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Slug')
                ->sortable()
                ->rules('required', 'max:255')
                ->creationRules('unique:guide_templates,slug')
                ->updateRules('unique:guide_templates,slug,{{resourceId}}'),

            Textarea::make('Description')
                ->nullable()
                ->rows(3)
                ->help('Describe what this template is for'),

            Textarea::make('Structure')
                ->rules('required')
                ->rows(8)
                ->help('JSON structure defining template sections and fields')
                ->hideFromIndex(),

            BelongsTo::make('Category', 'category', Category::class)
                ->sortable()
                ->searchable()
                ->rules('required'),

            Boolean::make('Is Official')
                ->sortable()
                ->filterable()
                ->help('Mark as an official template'),

            BelongsTo::make('Creator', 'creator', User::class)
                ->nullable()
                ->searchable()
                ->hideFromIndex(),

            Number::make('Usage Count')
                ->default(0)
                ->sortable()
                ->exceptOnForms()
                ->help('Number of guides created from this template'),

            DateTime::make('Created At')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Updated At')
                ->exceptOnForms()
                ->hideFromIndex(),

            HasMany::make('Guides'),
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
