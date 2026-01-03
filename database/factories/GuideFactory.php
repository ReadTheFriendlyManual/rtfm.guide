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
        $nsfwPrefixes = [
            'What the fuck is wrong with you? ',
            'Listen up, dipshit: ',
            'Holy shit, ',
            'Goddamn it, ',
            'For fuck\'s sake, ',
            'Jesus fucking Christ, ',
            'Fucking hell, ',
            'Are you fucking kidding me? ',
        ];

        $nsfwSuffixes = [
            ', you absolute moron!',
            ', dickhead!',
            ', you useless fuck!',
            ', asshole!',
            ', you incompetent bastard!',
            ', shithead!',
            ', fuckface!',
            ', you dense motherfucker!',
        ];

        return [
            'user_id' => \App\Models\User::factory(),
            'slug' => $this->faker->unique()->slug(),
            'title' => $this->faker->sentence(),
            'tldr' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'tldr_nsfw' => $this->faker->randomElement($nsfwPrefixes).$this->faker->sentence().$this->faker->randomElement($nsfwSuffixes),
            'content_nsfw' => "## Fucking Prerequisites\n\n".$this->faker->paragraphs(2, true)."\n\n## Stop Being a Dumbass and Do This\n\n".$this->faker->paragraphs(3, true)."\n\n## If You Fuck This Up\n\n".$this->faker->paragraph()."\n\nSeriously, read the fucking instructions, dipshit.",
            'category_id' => \App\Models\Category::factory(),
            'difficulty' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'estimated_minutes' => $this->faker->numberBetween(5, 120),
            'os_tags' => $this->faker->randomElements(['windows', 'macos', 'linux'], $this->faker->numberBetween(0, 3)),
            'status' => 'published',
            'visibility' => 'public',
            'is_featured' => false,
            'view_count' => $this->faker->numberBetween(0, 1000),
            'published_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => $attributes['published_at'] ?? $this->faker->dateTimeBetween('-1 year'),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'visibility' => 'private',
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
