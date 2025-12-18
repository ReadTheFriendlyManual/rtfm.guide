<?php

namespace App\Livewire\Guides;

use App\Models\Guide;
use App\Models\RtfmMessage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Show extends Component
{
    public Guide $guide;

    public string $rtfmMessage = '';

    public function mount(Guide $guide)
    {
        $this->guide = $guide->load(['user', 'category', 'comments.user', 'reactions']);

        // Track view (in a real app, we'd want to prevent duplicate views from same user)
        $this->guide->increment('view_count');

        // Get random RTFM message
        $this->rtfmMessage = $this->getRandomRtfmMessage();
    }

    private function getRandomRtfmMessage(): string
    {
        $message = RtfmMessage::where('is_approved', true)
            ->inRandomOrder()
            ->first();

        return $message ? $message->message : 'You should have RTFM... but we did it for you.';
    }

    public function regenerateRtfmMessage()
    {
        $this->rtfmMessage = $this->getRandomRtfmMessage();
    }

    public function getRelatedGuidesProperty()
    {
        // Get guides from same category, excluding current guide
        $related = Guide::query()
            ->with(['user', 'category'])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->where('category_id', $this->guide->category_id)
            ->where('id', '!=', $this->guide->id)
            ->orderBy('view_count', 'desc')
            ->limit(6)
            ->get();

        // If we don't have enough related guides, add some from other categories
        if ($related->count() < 3) {
            $additional = Guide::query()
                ->with(['user', 'category'])
                ->where('status', 'published')
                ->where('visibility', 'public')
                ->where('id', '!=', $this->guide->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->orderBy('view_count', 'desc')
                ->limit(6 - $related->count())
                ->get();

            $related = $related->merge($additional);
        }

        return $related;
    }

    public function render()
    {
        return view('livewire.guides.show', [
            'relatedGuides' => $this->relatedGuides,
        ]);
    }
}
