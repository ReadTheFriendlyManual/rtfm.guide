<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use App\Models\Guide;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Category $category;

    #[Url(as: 'difficulty', keep: true)]
    public ?string $difficulty = null;

    #[Url(as: 'sort', keep: true)]
    public string $sortBy = 'published_at';

    public function mount(Category $category)
    {
        $this->category = $category->load(['children']);
    }

    public function updatedDifficulty()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->difficulty = null;
        $this->sortBy = 'published_at';
        $this->resetPage();
    }

    public function getGuidesProperty()
    {
        // Get all category IDs (current + children for hierarchical display)
        $categoryIds = [$this->category->id];
        $this->addChildCategoryIds($categoryIds, $this->category->id);

        $query = Guide::query()
            ->with(['user', 'category'])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->whereIn('category_id', $categoryIds);

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

    public function render()
    {
        return view('livewire.categories.show', [
            'guides' => $this->guides,
        ]);
    }
}
