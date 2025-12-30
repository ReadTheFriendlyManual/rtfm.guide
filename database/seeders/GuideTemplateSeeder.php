<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GuideTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;

class GuideTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        // Create 10 official templates
        GuideTemplate::factory()->count(10)->create([
            'category_id' => fn () => $categories->random()->id,
            'created_by_user_id' => fn () => $users->random()->id,
            'is_official' => true,
        ]);

        // Create 15 user-created templates
        GuideTemplate::factory()->count(15)->create([
            'category_id' => fn () => $categories->random()->id,
            'created_by_user_id' => fn () => $users->random()->id,
            'is_official' => false,
        ]);
    }
}
