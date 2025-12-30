<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Http\Requests\NovaRequest;

class Comment extends Resource
{
    public static $model = \App\Models\Comment::class;

    public static $title = 'id';

    public static $search = [
        'id', 'content',
    ];

    public static function label(): string
    {
        return 'Comments';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Guide', 'guide', Guide::class)
                ->sortable()
                ->searchable()
                ->rules('required'),

            BelongsTo::make('User', 'user', User::class)
                ->sortable()
                ->searchable()
                ->rules('required'),

            BelongsTo::make('Parent Comment', 'parent', Comment::class)
                ->nullable()
                ->searchable()
                ->hideFromIndex(),

            Markdown::make('Content')
                ->rules('required')
                ->alwaysShow(),

            Boolean::make('Is Approved')
                ->sortable()
                ->filterable()
                ->help('Approve this comment to make it visible'),

            DateTime::make('Deleted At')
                ->exceptOnForms()
                ->nullable()
                ->sortable()
                ->hideFromIndex(),

            DateTime::make('Created At')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Updated At')
                ->exceptOnForms()
                ->hideFromIndex(),

            HasMany::make('Replies', 'replies', Comment::class),
            HasMany::make('Flags', 'flags', ContentFlag::class),
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
        return [
            new Actions\ApproveComment,
        ];
    }
}
