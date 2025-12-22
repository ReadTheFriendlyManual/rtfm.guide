<?php

namespace Database\Factories;

use App\Models\Guide;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reaction>
 */
class ReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guide_id' => Guide::factory(),
            'user_id' => User::factory(),
            'type' => fake()->randomElement(Reaction::allowedTypes()),
        ];
    }
}
