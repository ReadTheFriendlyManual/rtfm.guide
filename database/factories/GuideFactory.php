<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guide>
 */
class GuideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(nbWords: 6);
        $osTags = fake()->randomElements(
            ['linux', 'macos', 'windows', 'docker', 'laravel'],
            fake()->numberBetween(1, 3),
        );

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(10, 9999),
            'title' => Str::title($title),
            'tldr' => fake()->sentence(),
            'content' => implode("\n\n", [
                '## Quickstart',
                '- Step one: '.fake()->sentence(),
                '- Step two: '.fake()->sentence(),
                '## Troubleshooting',
                '- '.fake()->sentence(),
            ]),
            'difficulty' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'estimated_minutes' => fake()->numberBetween(5, 45),
            'os_tags' => $osTags,
            'status' => 'published',
            'visibility' => 'public',
            'view_count' => fake()->numberBetween(0, 1200),
            'share_count' => fake()->numberBetween(0, 400),
            'prerequisites' => [
                [
                    'label' => 'Shell access',
                    'summary' => 'You can run commands on the target machine.',
                ],
                [
                    'label' => 'Backups',
                    'summary' => 'Recent backups exist for rollbacks.',
                ],
            ],
            'troubleshooting' => [
                [
                    'label' => 'Permission errors',
                    'summary' => 'Ensure your user has sudo or correct ACLs.',
                ],
                [
                    'label' => 'Service not restarting',
                    'summary' => 'Validate the service file path and run systemctl status.',
                ],
            ],
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
        ];
    }
}
