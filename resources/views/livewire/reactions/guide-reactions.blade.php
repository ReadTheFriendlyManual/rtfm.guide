@php
    use App\Enums\ReactionType;
@endphp

<div class="flex flex-wrap gap-2">
    @foreach(ReactionType::cases() as $reactionType)
        @php
            $isActive = $userReactions->contains($reactionType->value);
            $count = $reactionCounts[$reactionType->value] ?? 0;
            $color = $reactionType->color();
        @endphp

        <button
            wire:click="toggleReaction('{{ $reactionType->value }}')"
            wire:loading.attr="disabled"
            wire:target="toggleReaction"
            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg border transition-all duration-200
                @if($isActive)
                    @if($color === 'green') bg-green-100 dark:bg-green-900 border-green-300 dark:border-green-700 text-green-800 dark:text-green-200
                    @elseif($color === 'blue') bg-blue-100 dark:bg-blue-900 border-blue-300 dark:border-blue-700 text-blue-800 dark:text-blue-200
                    @elseif($color === 'yellow') bg-yellow-100 dark:bg-yellow-900 border-yellow-300 dark:border-yellow-700 text-yellow-800 dark:text-yellow-200
                    @else bg-orange-100 dark:bg-orange-900 border-orange-300 dark:border-orange-700 text-orange-800 dark:text-orange-200
                    @endif
                @else
                    bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700
                @endif"
            title="{{ $reactionType->label() }}"
        >
            <span class="text-base">{{ $reactionType->icon() }}</span>
            <span class="min-w-[1.5rem] text-center">{{ $count }}</span>
            <span class="sr-only">{{ $reactionType->label() }}</span>

            <span wire:loading wire:target="toggleReaction" class="ml-1">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </button>
    @endforeach

    @auth
        <div class="ml-4 text-sm text-slate-500 dark:text-slate-400 self-center">
            Tell us what you think!
        </div>
    @else
        <div class="ml-4 text-sm text-slate-500 dark:text-slate-400 self-center">
            <a href="{{ route('login') }}" class="text-sky-600 dark:text-sky-400 hover:underline">Sign in</a> to react
        </div>
    @endauth
</div>
