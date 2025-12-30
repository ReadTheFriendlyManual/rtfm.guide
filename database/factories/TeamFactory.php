<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'plan' => fake()->randomElement(['free', 'paid']),
            'logo' => fake()->boolean(30) ? fake()->imageUrl(200, 200, 'business') : null,
            'brand_colors' => fake()->boolean(50) ? [
                'primary' => fake()->hexColor(),
                'secondary' => fake()->hexColor(),
            ] : null,
            'profanity_filter_enabled' => fake()->boolean(70),
        ];
    }
}
