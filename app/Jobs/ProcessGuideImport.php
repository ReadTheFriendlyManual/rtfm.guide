<?php

namespace App\Jobs;

use App\Enums\GuideDifficulty;
use App\Enums\GuideStatus;
use App\Models\Category;
use App\Models\Guide;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessGuideImport implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $filePath,
        public int $userId
    ) {}

    public function handle(): void
    {
        $disk = Storage::disk('guide-imports');

        Log::info('Processing guide import', [
            'path' => $this->filePath,
            'disk' => 'guide-imports',
            'all_files' => $disk->allFiles(),
        ]);

        if (! $disk->exists($this->filePath)) {
            Log::error('Guide import file not found', [
                'path' => $this->filePath,
                'all_files' => $disk->allFiles(),
            ]);

            return;
        }

        $content = $disk->get($this->filePath);

        if (! $content) {
            Log::error('File exists but content is empty', ['path' => $this->filePath]);

            return;
        }

        $guides = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Invalid JSON in guide import file', [
                'path' => $this->filePath,
                'error' => json_last_error_msg(),
            ]);

            return;
        }

        $imported = 0;
        $failed = 0;

        foreach ($guides as $guideData) {
            try {
                DB::transaction(function () use ($guideData, &$imported) {
                    $category = Category::firstOrCreate(
                        ['slug' => $guideData['category']],
                        [
                            'name' => ucwords($guideData['category']),
                            'description' => "Guides related to {$guideData['category']}",
                        ]
                    );

                    Guide::create([
                        'user_id' => $this->userId,
                        'team_id' => null,
                        'slug' => $guideData['slug'],
                        'title' => $guideData['title'],
                        'tldr' => $guideData['tldr'],
                        'tldr_nsfw' => $guideData['tldr_nsfw'] ?? null,
                        'content' => $guideData['content'],
                        'content_nsfw' => $guideData['content_nsfw'] ?? null,
                        'category_id' => $category->id,
                        'difficulty' => GuideDifficulty::from($guideData['difficulty']),
                        'estimated_minutes' => $guideData['estimated_minutes'] ?? null,
                        'status' => GuideStatus::Published,
                        'visibility' => 'public',
                    ]);

                    $imported++;
                });
            } catch (\Exception $e) {
                $failed++;
                Log::error('Failed to import guide', [
                    'slug' => $guideData['slug'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Guide import completed', [
            'file' => $this->filePath,
            'imported' => $imported,
            'failed' => $failed,
        ]);

        $disk->delete($this->filePath);
    }
}
