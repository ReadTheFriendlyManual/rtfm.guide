@php
    use Illuminate\Support\Str;
@endphp

<x-layouts.public :title="__('Guides')">
    <section class="mx-auto max-w-6xl space-y-10 px-4 py-12">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="space-y-2">
                <flux:heading size="xl" class="flex items-center gap-2">
                    <flux:icon name="layout-grid" class="h-6 w-6 text-amber-400" />
                    {{ __('RTFM.guide Library') }}
                </flux:heading>
                <p class="text-sm text-zinc-500 dark:text-zinc-300">
                    {{ __('You should have read the manual. We did it for you anyway—search, filter, and ship faster.') }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <flux:button wire:click="feelingLucky" icon="sparkles" variant="primary">
                    {{ __('Feeling lucky') }}
                </flux:button>

                <flux:button href="{{ route('coming-soon') }}" variant="ghost" icon="bell">
                    {{ __('Get launch alerts') }}
                </flux:button>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200/70 bg-white/60 p-4 shadow-sm backdrop-blur dark:border-zinc-700/60 dark:bg-zinc-900/60">
            <div class="grid gap-3 md:grid-cols-3">
                <flux:input
                    wire:model.live="search"
                    icon="magnifying-glass"
                    :placeholder="__('Search guides, tags, or OS...')"
                    class="md:col-span-2"
                />

                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 rounded-xl border border-zinc-200/70 bg-zinc-50 px-3 py-2 text-sm dark:border-zinc-700/70 dark:bg-zinc-800">
                        <span class="text-xs font-medium text-zinc-500">{{ __('Difficulty') }}</span>
                        <select
                            wire:model.live="difficulty"
                            class="w-full rounded-lg border border-zinc-200 bg-white px-2 py-1 text-sm focus:border-amber-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900"
                        >
                            <option value="">{{ __('Any') }}</option>
                            <option value="beginner">{{ __('Beginner') }}</option>
                            <option value="intermediate">{{ __('Intermediate') }}</option>
                            <option value="advanced">{{ __('Advanced') }}</option>
                        </select>
                    </label>

                    <label class="flex items-center gap-2 rounded-xl border border-zinc-200/70 bg-zinc-50 px-3 py-2 text-sm dark:border-zinc-700/70 dark:bg-zinc-800">
                        <span class="text-xs font-medium text-zinc-500">{{ __('OS / Stack') }}</span>
                        <select
                            wire:model.live="os"
                            class="w-full rounded-lg border border-zinc-200 bg-white px-2 py-1 text-sm focus:border-amber-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900"
                        >
                            <option value="">{{ __('Any') }}</option>
                            <option value="linux">Linux</option>
                            <option value="macos">macOS</option>
                            <option value="windows">Windows</option>
                            <option value="docker">Docker</option>
                            <option value="laravel">Laravel</option>
                        </select>
                    </label>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <div class="flex flex-wrap gap-2">
                    <flux:badge
                        variant="neutral"
                        size="sm"
                        class="@class(['cursor-pointer', $category === null ? 'ring-2 ring-amber-400' : ''])"
                        wire:click="$set('category', null)"
                        wire:key="category-any"
                    >
                        {{ __('All categories') }}
                    </flux:badge>

                    @foreach ($categories as $categoryOption)
                        <flux:badge
                            variant="outline"
                            size="sm"
                            class="@class(['cursor-pointer', $category === $categoryOption->slug ? 'ring-2 ring-amber-400' : ''])"
                            wire:click="$set('category', '{{ $categoryOption->slug }}')"
                            wire:key="category-{{ $categoryOption->id }}"
                        >
                            {{ $categoryOption->name }}
                        </flux:badge>
                    @endforeach
                </div>

                <div class="ms-auto flex items-center gap-3">
                    @auth
                        <label class="inline-flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-200">
                            <input type="checkbox" wire:model.live="showSavedOnly" class="rounded border-zinc-300 text-amber-500 focus:ring-amber-500 dark:border-zinc-700" />
                            {{ __('My saved guides') }}
                        </label>
                    @endauth
                    <flux:button size="sm" variant="ghost" icon="arrow-path" wire:click="resetFilters">
                        {{ __('Reset filters') }}
                    </flux:button>
                </div>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[2fr,1fr]">
            <div class="space-y-4">
                @forelse ($guides as $guide)
                    <article
                        wire:key="guide-{{ $guide->id }}"
                        class="rounded-2xl border border-zinc-200/80 bg-white/70 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-700/80 dark:bg-zinc-900/70"
                    >
                        <div class="flex flex-wrap items-start gap-3">
                            <div class="flex-1 space-y-1">
                                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                                    <a href="{{ route('guides.show', $guide) }}" class="hover:text-amber-500" wire:navigate>
                                        {{ $guide->title }}
                                    </a>
                                </h2>
                                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                                    {{ $guide->tldr }}
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <flux:badge variant="neutral">{{ ucfirst($guide->difficulty) }}</flux:badge>
                                    <flux:badge variant="outline">{{ __('~:minutes min', ['minutes' => $guide->estimated_minutes]) }}</flux:badge>
                                    @foreach ($guide->os_tags as $tag)
                                        <flux:badge variant="outline">{{ Str::title($tag) }}</flux:badge>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2 text-sm text-zinc-500 dark:text-zinc-300">
                                <span>{{ __('Views: :count', ['count' => number_format($guide->view_count)]) }}</span>
                                <span>{{ __('Shares: :count', ['count' => number_format($guide->share_count)]) }}</span>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-300">
                                <span>{{ $guide->category?->name }}</span>
                                <span aria-hidden="true">•</span>
                                <span>{{ $guide->published_at?->diffForHumans() }}</span>
                            </div>
                            <div class="flex gap-2">
                                <flux:button href="{{ route('guides.show', $guide) }}" variant="ghost" icon="arrow-right" wire:navigate>
                                    {{ __('Open') }}
                                </flux:button>
                            </div>
                        </div>
                    </article>
                @empty
                    <flux:callout icon="sparkles" title="{{ __('No guides found (yet)') }}">
                        <p class="text-sm text-zinc-600 dark:text-zinc-200">
                            {{ __('Try a different filter or clear the search. New guides arrive daily once the community starts contributing.') }}
                        </p>
                    </flux:callout>
                @endforelse
            </div>

            <aside class="space-y-4">
                <div class="rounded-2xl border border-amber-200/60 bg-amber-50/70 p-5 text-amber-900 shadow-sm dark:border-amber-700/50 dark:bg-amber-900/30 dark:text-amber-50">
                    <p class="text-xs font-semibold uppercase tracking-wide">{{ __('RTFM Headline') }}</p>
                    <p class="mt-2 text-lg font-semibold">
                        {{ __('Docs read so you do not have to—pick a guide and ship confidently.') }}
                    </p>
                    @if ($luckyGuide)
                        <flux:button class="mt-3" variant="primary" wire:click="feelingLucky" icon="sparkles">
                            {{ __('Surprise me') }}
                        </flux:button>
                    @endif
                </div>

                <div class="rounded-2xl border border-zinc-200/80 bg-white/70 p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/70">
                    <div class="mb-3 flex items-center gap-2">
                        <flux:icon name="fire" class="h-5 w-5 text-amber-500" />
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Trending now') }}</h3>
                    </div>

                    <div class="space-y-3">
                        @foreach ($this->trendingGuides as $trendingGuide)
                            <a
                                wire:key="trending-{{ $trendingGuide->id }}"
                                href="{{ route('guides.show', $trendingGuide) }}"
                                class="block rounded-xl border border-zinc-200/70 px-3 py-2 hover:border-amber-300 dark:border-zinc-700/70 dark:hover:border-amber-400"
                                wire:navigate
                            >
                                <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $trendingGuide->title }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-300">
                                    {{ __(':views views', ['views' => number_format($trendingGuide->view_count)]) }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-layouts.public>
