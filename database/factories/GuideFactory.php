<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guide>
 */
class GuideFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = \App\Models\Guide::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'slug' => $this->faker->unique()->slug(),
            'title' => $this->faker->sentence(),
            'tldr' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'category_id' => \App\Models\Category::factory(),
            'difficulty' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'estimated_minutes' => $this->faker->numberBetween(5, 120),
            'os_tags' => $this->faker->randomElements(['windows', 'macos', 'linux'], $this->faker->numberBetween(0, 3)),
            'status' => 'published',
            'visibility' => 'public',
            'view_count' => $this->faker->numberBetween(0, 1000),
            'published_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'visibility' => 'private',
        ]);
    }
}
