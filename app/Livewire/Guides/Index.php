<?php

namespace App\Livewire\Guides;

use App\Models\Category;
use App\Models\Guide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public ?string $difficulty = null;

    #[Url]
    public ?string $category = null;

    #[Url]
    public ?string $os = null;

    public bool $showSavedOnly = false;

    public function resetFilters(): void
    {
        $this->search = '';
        $this->difficulty = null;
        $this->category = null;
        $this->os = null;
        $this->showSavedOnly = false;
    }

    public function feelingLucky(): Redirector|RedirectResponse|null
    {
        $guide = Guide::query()->published()->inRandomOrder()->first();

        if ($guide === null) {
            return null;
        }

        return $this->redirectRoute('guides.show', $guide);
    }

    /**
     * @return Collection<Guide>
     */
    public function getGuidesProperty(): Collection
    {
        $category = $this->resolveCategory();

        return Guide::query()
            ->with(['category', 'author'])
            ->published()
            ->search($this->search)
            ->forDifficulty($this->difficulty)
            ->forCategory($category)
            ->when($this->os, fn ($query) => $query->whereJsonContains('os_tags', $this->os))
            ->when(
                $this->showSavedOnly && auth()->check(),
                fn ($query) => $query->whereRelation('savedBy', 'users.id', auth()->id()),
            )
            ->latest('published_at')
            ->limit(12)
            ->get();
    }

    /**
     * @return Collection<Guide>
     */
    public function getTrendingGuidesProperty(): Collection
    {
        return Guide::query()
            ->with(['category', 'author'])
            ->published()
            ->trending()
            ->limit(3)
            ->get();
    }

    protected function resolveCategory(): ?Category
    {
        if (blank($this->category)) {
            return null;
        }

        return Category::query()->where('slug', $this->category)->first();
    }

    #[Layout('components.layouts.public')]
    public function render(): View
    {
        return view('livewire.guides.index', [
            'guides' => $this->guides,
            'categories' => Category::query()->orderBy('display_order')->get(),
            'luckyGuide' => Guide::query()->published()->inRandomOrder()->first(),
        ]);
    }
}
