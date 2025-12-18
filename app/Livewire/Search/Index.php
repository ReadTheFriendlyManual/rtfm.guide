<?php

namespace App\Livewire\Search;

use App\Models\Guide;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    #[Url(as: 'q', keep: true)]
    public string $query = '';

    public $results = [];

    public function updatedQuery()
    {
        $this->search();
    }

    public function search()
    {
        if (empty($this->query)) {
            $this->results = [];

            return;
        }

        $this->results = Guide::search($this->query)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->take(20)
            ->get()
            ->load(['user', 'category']);
    }

    public function render()
    {
        return view('livewire.search.index');
    }
}
