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
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Categories</h1>
            <p class="mt-2 text-slate-600 dark:text-slate-300">Browse guides by technology, framework, and topic.</p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $category)
                <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        @if($category->icon)
                            <div class="text-2xl">{{ $category->icon }}</div>
                        @endif
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900 dark:text-white">
                                <a href="{{ route('categories.show', $category) }}" class="hover:text-sky-600 dark:hover:text-sky-400">
                                    {{ $category->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $category->guides_count }} guides</p>
                        </div>
                    </div>

                    @if($category->description)
                        <p class="text-slate-700 dark:text-slate-300 mb-4">{{ $category->description }}</p>
                    @endif

                    <!-- Subcategories -->
                    @if($category->children->count() > 0)
                        <div class="space-y-2">
                            <h4 class="text-sm font-medium text-slate-900 dark:text-white">Subcategories:</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($category->children as $child)
                                    <a href="{{ route('categories.show', $child) }}"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                                        {{ $child->name }}
                                        <span class="ml-1 text-slate-600 dark:text-slate-400">({{ $child->guides_count }})</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('categories.show', $category) }}" class="text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 font-medium text-sm">
                            Browse {{ $category->name }} guides →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($categories->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-white">No categories yet</h3>
                <p class="mt-2 text-slate-600 dark:text-slate-300">Categories will appear here as guides are added.</p>
            </div>
        @endif>
    </div>
</div>
