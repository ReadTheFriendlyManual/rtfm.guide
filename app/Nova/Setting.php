<?php

namespace App\Nova;

use App\Enums\SettingType;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Setting extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Setting>
     */
    public static $model = \App\Models\Setting::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'key';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'key', 'value',
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

            Text::make('Key')
                ->sortable()
                ->readonly()
                ->rules('required', 'max:255', 'unique:settings,key,{{resourceId}}')
                ->help('Unique identifier for this setting'),

            Select::make('Type')
                ->options(collect(SettingType::cases())->mapWithKeys(function ($case) {
                    return [$case->value => ucfirst($case->value)];
                }))
                ->displayUsingLabels()
                ->rules('required')
                ->sortable()
                ->help('Data type for this setting value'),

            // Show different fields based on type
            Boolean::make('Value', 'value')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->showOnDetail(fn () => $this->type === SettingType::Boolean)
                ->resolveUsing(function ($value) {
                    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }),

            Number::make('Value', 'value')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->showOnDetail(fn () => $this->type === SettingType::Integer)
                ->resolveUsing(function ($value) {
                    return (int) $value;
                }),

            Code::make('Value (JSON)', 'value')
                ->json()
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->showOnDetail(fn () => $this->type === SettingType::Json),

            Textarea::make('Value', 'value')
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->showOnDetail(fn () => $this->type === SettingType::Text),

            // Form field for all types
            Textarea::make('Value')
                ->rows(3)
                ->onlyOnForms()
                ->help('Enter value based on selected type. Boolean: 0/1, Integer: number, JSON: valid JSON, Text: any text'),

            Text::make('Display Value', fn () => $this->type->toDisplay($this->value))
                ->onlyOnIndex(),

            DateTime::make('Created At')
                ->onlyOnDetail()
                ->sortable(),

            DateTime::make('Updated At')
                ->onlyOnDetail()
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
