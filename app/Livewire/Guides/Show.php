<?php

namespace App\Livewire\Guides;

use App\Models\Guide;
use App\Models\Reaction;
use App\Models\RtfmMessage;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Guide $guide;

    public string $rtfmMessage = '';

    public bool $saved = false;

    public ?string $reactionType = null;

    public Collection $relatedGuides;

    public function mount(Guide $guide): void
    {
        $this->guide = $guide->load(['author', 'category']);
        $this->rtfmMessage = $this->resolveMessage();
        $this->saved = auth()->check()
            && $this->guide->savedBy()->where('users.id', auth()->id())->exists();
        $this->reactionType = auth()->check()
            ? $this->guide->reactions()->where('user_id', auth()->id())->value('type')
            : null;
        $this->relatedGuides = $this->guide->related();

        $this->guide->increment('view_count');
    }

    public function toggleSave(): void
    {
        abort_unless(auth()->check(), 403);

        if ($this->saved) {
            $this->guide->savedBy()->detach(auth()->id());
            $this->saved = false;

            return;
        }

        $this->guide->savedBy()->syncWithoutDetaching([auth()->id()]);
        $this->saved = true;
    }

    public function react(string $type): void
    {
        abort_unless(auth()->check(), 403);
        abort_unless(in_array($type, Reaction::allowedTypes(), true), 422);

        $existingReaction = $this->guide->reactions()
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReaction !== null && $existingReaction->type === $type) {
            $existingReaction->delete();
            $this->reactionType = null;

            return;
        }

        $this->guide->reactions()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['type' => $type],
        );

        $this->reactionType = $type;
    }

    protected function resolveMessage(): string
    {
        $message = RtfmMessage::query()->approved()->inRandomOrder()->first();

        if ($message !== null) {
            $message->recordUsage();

            return $message->message;
        }

        return __('You should have RTFM, but we did it for you.');
    }

    public function toJSON(): string
    {
        return $this->guide
            ->loadMissing(['author', 'category'])
            ->loadCount(['reactions as reactions_count', 'savedBy as saves_count'])
            ->toJson(JSON_PRETTY_PRINT);
    }

    #[Layout('components.layouts.public')]
    public function render(): View
    {
        return view('livewire.guides.show', [
            'relatedGuides' => $this->relatedGuides ?? collect(),
        ]);
    }
}
