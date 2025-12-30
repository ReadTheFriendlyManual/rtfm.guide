<?php

namespace Database\Factories;

use App\Enums\FlagReason;
use App\Enums\FlagStatus;
use App\Models\ContentFlag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContentFlag>
 */
class ContentFlagFactory extends Factory
{
    protected $model = ContentFlag::class;

    public function definition(): array
    {
        return [
            'reason' => fake()->randomElement(FlagReason::cases()),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(FlagStatus::cases()),
        ];
    }
}
