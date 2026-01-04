<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Seeder;

class SeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates SEO records for existing models that don't have them yet.
     */
    public function run(): void
    {
        $this->command->info('Generating SEO data for existing models...');

        // Generate SEO for guides
        $guidesWithoutSeo = Guide::whereDoesntHave('seo')->get();
        $this->command->info("Found {$guidesWithoutSeo->count()} guides without SEO data");

        foreach ($guidesWithoutSeo as $guide) {
            $guide->loadMissing(['user', 'category']);
            $seoData = $guide->getDynamicSEOData();

            $guide->seo()->create([
                'title' => $seoData->title,
                'description' => $seoData->description,
                'author' => $seoData->author,
                'image' => $seoData->image,
            ]);
        }

        // Generate SEO for categories
        $categoriesWithoutSeo = Category::whereDoesntHave('seo')
            ->withCount('guides')
            ->get();
        $this->command->info("Found {$categoriesWithoutSeo->count()} categories without SEO data");

        foreach ($categoriesWithoutSeo as $category) {
            $seoData = $category->getDynamicSEOData();

            $category->seo()->create([
                'title' => $seoData->title,
                'description' => $seoData->description,
                'image' => $seoData->image,
            ]);
        }

        // Generate SEO for users
        $usersWithoutSeo = User::whereDoesntHave('seo')->get();
        $this->command->info("Found {$usersWithoutSeo->count()} users without SEO data");

        foreach ($usersWithoutSeo as $user) {
            $seoData = $user->getDynamicSEOData();

            $user->seo()->create([
                'title' => $seoData->title,
                'description' => $seoData->description,
                'author' => $seoData->author,
                'image' => $seoData->image,
            ]);
        }

        $this->command->info('SEO data generation complete!');
    }
}
