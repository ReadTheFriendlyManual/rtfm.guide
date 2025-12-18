<div class="bg-white dark:bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
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
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                @if($category->icon)
                    <span class="text-3xl">{{ $category->icon }}</span>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $category->name }}</h1>
                    @if($category->parent)
                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                            Part of <a href="{{ route('categories.show', $category->parent) }}" class="text-sky-600 dark:text-sky-400 hover:underline">{{ $category->parent->name }}</a>
                        </p>
                    @endif
                </div>
            </div>

            @if($category->description)
                <p class="text-slate-600 dark:text-slate-300 mb-6">{{ $category->description }}</p>
            @endif

            <!-- Subcategories -->
            @if($category->children->count() > 0)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-3">Subcategories</h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach($category->children as $child)
                            <a href="{{ route('categories.show', $child) }}"
                               class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                @if($child->icon)
                                    <span class="mr-2">{{ $child->icon }}</span>
                                @endif
                                {{ $child->name }}
                                <span class="ml-2 text-sm text-slate-500 dark:text-slate-400">({{ $child->guides_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Filters -->
        <div class="mb-6 flex flex-wrap gap-4 items-center">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Difficulty:</label>
                <select wire:model.live="difficulty" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-3 py-1.5 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="">All Levels</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Sort by:</label>
                <select wire:model.live="sortBy" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-3 py-1.5 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="published_at">Newest</option>
                    <option value="views">Most Viewed</option>
                    <option value="title">Title (A-Z)</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>

            @if($difficulty || $sortBy !== 'published_at')
                <button wire:click="clearFilters" class="text-sm text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 font-medium">
                    Clear filters
                </button>
            @endif
        </div>

        <!-- Results Count -->
        <div class="mb-6">
            <p class="text-sm text-slate-600 dark:text-slate-300">
                Showing {{ $guides->count() }} of {{ $guides->total() }} guides
                @if($difficulty)
                    in {{ ucfirst($difficulty) }}
                @endif
            </p>
        </div>

        <!-- Guides Grid -->
        @if($guides->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($guides as $guide)
                    <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">
                                    <a href="{{ route('guides.show', $guide) }}" class="hover:text-sky-600 dark:hover:text-sky-400">
                                        {{ $guide->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-slate-600 dark:text-slate-300 mb-2">
                                    {{ $guide->tldr }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ $guide->user->name }}</span>
                            </div>
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
                                <span>{{ number_format($guide->view_count) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($guide->difficulty === 'beginner') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @elseif($guide->difficulty === 'intermediate') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                    @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @endif">
                                    {{ ucfirst($guide->difficulty) }}
                                </span>
                                @if($guide->category->id !== $category->id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                        {{ $guide->category->name }}
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('guides.show', $guide) }}" class="text-sm text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 font-medium">
                                Read more →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $guides->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707-.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-white">No guides found</h3>
                <p class="mt-2 text-slate-600 dark:text-slate-300">
                    @if($difficulty)
                        No {{ $difficulty }} guides found in this category.
                    @else
                        No guides have been published in this category yet.
                    @endif
                </p>
                @if($difficulty)
                    <div class="mt-6">
                        <button wire:click="clearFilters" class="text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 font-medium">
                            Show all guides
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Back to Categories -->
        <div class="mt-12 text-center">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to all categories
            </a>
                </div>
            </div>
        </div>
    </div>
</div>
