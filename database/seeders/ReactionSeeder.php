<?php

namespace Database\Seeders;

use App\Models\Guide;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReactionSeeder extends Seeder
{
    public function run(): void
    {
        $guides = Guide::all();
        $users = User::all();

        // Create reactions ensuring unique (guide_id, user_id, type) combinations
        $created = 0;
        $maxAttempts = 500;
        $attempts = 0;

        while ($created < 300 && $attempts < $maxAttempts) {
            try {
                Reaction::factory()->create([
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
