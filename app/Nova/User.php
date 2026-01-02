<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class User extends Resource
{
    use PasswordValidationRules;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

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
        'name', 'email', 'github_username', 'gitlab_username',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel|\Laravel\Nova\ResourceTool|\Illuminate\Http\Resources\MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Gravatar::make()->maxWidth(50),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            DateTime::make('Email Verified At')
                ->sortable()
                ->hideWhenCreating()
                ->readonly()
                ->onlyOnDetail(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules($this->passwordRules())
                ->updateRules($this->optionalPasswordRules()),

            Panel::make('Profile', [
                Image::make('Avatar')
                    ->disk('public')
                    ->path('avatars')
                    ->prunable()
                    ->maxWidth(100),

                Textarea::make('Bio')
                    ->rows(3)
                    ->rules('nullable', 'max:500'),

                Text::make('GitHub Username')
                    ->rules('nullable', 'max:255'),

                Text::make('GitLab Username')
                    ->rules('nullable', 'max:255'),
            ]),

            Panel::make('OAuth Authentication', [
                Text::make('OAuth Provider')
                    ->readonly()
                    ->hideWhenCreating(),

                Text::make('OAuth ID')
                    ->readonly()
                    ->hideWhenCreating(),
            ]),

            Panel::make('Gamification & Status', [
                Number::make('Reputation Points')
                    ->default(0)
                    ->min(0)
                    ->step(1)
                    ->sortable(),

                Select::make('Trust Level')
                    ->options([
                        0 => 'New User',
                        1 => 'Basic User',
                        2 => 'Member',
                        3 => 'Regular',
                        4 => 'Leader',
                    ])
                    ->default(0)
                    ->displayUsingLabels()
                    ->sortable(),
            ]),

            Panel::make('Preferences', [
                Select::make('Preferred Locale')
                    ->options([
                        'en' => 'English',
                        'es' => 'Spanish',
                        'fr' => 'French',
                        'de' => 'German',
                    ])
                    ->default('en')
                    ->displayUsingLabels(),

                Select::make('Preferred Theme')
                    ->options([
                        'light' => 'Light',
                        'dark' => 'Dark',
                        'auto' => 'Auto',
                    ])
                    ->default('auto')
                    ->displayUsingLabels(),

                Select::make('Preferred RTFM Mode')
                    ->options([
                        'sfw' => 'Safe for Work',
                        'nsfw' => 'Not Safe for Work',
                    ])
                    ->default('sfw')
                    ->displayUsingLabels(),

                Boolean::make('Newsletter Subscribed')
                    ->default(false),
            ]),

            Panel::make('Permissions', [
                Boolean::make('Trusted Editor')
                    ->default(false)
                    ->sortable(),

                Boolean::make('Is Admin')
                    ->default(false)
                    ->sortable(),
            ]),
        ];
    }

    /**
     * Get the cards available for the request.
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
