<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\ContentFlag;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentFlagSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $guides = Guide::all();
        $comments = Comment::all();

        // Flag some guides - ensuring unique (user_id, flaggable_id, flaggable_type) combinations
        $created = 0;
        $maxAttempts = 30;
        $attempts = 0;

        while ($created < 15 && $attempts < $maxAttempts) {
            try {
                ContentFlag::factory()->create([
                    'user_id' => $users->random()->id,
                    'flaggable_id' => $guides->random()->id,
                    'flaggable_type' => Guide::class,
                    'reviewed_by' => fake()->boolean(60) ? $users->random()->id : null,
                    'reviewed_at' => fake()->boolean(60) ? now()->subDays(rand(1, 30)) : null,
                ]);
                $created++;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Skip duplicate combinations
            }
            $attempts++;
        }

        // Flag some comments
        $created = 0;
        $maxAttempts = 50;
        $attempts = 0;

        while ($created < 25 && $attempts < $maxAttempts) {
            try {
                ContentFlag::factory()->create([
                    'user_id' => $users->random()->id,
                    'flaggable_id' => $comments->random()->id,
                    'flaggable_type' => Comment::class,
                    'reviewed_by' => fake()->boolean(70) ? $users->random()->id : null,
                    'reviewed_at' => fake()->boolean(70) ? now()->subDays(rand(1, 30)) : null,
                ]);
                $created++;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Skip duplicate combinations
            }
            $attempts++;
        }
    }
}
