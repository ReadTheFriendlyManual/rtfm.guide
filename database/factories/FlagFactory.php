<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flag>
 */
class FlagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = \App\Models\Flag::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
            'color' => $this->faker->randomElement(['red', 'yellow', 'orange', 'blue', 'purple', 'green']),
            'icon' => $this->faker->randomElement(['⚠️', '⚡', '🔴', '⚙️', '📌', null]),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }

    /**
     * Create a medical advice flag
     */
    public function medicalAdvice(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Medical Advice',
            'slug' => 'medical-advice',
            'description' => 'This content contains medical information. Always consult with a healthcare professional.',
            'color' => 'red',
            'icon' => '⚕️',
        ]);
    }

    /**
     * Create a legal advice flag
     */
    public function legalAdvice(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Legal Advice',
            'slug' => 'legal-advice',
            'description' => 'This content contains legal information. Always consult with a legal professional.',
            'color' => 'red',
            'icon' => '⚖️',
        ]);
    }

    /**
     * Create an outdated flag
     */
    public function outdated(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Outdated',
            'slug' => 'outdated',
            'description' => 'This content may be outdated. Please verify with current documentation.',
            'color' => 'yellow',
            'icon' => '⚠️',
        ]);
    }
}
