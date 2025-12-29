<?php

namespace App\Http\Controllers;

use App\Enums\GuideDifficulty;
use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
use App\Http\Requests\StoreGuideRequest;
use App\Http\Requests\UpdateGuideRequest;
use App\Models\Category;
use App\Models\Guide;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GuideManagementController extends Controller
{
    use AuthorizesRequests;
    public function create()
    {
        $categories = Category::with('parent')
            ->orderBy('order')
            ->get()
            ->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->parent
                    ? "{$category->parent->name} / {$category->name}"
                    : $category->name,
            ]);

        $difficulties = collect(GuideDifficulty::cases())->map(fn ($difficulty) => [
            'value' => $difficulty->value,
            'label' => $difficulty->label(),
            'description' => $difficulty->description(),
        ]);

        $osTags = [
            'Linux',
            'macOS',
            'Windows',
            'Ubuntu',
            'Debian',
            'CentOS',
            'RHEL',
            'Arch',
            'Docker',
        ];

        return Inertia::render('Guides/Create', [
            'categories' => $categories,
            'difficulties' => $difficulties,
            'osTags' => $osTags,
        ]);
    }

    public function store(StoreGuideRequest $request)
    {
        $guide = Guide::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => $request->slug,
            'tldr' => $request->tldr,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'difficulty' => $request->difficulty ?? GuideDifficulty::Beginner,
            'estimated_minutes' => $request->estimated_minutes,
            'os_tags' => $request->os_tags ?? [],
            'status' => $request->status ?? GuideStatus::Draft,
            'visibility' => $request->visibility ?? GuideVisibility::Public,
        ]);

        if ($guide->status === GuideStatus::Published) {
            $guide->published_at = now();
            $guide->save();
        }

        return redirect()
            ->route('guides.edit', $guide)
            ->with('success', 'Guide created successfully!');
    }

    public function edit(Guide $guide)
    {
        $this->authorize('update', $guide);

        $categories = Category::with('parent')
            ->orderBy('order')
            ->get()
            ->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->parent
                    ? "{$category->parent->name} / {$category->name}"
                    : $category->name,
            ]);

        $difficulties = collect(GuideDifficulty::cases())->map(fn ($difficulty) => [
            'value' => $difficulty->value,
            'label' => $difficulty->label(),
            'description' => $difficulty->description(),
        ]);

        $osTags = [
            'Linux',
            'macOS',
            'Windows',
            'Ubuntu',
            'Debian',
            'CentOS',
            'RHEL',
            'Arch',
            'Docker',
        ];

        return Inertia::render('Guides/Edit', [
            'guide' => [
                'id' => $guide->id,
                'title' => $guide->title,
                'slug' => $guide->slug,
                'tldr' => $guide->tldr,
                'content' => $guide->content,
                'category_id' => $guide->category_id,
                'difficulty' => $guide->difficulty->value,
                'estimated_minutes' => $guide->estimated_minutes,
                'os_tags' => $guide->os_tags ?? [],
                'status' => $guide->status->value,
                'visibility' => $guide->visibility->value,
            ],
            'categories' => $categories,
            'difficulties' => $difficulties,
            'osTags' => $osTags,
        ]);
    }

    public function update(UpdateGuideRequest $request, Guide $guide)
    {
        $wasPublished = $guide->status === GuideStatus::Published;

        $guide->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'tldr' => $request->tldr,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'difficulty' => $request->difficulty,
            'estimated_minutes' => $request->estimated_minutes,
            'os_tags' => $request->os_tags ?? [],
            'status' => $request->status ?? $guide->status,
            'visibility' => $request->visibility ?? $guide->visibility,
        ]);

        if (! $wasPublished && $guide->status === GuideStatus::Published) {
            $guide->published_at = now();
            $guide->save();
        }

        return back()->with('success', 'Guide updated successfully!');
    }

    public function index()
    {
        $guides = Guide::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(15);

        return Inertia::render('Guides/MyGuides', [
            'guides' => $guides->through(fn ($guide) => [
                'id' => $guide->id,
                'title' => $guide->title,
                'slug' => $guide->slug,
                'category' => [
                    'name' => $guide->category->name,
                    'slug' => $guide->category->slug,
                ],
                'difficulty' => [
                    'value' => $guide->difficulty->value,
                    'label' => $guide->difficulty->label(),
                ],
                'status' => [
                    'value' => $guide->status->value,
                    'label' => $guide->status->label(),
                    'color' => $guide->status->color(),
                ],
                'view_count' => $guide->view_count,
                'created_at' => $guide->created_at->diffForHumans(),
                'updated_at' => $guide->updated_at->diffForHumans(),
            ]),
        ]);
    }
}
