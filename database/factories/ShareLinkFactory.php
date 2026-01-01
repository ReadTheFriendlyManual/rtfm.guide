<?php

namespace Database\Factories;

use App\Models\Guide;
use App\Models\ShareLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShareLink>
 */
class ShareLinkFactory extends Factory
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
            'token' => ShareLink::generateToken(),
            'mode' => $this->faker->randomElement(['sfw', 'nsfw']),
            'visit_count' => 0,
        ];
    }

    public function sfw(): static
    {
        return $this->state(fn (array $attributes) => [
            'mode' => 'sfw',
        ]);
    }

    public function nsfw(): static
    {
        return $this->state(fn (array $attributes) => [
            'mode' => 'nsfw',
        ]);
    }
}
