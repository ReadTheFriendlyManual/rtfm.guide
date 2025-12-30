<?php

namespace Database\Factories;

use App\Models\SavedGuide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SavedGuide>
 */
class SavedGuideFactory extends Factory
{
    protected $model = SavedGuide::class;

    public function definition(): array
    {
        return [
            // No additional fields - just user_id and guide_id from seeder
        ];
    }
}
