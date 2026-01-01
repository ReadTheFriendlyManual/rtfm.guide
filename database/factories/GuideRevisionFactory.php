<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GuideRevision>
 */
class GuideRevisionFactory extends Factory
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
            'title' => fake()->sentence(),
            'tldr' => fake()->paragraph(),
            'tldr_nsfw' => null,
            'content' => fake()->paragraphs(3, true),
            'content_nsfw' => null,
            'category_id' => \App\Models\Category::factory(),
            'difficulty' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'estimated_minutes' => fake()->numberBetween(5, 60),
            'os_tags' => fake()->randomElements(['Linux', 'macOS', 'Windows', 'Ubuntu'], 2),
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => null,
        ];
    }
}
