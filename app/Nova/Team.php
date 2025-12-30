<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Team extends Resource
{
    public static $model = \App\Models\Team::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'slug',
    ];

    public static function label(): string
    {
        return 'Teams';
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
                ->creationRules('unique:teams,slug')
                ->updateRules('unique:teams,slug,{{resourceId}}'),

            BelongsTo::make('Owner', 'owner', User::class)
                ->sortable()
                ->searchable()
                ->rules('required'),

            Select::make('Plan')
                ->options([
                    'free' => 'Free',
                    'pro' => 'Pro',
                    'enterprise' => 'Enterprise',
                ])
                ->default('free')
                ->displayUsingLabels()
                ->sortable(),

            Image::make('Logo')
                ->disk('public')
                ->prunable()
                ->nullable()
                ->hideFromIndex(),

            KeyValue::make('Brand Colors')
                ->keyLabel('Color Name')
                ->valueLabel('Hex Code')
                ->nullable()
                ->hideFromIndex()
                ->help('Define custom brand colors for this team'),

            Boolean::make('Profanity Filter Enabled')
                ->sortable()
                ->help('Enable profanity filtering for this team'),

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
