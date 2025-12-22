@php
    use Illuminate\Support\Str;
@endphp

<x-layouts.public :title="$guide->title">
    <section class="mx-auto max-w-5xl space-y-8 px-4 py-12">
        <div class="rounded-2xl border border-amber-200/70 bg-gradient-to-br from-amber-50/90 via-white to-amber-100/70 p-6 shadow-sm dark:border-amber-700/50 dark:from-amber-900/30 dark:via-zinc-900 dark:to-amber-900/20">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-300">
                        {{ __('RTFM pep talk') }}
                    </p>
                    <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ $guide->title }}
                    </h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-200">
                        {{ $rtfmMessage }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <flux:button
                        wire:click="toggleSave"
                        variant="{{ $saved ? 'primary' : 'ghost' }}"
                        icon="{{ $saved ? 'bookmark' : 'bookmark' }}"
                    >
                        {{ $saved ? __('Saved') : __('Save for later') }}
                    </flux:button>

                    <flux:button wire:click="react('helpful')" variant="{{ $reactionType === 'helpful' ? 'primary' : 'ghost' }}">
                        {{ __('Helpful') }}
                    </flux:button>
                    <flux:button wire:click="react('saved_me')" variant="{{ $reactionType === 'saved_me' ? 'primary' : 'ghost' }}">
                        {{ __('Saved me') }}
                    </flux:button>
                    <flux:button wire:click="react('outdated')" variant="{{ $reactionType === 'outdated' ? 'primary' : 'ghost' }}">
                        {{ __('Outdated') }}
                    </flux:button>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2 text-xs text-zinc-600 dark:text-zinc-300">
                <flux:badge variant="neutral">{{ ucfirst($guide->difficulty) }}</flux:badge>
                <flux:badge variant="outline">{{ __(':minutes min', ['minutes' => $guide->estimated_minutes]) }}</flux:badge>
                @foreach ($guide->os_tags as $tag)
                    <flux:badge variant="outline">{{ Str::title($tag) }}</flux:badge>
                @endforeach
                <span aria-hidden="true">•</span>
                <span>{{ __('Views: :count', ['count' => number_format($guide->view_count)]) }}</span>
                <span aria-hidden="true">•</span>
                <span>{{ __('Shares: :count', ['count' => number_format($guide->share_count)]) }}</span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
            <article class="space-y-6 rounded-2xl border border-zinc-200/80 bg-white/80 p-6 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/70">
                <div class="rounded-xl border border-emerald-200/60 bg-emerald-50/70 p-4 dark:border-emerald-700/50 dark:bg-emerald-900/30">
                    <h2 class="text-sm font-semibold text-emerald-800 dark:text-emerald-100">{{ __('TL;DR') }}</h2>
                    <p class="mt-2 text-zinc-800 dark:text-zinc-100">
                        {{ $guide->tldr }}
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Prerequisites') }}</h3>
                        <ul class="space-y-2 text-sm text-zinc-700 dark:text-zinc-200">
                            @forelse (collect($guide->prerequisites) as $prerequisite)
                                <li class="rounded-lg border border-zinc-200/70 bg-zinc-50 px-3 py-2 dark:border-zinc-700/60 dark:bg-zinc-800/70">
                                    <p class="font-semibold text-zinc-900 dark:text-white">{{ $prerequisite['label'] ?? __('Requirement') }}</p>
                                    <p class="text-xs text-zinc-600 dark:text-zinc-300">{{ $prerequisite['summary'] ?? '' }}</p>
                                </li>
                            @empty
                                <li class="text-xs text-zinc-500 dark:text-zinc-300">{{ __('No prerequisites listed.') }}</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Troubleshooting') }}</h3>
                        <ul class="space-y-2 text-sm text-zinc-700 dark:text-zinc-200">
                            @forelse (collect($guide->troubleshooting) as $item)
                                <li class="rounded-lg border border-zinc-200/70 bg-zinc-50 px-3 py-2 dark:border-zinc-700/60 dark:bg-zinc-800/70">
                                    <p class="font-semibold text-zinc-900 dark:text-white">{{ $item['label'] ?? __('Issue') }}</p>
                                    <p class="text-xs text-zinc-600 dark:text-zinc-300">{{ $item['summary'] ?? '' }}</p>
                                </li>
                            @empty
                                <li class="text-xs text-zinc-500 dark:text-zinc-300">{{ __('No known issues yet.') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="space-y-3 text-sm leading-relaxed text-zinc-800 dark:text-zinc-100">
                    {!! $guide->contentAsHtml() !!}
                </div>
            </article>

            <aside class="space-y-4">
                <div class="rounded-2xl border border-zinc-200/80 bg-white/80 p-5 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/70">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Guide metadata') }}</h3>
                        <flux:badge variant="neutral">{{ $guide->category?->name }}</flux:badge>
                    </div>
                    <dl class="mt-3 space-y-2 text-sm text-zinc-700 dark:text-zinc-200">
                        <div class="flex items-center justify-between">
                            <dt>{{ __('Published') }}</dt>
                            <dd>{{ $guide->published_at?->toFormattedDateString() ?? __('Draft') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt>{{ __('Visibility') }}</dt>
                            <dd class="capitalize">{{ $guide->visibility }}</dd>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            @foreach ($guide->os_tags as $tag)
                                <flux:badge variant="outline">{{ Str::title($tag) }}</flux:badge>
                            @endforeach
                        </div>
                    </dl>
                </div>

                <div class="rounded-2xl border border-zinc-200/80 bg-white/80 p-5 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/70">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Related guides') }}</h3>
                    <div class="mt-3 space-y-2">
                        @forelse ($relatedGuides as $related)
                            <a
                                href="{{ route('guides.show', $related) }}"
                                class="block rounded-xl border border-zinc-200/70 px-3 py-2 hover:border-amber-300 dark:border-zinc-700/70 dark:hover:border-amber-400"
                                wire:navigate
                            >
                                <p class="text-sm font-semibold text-zinc-900 dark:text-white">
                                    {{ $related->title }}
                                </p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-300">
                                    {{ __(':minutes min · :category', ['minutes' => $related->estimated_minutes, 'category' => $related->category?->name]) }}
                                </p>
                            </a>
                        @empty
                            <p class="text-xs text-zinc-500 dark:text-zinc-300">{{ __('Nothing related yet—publish more!') }}</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-layouts.public>
