@php
    use Illuminate\Support\Str;
@endphp

<div class="bg-white dark:bg-slate-900">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">
        <!-- RTFM Header -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <span class="text-2xl">📖</span>
                <div class="text-left">
                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">RTFM</p>
                    <p class="text-yellow-700 dark:text-yellow-300">{{ $rtfmMessage }}</p>
                </div>
                <button wire:click="regenerateRtfmMessage" class="ml-2 text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Guide Header -->
        <header class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">{{ $guide->title }}</h1>

            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600 dark:text-slate-300 mb-6">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ $guide->user->name }}</span>
                </div>

                @if($guide->category)
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>{{ $guide->category->name }}</span>
                    </div>
                @endif

                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $guide->estimated_minutes ? $guide->estimated_minutes . ' min read' : 'Quick read' }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>{{ number_format($guide->view_count) }} views</span>
                </div>

                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ $guide->published_at?->diffForHumans() }}</span>
                </div>
            </div>

            <!-- Difficulty Badge -->
            <div class="mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($guide->difficulty === 'beginner') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                    @elseif($guide->difficulty === 'intermediate') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                    @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @endif">
                    {{ ucfirst($guide->difficulty) }}
                </span>
            </div>
        </header>

        <!-- TL;DR Section -->
        @if($guide->tldr)
            <div class="mb-8 p-6 bg-sky-50 dark:bg-sky-900/20 rounded-lg border border-sky-200 dark:border-sky-800">
                <h2 class="flex items-center gap-2 text-lg font-semibold text-sky-900 dark:text-sky-100 mb-3">
                    <span class="text-xl">💡</span>
                    <span>TL;DR</span>
                </h2>
                <p class="text-sky-800 dark:text-sky-200">{{ $guide->tldr }}</p>
            </div>
        @endif

        <!-- Prerequisites -->
        @if($guide->estimated_minutes || $guide->os_tags)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Prerequisites</h2>
                <ul class="space-y-2 text-slate-700 dark:text-slate-300">
                    @if($guide->estimated_minutes)
                        <li class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ $guide->estimated_minutes }} minutes to complete</span>
                        </li>
                    @endif
                    @if($guide->os_tags)
                        <li class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Works on: {{ collect($guide->os_tags)->join(', ') }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif

        <!-- Guide Content -->
        <div class="prose prose-slate dark:prose-invert max-w-none mb-12">
            <div class="text-slate-900 dark:text-slate-100">
                {!! Str::markdown($guide->content) !!}
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between py-6 border-t border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-4">
                <!-- Reactions -->
                <livewire:reactions.guide-reactions :guide="$guide" :key="'reactions-'.$guide->id" />

                <!-- Share -->
                <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                    </svg>
                    Share
                </button>

                <!-- Save -->
                <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    Save
                </button>
            </div>

            <!-- Back to Guides -->
            <a href="{{ route('guides.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Guides
            </a>
        </div>

        <!-- Comments Section -->
        <div class="border-t border-slate-200 dark:border-slate-700 pt-8">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-6">Comments</h2>

            @auth
                <livewire:comments.comment-form :guide="$guide" :key="'comment-form-'.$guide->id" />
            @else
                <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-6 text-center">
                    <p class="text-slate-600 dark:text-slate-300 mb-4">Want to join the discussion?</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition-colors">
                        Sign in to comment
                    </a>
                </div>
            @endauth

            <livewire:comments.comment-list :guide="$guide" :key="'comment-list-'.$guide->id" />
        </div>

        <!-- Related Guides -->
        @if($relatedGuides->count() > 0)
            <div class="border-t border-slate-200 dark:border-slate-700 pt-8 mt-8">
                <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-6">Related Guides</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedGuides as $relatedGuide)
                        <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-4 hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
                                <a href="{{ route('guides.show', $relatedGuide) }}" class="hover:text-sky-600 dark:hover:text-sky-400">
                                    {{ $relatedGuide->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mb-3 line-clamp-2">
                                {{ $relatedGuide->tldr }}
                            </p>
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                <span>{{ $relatedGuide->user->name }}</span>
                                <span>{{ number_format($relatedGuide->view_count) }} views</span>
                            </div>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($relatedGuide->difficulty === 'beginner') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @elseif($relatedGuide->difficulty === 'intermediate') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                    @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @endif">
                                    {{ ucfirst($relatedGuide->difficulty) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif>
        </main>
    </div>
</div>
