<?php

namespace App\Nova;

use App\Enums\GuideDifficulty;
use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Guide extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Guide>
     */
    public static $model = \App\Models\Guide::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'slug', 'tldr',
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

            BelongsTo::make('Author', 'user', User::class)
                ->searchable()
                ->sortable(),

            BelongsTo::make('Team')
                ->nullable()
                ->searchable()
                ->hideFromIndex(),

            Text::make('Slug')
                ->sortable()
                ->rules('required', 'max:255', 'unique:guides,slug,{{resourceId}}'),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('TLDR')
                ->rows(3)
                ->rules('required'),

            Textarea::make('TLDR NSFW')
                ->rows(3)
                ->nullable()
                ->hideFromIndex(),

            Markdown::make('Content')
                ->rules('required')
                ->hideFromIndex(),

            Markdown::make('Content NSFW')
                ->nullable()
                ->hideFromIndex(),

            Panel::make('Categorization & Difficulty', [
                BelongsTo::make('Category')
                    ->searchable()
                    ->sortable(),

                Select::make('Difficulty')
                    ->options(collect(GuideDifficulty::cases())->mapWithKeys(function ($case) {
                        return [$case->value => $case->label()];
                    }))
                    ->displayUsingLabels()
                    ->rules('required')
                    ->sortable(),

                Number::make('Estimated Minutes')
                    ->min(0)
                    ->step(1)
                    ->nullable()
                    ->hideFromIndex(),

                KeyValue::make('OS Tags')
                    ->nullable()
                    ->hideFromIndex(),
            ]),

            Panel::make('Publication & Visibility', [
                Select::make('Status')
                    ->options(collect(GuideStatus::cases())->mapWithKeys(function ($case) {
                        return [$case->value => $case->label()];
                    }))
                    ->default(GuideStatus::Draft->value)
                    ->displayUsingLabels()
                    ->sortable(),

                Select::make('Visibility')
                    ->options(collect(GuideVisibility::cases())->mapWithKeys(function ($case) {
                        return [$case->value => $case->label()];
                    }))
                    ->default(GuideVisibility::Public->value)
                    ->displayUsingLabels()
                    ->sortable(),

                Boolean::make('Featured', 'is_featured')
                    ->help('Feature this guide on its category landing page')
                    ->sortable(),

                DateTime::make('Published At')
                    ->nullable()
                    ->sortable(),
            ]),

            Panel::make('Template & Statistics', [
                BelongsTo::make('Template', 'template', GuideTemplate::class)
                    ->nullable()
                    ->searchable()
                    ->hideFromIndex(),

                Number::make('View Count')
                    ->default(0)
                    ->readonly()
                    ->sortable(),

                Number::make('Share Count')
                    ->default(0)
                    ->readonly()
                    ->sortable(),
            ]),

            DateTime::make('Created At')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Updated At')
                ->exceptOnForms()
                ->sortable(),

            HasMany::make('Comments'),
            HasMany::make('Reactions'),
            HasMany::make('Revisions', 'revisions', GuideRevision::class),

            BelongsToMany::make('Moderation Flags', 'flags', Flag::class)
                ->fields(function () {
                    return [
                        Textarea::make('Notes')
                            ->help('Optional notes about this flag (e.g., "Applies to nginx v1.x only")')
                            ->nullable(),
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
