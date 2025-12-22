<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RtfmMessage>
 */
class RtfmMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'message' => fake()->randomElement([
                'Did you skip the docs again? Thought so.',
                'RTFM: Required To Fix Mistakes.',
                'Manuals are friends, not optional.',
                'Docs exist for a reason—read them.',
            ]),
            'is_approved' => true,
            'usage_count' => fake()->numberBetween(0, 20),
        ];
    }
}
