<?php

namespace Database\Seeders;

use App\Enums\GuideDifficulty;
use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
use App\Models\Category;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FeaturedContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createFeaturedWriters();
        $this->createFeaturedGuides();
        $this->attachFeaturedWritersToCategories();
    }

    /**
     * Create featured writer users with complete profiles
     */
    private function createFeaturedWriters(): void
    {
        $writers = [
            [
                'name' => 'Sarah Chen',
                'email' => 'sarah.chen@example.com',
                'bio' => 'DevOps engineer with 10+ years experience',
                'featured_bio' => 'Sarah is a seasoned DevOps engineer specializing in cloud infrastructure and automation. She has helped dozens of companies streamline their deployment pipelines and improve system reliability.',
                'github_username' => 'sarahchen',
                'twitter_username' => 'sarahchen',
                'linkedin_username' => 'sarah-chen',
                'website_url' => 'https://sarahchen.dev',
                'reputation_points' => 500,
                'trust_level' => 'leader',
            ],
            [
                'name' => 'Marcus Rodriguez',
                'email' => 'marcus.rodriguez@example.com',
                'bio' => 'Full-stack developer and technical writer',
                'featured_bio' => 'Marcus has been writing technical documentation and tutorials for over 8 years. He specializes in making complex topics accessible to developers of all skill levels.',
                'github_username' => 'marcusdev',
                'gitlab_username' => 'marcus.rodriguez',
                'twitter_username' => 'marcusdev',
                'linkedin_username' => 'marcus-rodriguez',
                'website_url' => 'https://marcus.codes',
                'reputation_points' => 750,
                'trust_level' => 'leader',
            ],
            [
                'name' => 'Emily Watson',
                'email' => 'emily.watson@example.com',
                'bio' => 'Security specialist and Linux enthusiast',
                'featured_bio' => 'Emily focuses on application security and best practices for securing web applications. She regularly speaks at conferences and contributes to open-source security tools.',
                'github_username' => 'ewatson',
                'twitter_username' => 'emilywatson',
                'linkedin_username' => 'emily-watson-security',
                'reputation_points' => 650,
                'trust_level' => 'leader',
            ],
            [
                'name' => 'Raj Patel',
                'email' => 'raj.patel@example.com',
                'bio' => 'Database architect and performance expert',
                'featured_bio' => 'Raj has optimized database systems for Fortune 500 companies. He specializes in PostgreSQL, MySQL, and MongoDB performance tuning and scaling strategies.',
                'github_username' => 'rajpatel',
                'gitlab_username' => 'raj.patel',
                'twitter_username' => 'rajpateldb',
                'linkedin_username' => 'raj-patel-db',
                'website_url' => 'https://rajpatel.io',
                'reputation_points' => 820,
                'trust_level' => 'leader',
            ],
        ];

        foreach ($writers as $writerData) {
            User::firstOrCreate(
                ['email' => $writerData['email']],
                array_merge($writerData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );
        }
    }

    /**
     * Create featured guides across different categories
     */
    private function createFeaturedGuides(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');

            return;
        }

        $writers = User::whereIn('email', [
            'sarah.chen@example.com',
            'marcus.rodriguez@example.com',
            'emily.watson@example.com',
            'raj.patel@example.com',
        ])->get();

        if ($writers->isEmpty()) {
            $this->command->warn('No featured writers found.');

            return;
        }

        // Create featured guides for each category
        foreach ($categories->take(5) as $category) {
            $this->createFeaturedGuidesForCategory($category, $writers);
        }

        // Mark some existing guides as featured
        $this->markExistingGuidesAsFeatured();
    }

    /**
     * Create 2-3 featured guides per category
     */
    private function createFeaturedGuidesForCategory(Category $category, $writers): void
    {
        $guidesCount = rand(2, 3);

        for ($i = 0; $i < $guidesCount; $i++) {
            $writer = $writers->random();
            $difficulty = collect(GuideDifficulty::cases())->random();

            $titlePrefix = match ($difficulty) {
                GuideDifficulty::Beginner => 'Getting Started with',
                GuideDifficulty::Intermediate => 'Advanced Techniques for',
                GuideDifficulty::Advanced => 'Mastering',
            };

            Guide::create([
                'user_id' => $writer->id,
                'slug' => $category->slug.'-featured-'.($i + 1).'-'.time().rand(1000, 9999),
                'title' => $titlePrefix.' '.$category->name,
                'tldr' => 'A comprehensive guide covering essential '.$category->name.' concepts and best practices.',
                'tldr_nsfw' => 'Stop fucking around and learn '.$category->name.' properly, you lazy bastard.',
                'content' => $this->generateGuideContent($category->name, $difficulty),
                'content_nsfw' => $this->generateNsfwGuideContent($category->name, $difficulty),
                'category_id' => $category->id,
                'difficulty' => $difficulty,
                'estimated_minutes' => rand(10, 45),
                'os_tags' => $this->getRandomOsTags(),
                'status' => GuideStatus::Published,
                'visibility' => GuideVisibility::Public,
                'is_featured' => true,
                'view_count' => rand(100, 5000),
                'published_at' => now()->subDays(rand(1, 60)),
            ]);
        }
    }

    /**
     * Mark some existing guides as featured
     */
    private function markExistingGuidesAsFeatured(): void
    {
        Guide::where('status', GuideStatus::Published)
            ->where('visibility', GuideVisibility::Public)
            ->whereNull('is_featured')
            ->orWhere('is_featured', false)
            ->inRandomOrder()
            ->limit(5)
            ->update([
                'is_featured' => true,
            ]);
    }

    /**
     * Attach featured writers to categories
     */
    private function attachFeaturedWritersToCategories(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        $writers = User::whereIn('email', [
            'sarah.chen@example.com',
            'marcus.rodriguez@example.com',
            'emily.watson@example.com',
            'raj.patel@example.com',
        ])->get();

        if ($writers->isEmpty()) {
            return;
        }

        // Attach featured writers to categories
        $categoryMappings = [
            'nginx' => ['sarah.chen@example.com', 'emily.watson@example.com'],
            'deployment' => ['sarah.chen@example.com', 'marcus.rodriguez@example.com'],
            'git' => ['marcus.rodriguez@example.com'],
            'docker' => ['sarah.chen@example.com', 'raj.patel@example.com'],
            'database' => ['raj.patel@example.com', 'emily.watson@example.com'],
            'security' => ['emily.watson@example.com'],
        ];

        foreach ($categoryMappings as $categorySlug => $writerEmails) {
            $category = Category::where('slug', $categorySlug)->first();

            if (! $category) {
                continue;
            }

            // Detach existing featured writers first
            $category->featuredWriters()->detach();

            $order = 1;
            foreach ($writerEmails as $email) {
                $writer = $writers->firstWhere('email', $email);

                if ($writer) {
                    $category->featuredWriters()->attach($writer->id, [
                        'order' => $order,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $order++;
                }
            }
        }

        $this->command->info('Featured writers attached to categories');
    }

    /**
     * Generate sample guide content
     */
    private function generateGuideContent(string $topic, GuideDifficulty $difficulty): string
    {
        return <<<MARKDOWN
# {$topic} Guide

## Introduction

This guide will walk you through everything you need to know about {$topic}.

## Prerequisites

- Basic understanding of command line
- {$topic} installed on your system
- Text editor of your choice

## Getting Started

```bash
# Example command
some-command --help
```

## Key Concepts

1. **Concept 1**: Understanding the basics
2. **Concept 2**: Intermediate techniques
3. **Concept 3**: Advanced patterns

## Best Practices

- Always follow security guidelines
- Keep your system updated
- Document your configurations

## Troubleshooting

If you encounter issues, check the following:

- Verify your configuration
- Check the logs
- Consult the documentation

## Conclusion

You should now have a solid understanding of {$topic}. Practice these techniques to master the concepts.

## Further Reading

- Official {$topic} documentation
- Community forums and discussions
MARKDOWN;
    }

    /**
     * Generate NSFW guide content
     */
    private function generateNsfwGuideContent(string $topic, GuideDifficulty $difficulty): string
    {
        return <<<MARKDOWN
# Stop Being a Dumbass and Learn {$topic}

## Introduction

Seriously? You don't know {$topic} yet? Well, buckle up buttercup.

## Prerequisites

- A functioning brain (questionable)
- {$topic} installed, obviously
- The ability to read, which I'm not even sure you have

## Getting Started

```bash
# Just run this, genius
some-command --help
```

## Key Concepts You Should Already Know

1. **Concept 1**: This is basic shit
2. **Concept 2**: Still pretty fucking basic
3. **Concept 3**: If you don't get this, give up now

## Best Practices (That You'll Probably Ignore)

- Don't be an idiot with security
- Update your damn system
- Document your shit so you remember what you did

## Troubleshooting (When You Inevitably Fuck Up)

When things break (and they will because you're you):

- Actually read the error message this time
- Check the fucking logs
- RTFM - that's literally why you're here

## Conclusion

If you've made it this far without breaking everything, congratulations. You're slightly less incompetent than before.

## Further Reading (Please Actually Read It)

- The documentation you should have read first
- Forums where people will roast you for asking dumb questions
MARKDOWN;
    }

    /**
     * Get random OS tags
     */
    private function getRandomOsTags(): array
    {
        $allTags = ['Linux', 'macOS', 'Windows', 'Ubuntu', 'Debian', 'CentOS', 'FreeBSD'];
        $count = rand(1, 3);

        return collect($allTags)->random($count)->values()->toArray();
    }
}
