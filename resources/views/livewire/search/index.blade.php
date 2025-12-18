@php
    use Illuminate\Support\Str;
@endphp

<div class="bg-white dark:bg-slate-900">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">
            <header class="mx-auto flex max-w-7xl items-center justify-between py-8">
                <a href="{{ route('home') }}" class="text-xl font-bold text-slate-900 dark:text-white">
                    RTFM<span class="text-sky-600 dark:text-sky-400">.guide</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden items-center gap-6 md:flex">
                    <a href="{{ route('guides.index') }}" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white {{ request()->routeIs('guides.*') ? 'text-slate-900 dark:text-white font-medium' : '' }}">Guides</a>
                    <a href="{{ route('categories.index') }}" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white {{ request()->routeIs('categories.*') ? 'text-slate-900 dark:text-white font-medium' : '' }}">Categories</a>
                    <a href="{{ route('search.index') }}" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white {{ request()->routeIs('search.*') ? 'text-slate-900 dark:text-white font-medium' : '' }}">Search</a>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" class="rounded-lg p-2 text-slate-600 transition-colors hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-white/5" title="Toggle theme">
                        <svg x-show="theme === 'dark'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-show="theme === 'light'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- SFW/NSFW Toggle -->
                    <button @click="toggleMode()" class="group relative inline-flex items-center gap-2 rounded-full p-1 text-sm font-medium text-slate-500 transition-colors focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:text-slate-400 dark:focus:ring-offset-slate-900">
                        <span class="pl-2 transition-colors" :class="mode === 'sfw' ? 'text-slate-900 dark:text-white' : ''">SFW</span>
                        <div class="relative h-6 w-11 rounded-full bg-slate-200 transition-colors dark:bg-slate-700" :class="mode === 'nsfw' ? 'bg-sky-500 dark:bg-sky-500' : ''">
                            <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow-sm ring-1 ring-black/5 transition-transform duration-300" :class="mode === 'nsfw' ? 'translate-x-5' : ''"></div>
                        </div>
                        <span class="pr-2 transition-colors" :class="mode === 'nsfw' ? 'text-slate-900 dark:text-white' : ''">NSFW</span>
                    </button>

                    <!-- Auth Links -->
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-sky-500">
                            Sign In
                        </a>
                    @endauth
                </div>
            </header>
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Search Guides</h1>
            <p class="mt-2 text-slate-600 dark:text-slate-300">Find the perfect guide for your development needs.</p>
        </div>

        <!-- Search Input -->
        <div class="mb-8">
            <div class="relative">
                <flux:input
                    wire:model.live.debounce.300ms="query"
                    type="text"
                    placeholder="Search for guides, topics, or technologies..."
                    class="w-full pl-12 pr-4 py-4 text-lg rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                />
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 018 0z" />
                    </svg>
                </div>
                @if($query)
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <button wire:click="$set('query', '')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Search Results -->
        @if($query)
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                    Search Results
                    <span class="text-sm font-normal text-slate-600 dark:text-slate-300">
                        ({{ count($results) }} results for "{{ $query }}")
                    </span>
                </h2>
            </div>

            @if(count($results) > 0)
                <div class="space-y-6">
                    @foreach($results as $guide)
                        <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">
                                        <a href="{{ route('guides.show', $guide) }}" class="hover:text-sky-600 dark:hover:text-sky-400">
                                            {{ $guide->title }}
                                        </a>
                                    </h3>
                                    <p class="text-slate-700 dark:text-slate-300 mb-3 line-clamp-2">{{ $guide->tldr }}</p>
                                </div>
                            </div>

                            <!-- Preview Content -->
                            @if($guide->content)
                                <div class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-3 prose prose-sm prose-slate dark:prose-invert max-w-none">
                                    {!! Str::limit(strip_tags(Str::markdown($guide->content)), 200) !!}
                                </div>
                            @endif

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ $guide->user->name }}</span>
                                    </div>

                                    @if($guide->category)
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            <span>{{ $guide->category->name }}</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $guide->estimated_minutes ? $guide->estimated_minutes . ' min' : 'Quick read' }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>{{ number_format($guide->view_count) }} views</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($guide->difficulty === 'beginner') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                        @elseif($guide->difficulty === 'intermediate') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                        @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @endif">
                                        {{ ucfirst($guide->difficulty) }}
                                    </span>
                                    <a href="{{ route('guides.show', $guide) }}" class="text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 font-medium">
                                        Read more →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(count($results) >= 20)
                    <div class="mt-8 text-center">
                        <p class="text-slate-600 dark:text-slate-300">Showing top 20 results. Try refining your search for more specific results.</p>
                    </div>
                @endif
            @else
                <!-- No Results -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-white">No guides found</h3>
                    <p class="mt-2 text-slate-600 dark:text-slate-300">
                        We couldn't find any guides matching "{{ $query }}".
                    </p>
                    <div class="mt-6">
                        <flux:button wire:click="$set('query', '')" variant="outline">
                            Clear search
                        </flux:button>
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 018 0z" />
                </svg>
                <h3 class="mt-4 text-xl font-medium text-slate-900 dark:text-white">Search RTFM Guides</h3>
                <p class="mt-2 text-slate-600 dark:text-slate-300 max-w-md mx-auto">
                    Start typing to search through our comprehensive collection of developer guides.
                    Find solutions for common problems, learn new technologies, and level up your skills.
                </p>
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto text-sm">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                        <h4 class="font-medium text-slate-900 dark:text-white mb-2">🔍 Smart Search</h4>
                        <p class="text-slate-600 dark:text-slate-300">Typo-tolerant search with instant results</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                        <h4 class="font-medium text-slate-900 dark:text-white mb-2">📚 Rich Content</h4>
                        <p class="text-slate-600 dark:text-slate-300">Guides with code examples and step-by-step instructions</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                        <h4 class="font-medium text-slate-900 dark:text-white mb-2">🏷️ Categorized</h4>
                        <p class="text-slate-600 dark:text-slate-300">Organized by technology, difficulty, and topic</p>
                    </div>
                </div>
            </div>
        @endif>
    </div>
</div>
