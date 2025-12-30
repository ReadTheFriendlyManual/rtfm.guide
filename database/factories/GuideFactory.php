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
            'slug' => \Illuminate\Support\Str::slug(fake()->unique()->words(3, true)),
            'title' => fake()->sentence(),
            'tldr' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'tldr_nsfw' => fake()->randomElement($nsfwPrefixes).fake()->sentence().fake()->randomElement($nsfwSuffixes),
            'content_nsfw' => "## Fucking Prerequisites\n\n".fake()->paragraphs(2, true)."\n\n## Stop Being a Dumbass and Do This\n\n".fake()->paragraphs(3, true)."\n\n## If You Fuck This Up\n\n".fake()->paragraph()."\n\nSeriously, read the fucking instructions, dipshit.",
            'category_id' => \App\Models\Category::factory(),
            'difficulty' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'estimated_minutes' => fake()->numberBetween(5, 120),
            'os_tags' => fake()->randomElements(['windows', 'macos', 'linux'], fake()->numberBetween(0, 3)),
            'status' => 'published',
            'visibility' => 'public',
            'view_count' => fake()->numberBetween(0, 1000),
            'published_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => $attributes['published_at'] ?? fake()->dateTimeBetween('-1 year'),
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
}
