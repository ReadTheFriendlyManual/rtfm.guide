<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(nb: 2, asText: true);

        return [
            'parent_id' => null,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'name' => Str::title($name),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement([
                'cpu',
                'server',
                'command-line',
                'book-open',
            ]),
            'display_order' => fake()->numberBetween(0, 50),
        ];
    }
}
