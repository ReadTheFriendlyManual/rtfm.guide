<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.public')]
class ComingSoon extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public bool $subscribed = false;

    public function getMessagesProperty(): array
    {
        return [
            'sfw' => [
                'hero' => __('mode.sfw.hero'),
                'tagline' => __('mode.sfw.tagline'),
                'description' => __('mode.sfw.description'),
            ],
            'nsfw' => [
                'hero' => __('mode.nsfw.hero'),
                'tagline' => __('mode.nsfw.tagline'),
                'description' => __('mode.nsfw.description'),
            ],
        ];
    }

    public function subscribe(): void
    {
        $this->validate();

        // TODO: Store email subscription when newsletter system is ready
        // For now, just show success
        $this->subscribed = true;
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.pages.coming-soon');
    }
}
