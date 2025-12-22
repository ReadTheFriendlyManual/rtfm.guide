<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Guide;
use App\Models\Reaction;
use App\Models\RtfmMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $rootCategories = Category::factory(3)->create();

        $rootCategories->each(function (Category $category) use ($admin): void {
            $children = Category::factory(2)->create(['parent_id' => $category->id]);

            Guide::factory(3)
                ->for($admin, 'author')
                ->for($category)
                ->create();

            $children->each(function (Category $child) use ($admin): void {
                Guide::factory(2)
                    ->for($admin, 'author')
                    ->for($child)
                    ->create();
            });
        });

        RtfmMessage::factory(6)->create([
            'user_id' => $admin->id,
            'is_approved' => true,
        ]);

        Guide::query()->each(function (Guide $guide): void {
            Reaction::factory()->count(2)->create([
                'guide_id' => $guide->id,
                'user_id' => User::factory(),
            ]);
        });
    }
}
