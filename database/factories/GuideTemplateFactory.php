<?php

namespace Database\Factories;

use App\Models\GuideTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GuideTemplate>
 */
class GuideTemplateFactory extends Factory
{
    protected $model = GuideTemplate::class;

    public function definition(): array
    {
        $name = fake()->words(3, true).' Template';

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => fake()->paragraph(),
            'structure' => [
                'sections' => [
                    ['title' => 'Introduction', 'required' => true],
                    ['title' => 'Prerequisites', 'required' => false],
                    ['title' => 'Steps', 'required' => true],
                    ['title' => 'Conclusion', 'required' => false],
                ],
            ],
            'is_official' => false,
            'usage_count' => fake()->numberBetween(0, 500),
        ];
    }
}
