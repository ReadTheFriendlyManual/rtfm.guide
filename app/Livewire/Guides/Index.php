<?php

namespace App\Livewire\Guides;

use App\Models\Category;
use App\Models\Guide;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q', keep: true)]
    public string $search = '';

    #[Url(as: 'category', keep: true)]
    public ?int $categoryId = null;

    #[Url(as: 'difficulty', keep: true)]
    public ?string $difficulty = null;

    #[Url(as: 'sort', keep: true)]
    public string $sortBy = 'published_at';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryId()
    {
        $this->resetPage();
    }

    public function updatedDifficulty()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->categoryId = null;
        $this->difficulty = null;
        $this->sortBy = 'published_at';
        $this->resetPage();
    }

    public function getGuidesProperty()
    {
        $query = Guide::query()
            ->with(['user', 'category', 'reactions'])
            ->where('status', 'published')
            ->where('visibility', 'public');

        // Apply search if provided
        if ($this->search) {
            $query->search($this->search);
        }

        // Apply category filter
        if ($this->categoryId) {
            // Get all descendant categories for hierarchical filtering
            $categoryIds = [$this->categoryId];
            $this->addChildCategoryIds($categoryIds, $this->categoryId);
            $query->whereIn('category_id', $categoryIds);
        }

        // Apply difficulty filter
        if ($this->difficulty) {
            $query->where('difficulty', $this->difficulty);
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'views':
                $query->orderBy('view_count', 'desc');
                break;
            case 'newest':
                $query->orderBy('published_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->orderBy('published_at', 'desc');
        }

        return $query->paginate(12);
    }

    private function addChildCategoryIds(array &$categoryIds, int $parentId): void
    {
        $children = Category::where('parent_id', $parentId)->pluck('id')->toArray();
        foreach ($children as $childId) {
            $categoryIds[] = $childId;
            $this->addChildCategoryIds($categoryIds, $childId);
        }
    }

    public function getCategoriesProperty()
    {
        return Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('order')
            ->get();
    }

    public function render()
    {
        return view('livewire.guides.index', [
            'guides' => $this->guides,
            'categories' => $this->categories,
        ]);
    }
}
