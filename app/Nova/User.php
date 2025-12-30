<?php

namespace App\Nova;

use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    public static $model = \App\Models\User::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'email', 'github_username', 'gitlab_username',
    ];

    public static function label(): string
    {
        return 'Users';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Avatar::make('Avatar')
                ->disk('public')
                ->prunable()
                ->nullable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:255')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            DateTime::make('Email Verified At')
                ->exceptOnForms()
                ->sortable(),

            Text::make('GitHub Username')
                ->nullable()
                ->hideFromIndex(),

            Text::make('GitLab Username')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Bio')
                ->nullable()
                ->hideFromIndex()
                ->rows(3),

            Number::make('Reputation Points')
                ->default(0)
                ->sortable()
                ->min(0),

            Select::make('Trust Level')
                ->options([
                    'new' => 'New User',
                    'basic' => 'Basic',
                    'member' => 'Member',
                    'regular' => 'Regular',
                    'leader' => 'Leader',
                ])
                ->default('new')
                ->displayUsingLabels()
                ->sortable(),

            Select::make('Preferred Locale')
                ->options([
                    'en' => 'English',
                    'es' => 'Spanish',
                    'fr' => 'French',
                    'de' => 'German',
                ])
                ->default('en')
                ->displayUsingLabels()
                ->hideFromIndex(),

            Boolean::make('Newsletter Subscribed')
                ->sortable(),

            Select::make('Preferred RTFM Mode')
                ->options([
                    'safe' => 'Safe Mode',
                    'nsfw' => 'NSFW Mode',
                ])
                ->default('safe')
                ->displayUsingLabels()
                ->hideFromIndex(),

            Select::make('Preferred Theme')
                ->options([
                    'light' => 'Light',
                    'dark' => 'Dark',
                    'auto' => 'Auto',
                ])
                ->default('auto')
                ->displayUsingLabels()
                ->hideFromIndex(),

            DateTime::make('Created At')
                ->exceptOnForms()
                ->sortable(),

            DateTime::make('Updated At')
                ->exceptOnForms()
                ->hideFromIndex(),

            HasMany::make('Guides'),
            HasMany::make('Comments'),
            HasMany::make('Reactions'),
            HasMany::make('Saved Guides', 'savedGuides', SavedGuide::class),
            HasMany::make('Content Flags', 'contentFlags', ContentFlag::class),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [
            new Metrics\TotalUsers,
        ];
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
