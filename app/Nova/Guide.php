<?php

namespace App\Nova;

use App\Enums\GuideDifficulty;
use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Guide extends Resource
{
    public static $model = \App\Models\Guide::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'slug', 'tldr',
    ];

    public static function label(): string
    {
        return 'Guides';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User', 'user', User::class)
                ->sortable()
                ->searchable()
                ->withoutTrashed(),

            BelongsTo::make('Team', 'team', Team::class)
                ->nullable()
                ->sortable()
                ->searchable(),

            BelongsTo::make('Category', 'category', Category::class)
                ->sortable()
                ->searchable()
                ->rules('required'),

            BelongsTo::make('Template', 'template', GuideTemplate::class)
                ->nullable()
                ->hideFromIndex(),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Slug')
                ->sortable()
                ->rules('required', 'max:255')
                ->creationRules('unique:guides,slug')
                ->updateRules('unique:guides,slug,{{resourceId}}')
                ->hideFromIndex(),

            Textarea::make('TLDR')
                ->rules('required')
                ->rows(3)
                ->help('A brief summary of this guide'),

            Textarea::make('TLDR (NSFW)', 'tldr_nsfw')
                ->nullable()
                ->rows(3)
                ->hideFromIndex()
                ->help('NSFW version of the summary'),

            Markdown::make('Content')
                ->rules('required')
                ->hideFromIndex()
                ->help('The main content of the guide'),

            Markdown::make('Content (NSFW)', 'content_nsfw')
                ->nullable()
                ->hideFromIndex()
                ->help('NSFW version of the content'),

            Select::make('Difficulty')
                ->options(GuideDifficulty::class)
                ->displayUsingLabels()
                ->sortable()
                ->rules('required'),

            Number::make('Estimated Minutes')
                ->min(1)
                ->step(1)
                ->nullable()
                ->sortable()
                ->help('How long it takes to complete this guide'),

            Text::make('OS Tags')
                ->hideFromIndex()
                ->help('JSON array of OS tags (e.g., ["linux", "mac", "windows"])'),

            Select::make('Status')
                ->options(GuideStatus::class)
                ->displayUsingLabels()
                ->default(GuideStatus::Draft->value)
                ->sortable()
                ->filterable()
                ->rules('required'),

            Select::make('Visibility')
                ->options(GuideVisibility::class)
                ->displayUsingLabels()
                ->sortable()
                ->filterable()
                ->rules('required'),

            Number::make('View Count')
                ->default(0)
                ->sortable()
                ->exceptOnForms(),

            Number::make('Share Count')
                ->default(0)
                ->sortable()
                ->exceptOnForms()
                ->hideFromIndex(),

            DateTime::make('Published At')
                ->nullable()
                ->sortable()
                ->filterable(),

            DateTime::make('Created At')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Updated At')
                ->exceptOnForms()
                ->hideFromIndex(),

            HasMany::make('Comments'),
            HasMany::make('Reactions'),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [
            new Metrics\TotalGuides,
            new Metrics\GuidesPerDay,
            new Metrics\GuidesByStatus,
            new Metrics\GuidesByDifficulty,
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new Filters\GuideStatusFilter,
            new Filters\GuideDifficultyFilter,
            new Filters\CategoryFilter,
        ];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            new Actions\PublishGuide,
        ];
    }
}
