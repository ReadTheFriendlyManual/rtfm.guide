<?php

namespace App\Livewire;

use Livewire\Component;

class ModeToggle extends Component
{
    public bool $isNsfwMode = false;

    public function mount()
    {
        // Initialize with default, will be updated by JavaScript
        $this->isNsfwMode = false;
    }

    public function setInitialMode($isNsfw)
    {
        $this->isNsfwMode = $isNsfw;
        // Dispatch event to update other components
        $this->dispatch('nsfw-mode-changed', ['isNsfw' => $this->isNsfwMode]);
    }

    public function toggleMode()
    {
        $this->isNsfwMode = ! $this->isNsfwMode;

        // Dispatch event to all components that need to know about mode changes
        $this->dispatch('nsfw-mode-changed', ['isNsfw' => $this->isNsfwMode]);

        // Also update localStorage via JavaScript
        $this->dispatch('update-localStorage', ['key' => 'rtfm_mode', 'value' => $this->isNsfwMode ? 'nsfw' : 'sfw']);
    }

    public function render()
    {
        return view('livewire.mode-toggle');
    }
}
