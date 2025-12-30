<?php

namespace Database\Seeders;

use App\Models\Guide;
use App\Models\SavedGuide;
use App\Models\User;
use Illuminate\Database\Seeder;

class SavedGuideSeeder extends Seeder
{
    public function run(): void
    {
        $guides = Guide::all();
        $users = User::all();

        // Create saved guides ensuring unique (user_id, guide_id) combinations
        $created = 0;
        $maxAttempts = 400;
        $attempts = 0;

        while ($created < 200 && $attempts < $maxAttempts) {
            try {
                SavedGuide::factory()->create([
                    'guide_id' => $guides->random()->id,
                    'user_id' => $users->random()->id,
                ]);
                $created++;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Skip duplicate combinations
            }
            $attempts++;
        }
    }
}
