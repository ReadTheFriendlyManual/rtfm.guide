<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\GuideRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GuideRevisionController extends Controller
{
    protected function authorizeAdmin()
    {
        if (! Auth::check() || ! Auth::user()->is_admin) {
            abort(403, 'Only administrators can access revision management.');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();
        $revisions = GuideRevision::with(['guide', 'user', 'category'])
            ->pending()
            ->latest()
            ->paginate(15);

        return Inertia::render('Revisions/Index', [
            'revisions' => $revisions->through(fn ($revision) => [
                'id' => $revision->id,
                'title' => $revision->title,
                'guide' => [
                    'id' => $revision->guide->id,
                    'title' => $revision->guide->title,
                    'slug' => $revision->guide->slug,
                ],
                'user' => [
                    'name' => $revision->user->name,
                ],
                'category' => [
                    'name' => $revision->category->name,
                ],
                'difficulty' => [
                    'value' => $revision->difficulty->value,
                    'label' => $revision->difficulty->label(),
                ],
                'created_at' => $revision->created_at->diffForHumans(),
            ]),
        ]);
    }

    public function show(GuideRevision $revision)
    {
        $this->authorizeAdmin();

        $revision->load(['guide', 'user', 'category']);

        return Inertia::render('Revisions/Show', [
            'revision' => [
                'id' => $revision->id,
                'title' => $revision->title,
                'tldr' => $revision->tldr,
                'content' => $revision->content,
                'category_id' => $revision->category_id,
                'difficulty' => $revision->difficulty->value,
                'estimated_minutes' => $revision->estimated_minutes,
                'os_tags' => $revision->os_tags ?? [],
                'user' => [
                    'name' => $revision->user->name,
                ],
                'created_at' => $revision->created_at->diffForHumans(),
            ],
            'guide' => [
                'id' => $revision->guide->id,
                'title' => $revision->guide->title,
                'slug' => $revision->guide->slug,
                'tldr' => $revision->guide->tldr,
                'content' => $revision->guide->content,
                'category_id' => $revision->guide->category_id,
                'difficulty' => $revision->guide->difficulty->value,
                'estimated_minutes' => $revision->guide->estimated_minutes,
                'os_tags' => $revision->guide->os_tags ?? [],
            ],
        ]);
    }

    public function approve(GuideRevision $revision)
    {
        $this->authorizeAdmin();

        $guide = $revision->guide;

        // Apply the revision to the guide
        $guide->update([
            'title' => $revision->title,
            'tldr' => $revision->tldr,
            'tldr_nsfw' => $revision->tldr_nsfw,
            'content' => $revision->content,
            'content_nsfw' => $revision->content_nsfw,
            'category_id' => $revision->category_id,
            'difficulty' => $revision->difficulty,
            'estimated_minutes' => $revision->estimated_minutes,
            'os_tags' => $revision->os_tags,
        ]);

        // Mark revision as approved
        $revision->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('revisions.index')
            ->with('success', 'Revision approved and applied to the guide!');
    }

    public function reject(Request $request, GuideRevision $revision)
    {
        $this->authorizeAdmin();

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $revision->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()
            ->route('revisions.index')
            ->with('success', 'Revision rejected.');
    }
}
