<?php

namespace Database\Factories;

use App\Models\RtfmMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RtfmMessage>
 */
class RtfmMessageFactory extends Factory
{
    protected $model = RtfmMessage::class;

    public function definition(): array
    {
        $messages = [
            'RTFM!',
            'Did you check the docs?',
            'The manual has all the answers!',
            'Have you tried reading the documentation?',
            'Read the fine manual!',
            'Documentation is your friend!',
            'Check the guides first!',
        ];

        return [
            'message' => fake()->randomElement($messages),
            'is_approved' => fake()->boolean(80),
            'is_nsfw' => fake()->boolean(10),
            'usage_count' => fake()->numberBetween(0, 1000),
        ];
    }
}
