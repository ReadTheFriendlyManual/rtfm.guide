<?php

use App\Enums\GuideStatus;
use App\Jobs\ProcessGuideImport;
use App\Models\Category;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->user = User::factory()->create();
    Storage::fake('guide-imports');
    Queue::fake();
});

it('requires authentication to upload guide imports', function () {
    $file = UploadedFile::fake()->create('guides.json', 100, 'application/json');

    postJson(route('api.guide-imports.upload'), ['file' => $file])
        ->assertUnauthorized();
});

it('validates that a file is required', function () {
    actingAs($this->user)
        ->postJson(route('api.guide-imports.upload'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['file' => 'Please upload a JSON file containing guides to import.']);
});

it('validates that the file must be JSON', function () {
    $file = UploadedFile::fake()->create('guides.txt', 100, 'text/plain');

    actingAs($this->user)
        ->postJson(route('api.guide-imports.upload'), ['file' => $file])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['file' => 'The file must be a valid JSON file.']);
});

it('validates that the file size must not exceed 10MB', function () {
    $file = UploadedFile::fake()->create('guides.json', 11000, 'application/json');

    actingAs($this->user)
        ->postJson(route('api.guide-imports.upload'), ['file' => $file])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['file' => 'The file size must not exceed 10MB.']);
});

it('uploads guide import file to S3 and dispatches job', function () {
    $file = UploadedFile::fake()->create('guides.json', 100, 'application/json');

    actingAs($this->user)
        ->postJson(route('api.guide-imports.upload'), ['file' => $file])
        ->assertStatus(202)
        ->assertJson([
            'message' => 'Guide import file uploaded successfully. Processing will begin shortly.',
        ]);

    Queue::assertPushed(ProcessGuideImport::class, function ($job) {
        return $job->userId === $this->user->id
            && str_contains($job->filePath, 'guides.json');
    });
});

it('processes guide import and creates guides with pending status', function () {
    $jsonData = [
        [
            'team_id' => null,
            'slug' => 'test-guide',
            'title' => 'Test Guide',
            'tldr' => 'A test guide',
            'content' => '## Test Content',
            'category' => 'testing',
            'difficulty' => 'beginner',
            'estimated_minutes' => 5,
        ],
    ];

    Storage::disk('guide-imports')->put('test-import.json', json_encode($jsonData));

    $job = new ProcessGuideImport('test-import.json', $this->user->id);
    $job->handle();

    assertDatabaseHas('categories', [
        'slug' => 'testing',
        'name' => 'Testing',
    ]);

    assertDatabaseHas('guides', [
        'slug' => 'test-guide',
        'title' => 'Test Guide',
        'status' => GuideStatus::Pending->value,
        'user_id' => $this->user->id,
    ]);

    Storage::disk('guide-imports')->assertMissing('test-import.json');
});

it('processes guides with NSFW content', function () {
    $jsonData = [
        [
            'team_id' => null,
            'slug' => 'nsfw-guide',
            'title' => 'NSFW Guide',
            'tldr' => 'A safe tldr',
            'tldr_nsfw' => 'A spicy tldr',
            'content' => '## Safe content',
            'content_nsfw' => '## Spicy content',
            'category' => 'testing',
            'difficulty' => 'beginner',
            'estimated_minutes' => 5,
        ],
    ];

    Storage::disk('guide-imports')->put('test-nsfw.json', json_encode($jsonData));

    $job = new ProcessGuideImport('test-nsfw.json', $this->user->id);
    $job->handle();

    $guide = Guide::where('slug', 'nsfw-guide')->first();

    expect($guide->tldr_nsfw)->toBe('A spicy tldr');
    expect($guide->content_nsfw)->toBe('## Spicy content');
});

it('uses existing category if it already exists', function () {
    $category = Category::factory()->create(['slug' => 'nginx', 'name' => 'Nginx']);

    $jsonData = [
        [
            'team_id' => null,
            'slug' => 'nginx-guide',
            'title' => 'Nginx Guide',
            'tldr' => 'A guide about nginx',
            'content' => '## Nginx Content',
            'category' => 'nginx',
            'difficulty' => 'beginner',
            'estimated_minutes' => 5,
        ],
    ];

    Storage::disk('guide-imports')->put('test-category.json', json_encode($jsonData));

    $job = new ProcessGuideImport('test-category.json', $this->user->id);
    $job->handle();

    expect(Category::where('slug', 'nginx')->count())->toBe(1);

    $guide = Guide::where('slug', 'nginx-guide')->first();
    expect($guide->category_id)->toBe($category->id);
});

it('ignores team_id field from import', function () {
    $jsonData = [
        [
            'team_id' => 999,
            'slug' => 'team-guide',
            'title' => 'Team Guide',
            'tldr' => 'A guide with team_id',
            'content' => '## Content',
            'category' => 'testing',
            'difficulty' => 'beginner',
            'estimated_minutes' => 5,
        ],
    ];

    Storage::disk('guide-imports')->put('test-team.json', json_encode($jsonData));

    $job = new ProcessGuideImport('test-team.json', $this->user->id);
    $job->handle();

    $guide = Guide::where('slug', 'team-guide')->first();
    expect($guide->team_id)->toBeNull();
});
