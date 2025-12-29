<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guide_id' => \App\Models\Guide::factory(),
            'user_id' => \App\Models\User::factory(),
            'parent_id' => null,
            'content' => fake()->paragraphs(2, true),
            'is_approved' => false,
        ];
    }
}
