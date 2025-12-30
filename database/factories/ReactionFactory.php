<?php

namespace Database\Factories;

use App\Enums\ReactionType;
use App\Models\Reaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reaction>
 */
class ReactionFactory extends Factory
{
    protected $model = Reaction::class;

    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(ReactionType::cases()),
        ];
    }
}
