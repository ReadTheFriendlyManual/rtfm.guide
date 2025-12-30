<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $guides = Guide::all();
        $users = User::all();

        // Create top-level comments (no parent)
        $topLevelComments = Comment::factory()->count(100)->create([
            'guide_id' => fn () => $guides->random()->id,
            'user_id' => fn () => $users->random()->id,
            'parent_id' => null,
            'is_approved' => fake()->boolean(80), // 80% approved
        ]);

        // Create reply comments (with parent)
        Comment::factory()->count(150)->create([
            'guide_id' => fn () => $guides->random()->id,
            'user_id' => fn () => $users->random()->id,
            'parent_id' => fn () => $topLevelComments->random()->id,
            'is_approved' => fake()->boolean(85), // 85% approved
        ]);
    }
}
