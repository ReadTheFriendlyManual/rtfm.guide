<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Developer Guides</h1>
        <p class="mt-2 text-slate-600 dark:text-slate-300">Find solutions to common development problems and learn new skills.</p>
    </div>

    <!-- Search and Filters -->
    <div class="mb-8 space-y-4">
        <!-- Search Bar -->
        <div class="relative">
            <flux:input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search guides..."
                class="w-full pl-10 pr-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-4 items-center">
            <!-- Category Filter -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Category:</label>
                <select wire:model.live="categoryId" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-3 py-1.5 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @if($category->children->count() > 0)
                            @foreach($category->children as $child)
                                <option value="{{ $child->id }}">└─ {{ $child->name }}</option>
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Difficulty Filter -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Difficulty:</label>
                <select wire:model.live="difficulty" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-3 py-1.5 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="">All Levels</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>

            <!-- Sort By -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Sort by:</label>
                <select wire:model.live="sortBy" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm px-3 py-1.5 focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="published_at">Newest</option>
                    <option value="views">Most Viewed</option>
                    <option value="title">Title (A-Z)</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>

            <!-- Clear Filters -->
            @if($search || $categoryId || $difficulty || $sortBy !== 'published_at')
                <flux:button wire:click="clearFilters" variant="ghost" size="sm">
                    Clear Filters
                </flux:button>
            @endif
        </div>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-sm text-slate-600 dark:text-slate-300">
            Showing {{ $guides->count() }} of {{ $guides->total() }} guides
            @if($search)
                for "{{ $search }}"
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
                        <span class="flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $guide->user->name }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $guide->estimated_minutes ? $guide->estimated_minutes . ' min' : 'Quick read' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ number_format($guide->view_count) }}
                        </span>
                        @php
                            $helpfulCount = $guide->reactions->where('type', 'helpful')->count();
                            $savedMeCount = $guide->reactions->where('type', 'saved_me')->count();
                        @endphp
                        @if($helpfulCount > 0 || $savedMeCount > 0)
                            <span class="flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                {{ $helpfulCount + $savedMeCount }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if($guide->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                    {{ $guide->category->name }}
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($guide->difficulty === 'beginner') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                @elseif($guide->difficulty === 'intermediate') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @endif">
                                {{ ucfirst($guide->difficulty) }}
                            </span>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-white">No guides found</h3>
            <p class="mt-2 text-slate-600 dark:text-slate-300">
                @if($search || $categoryId || $difficulty)
                    Try adjusting your search or filters.
                @else
                    Check back later for new guides.
                @endif
            </p>
            @if($search || $categoryId || $difficulty)
                <div class="mt-6">
                    <flux:button wire:click="clearFilters" variant="outline">
                        Clear all filters
                    </flux:button>
                </div>
            @endif
        </div>
    @endif
</div>
