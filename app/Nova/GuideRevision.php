<?php

namespace App\Nova;

use App\Enums\GuideDifficulty;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class GuideRevision extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\GuideRevision>
     */
    public static $model = \App\Models\GuideRevision::class;

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
        'id', 'title', 'tldr',
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

            BelongsTo::make('Guide')
                ->searchable()
                ->sortable(),

            BelongsTo::make('Submitted By', 'user', User::class)
                ->searchable()
                ->sortable(),

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

            Panel::make('Review Status', [
                Select::make('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->displayUsingLabels()
                    ->sortable(),

                BelongsTo::make('Approved By', 'approver', User::class)
                    ->nullable()
                    ->searchable()
                    ->hideWhenCreating(),

                DateTime::make('Approved At')
                    ->nullable()
                    ->hideWhenCreating()
                    ->sortable(),

                Textarea::make('Rejection Reason')
                    ->rows(3)
                    ->nullable()
                    ->hideFromIndex(),
            ]),

            DateTime::make('Created At')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Updated At')
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
