<?php

namespace App\Livewire\Reactions;

use App\Enums\ReactionType;
use App\Models\Guide;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GuideReactions extends Component
{
    public Guide $guide;

    public function mount(Guide $guide)
    {
        $this->guide = $guide;
    }

    public function toggleReaction(ReactionType $reactionType)
    {
        if (! Auth::check()) {
            $this->dispatch('reaction-error', 'You must be logged in to react to guides.');

            return;
        }

        $existingReaction = Reaction::where('guide_id', $this->guide->id)
            ->where('user_id', Auth::id())
            ->where('type', $reactionType)
            ->first();

        if ($existingReaction) {
            // Remove the reaction
            $existingReaction->delete();
        } else {
            // Add the reaction
            Reaction::create([
                'guide_id' => $this->guide->id,
                'user_id' => Auth::id(),
                'type' => $reactionType,
            ]);
        }

        // Refresh the guide with updated reactions
        $this->guide->refresh();
        $this->guide->load('reactions');
    }

    public function getReactionCountsProperty()
    {
        return collect(ReactionType::cases())->mapWithKeys(function ($type) {
            return [$type->value => $this->guide->reactions->where('type', $type)->count()];
        });
    }

    public function getUserReactionsProperty()
    {
        if (! Auth::check()) {
            return collect();
        }

        return $this->guide->reactions
            ->where('user_id', Auth::id())
            ->pluck('type')
            ->map(fn ($type) => $type->value);
    }

    public function render()
    {
        return view('livewire.reactions.guide-reactions', [
            'reactionCounts' => $this->reactionCounts,
            'userReactions' => $this->userReactions,
        ]);
    }
}
