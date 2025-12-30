<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Base data - no dependencies
            UserSeeder::class,

            // Depends on Users
            TeamSeeder::class,

            // Can have parent categories (self-referential)
            CategorySeeder::class,

            // Depends on Categories and Users
            GuideTemplateSeeder::class,

            // Depends on Users, Teams, Categories, GuideTemplates
            GuideSeeder::class,

            // Depends on Guides and Users
            CommentSeeder::class,
            ReactionSeeder::class,
            SavedGuideSeeder::class,

            // Depends on Users, Guides, Comments (polymorphic)
            ContentFlagSeeder::class,

            // Depends on Users
            RtfmMessageSeeder::class,
        ]);
    }
}
