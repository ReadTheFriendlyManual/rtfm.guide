<template>
    <PublicLayout>
        <!-- Search Header -->
        <div class="bg-linear-to-br from-pearl-50 via-white to-pearl-100 dark:from-pearl-950 dark:via-pearl-900 dark:to-pearl-900 border-b-2 border-pearl-200 dark:border-pearl-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="font-display text-4xl/tight sm:text-5xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-6">
                    Search Guides
                </h1>

                <!-- Search Input -->
                <div class="relative max-w-3xl">
                    <input
                        v-model="localQuery"
                        @input="handleSearch"
                        type="search"
                        placeholder="Search for guides... (try 'nginx', 'restart', 'ssl')"
                        class="w-full px-6 py-4 pr-12 rounded-2xl border-2 border-pearl-300 dark:border-pearl-600
                               bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50
                               focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500
                               transition-all text-lg/relaxed
                               placeholder:text-pearl-400 dark:placeholder:text-pearl-500"
                        autofocus
                    />
                    <svg class="absolute right-4 top-1/2 -translate-y-1/2 size-6 text-pearl-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Search Stats -->
                <p v-if="query && results.data" class="mt-4 text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                    Found <span class="font-semibold text-wine-600 dark:text-wine-400">{{ results.total }}</span>
                    {{ results.total === 1 ? 'guide' : 'guides' }} for "{{ query }}"
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filters Sidebar -->
                <aside class="lg:w-64 shrink-0">
                    <div class="sticky top-20 space-y-6">
                        <!-- Category Filter -->
                        <div v-if="categories.length && query">
                            <h3 class="font-display text-sm/tight font-bold text-pearl-900 dark:text-pearl-50 mb-3">Filter by Category</h3>
                            <div class="space-y-2">
                                <button
                                    @click="toggleFilter('category', null)"
                                    :class="[
                                        'w-full text-left px-4 py-2 rounded-xl font-medium transition-all',
                                        !localFilters.category
                                            ? 'bg-wine-600 text-white'
                                            : 'text-pearl-700 dark:text-pearl-300 hover:bg-pearl-100 dark:hover:bg-pearl-800'
                                    ]"
                                >
                                    All Categories
                                </button>
                                <button
                                    v-for="category in categories"
                                    :key="category.id"
                                    @click="toggleFilter('category', category.slug)"
                                    :class="[
                                        'w-full text-left px-4 py-2 rounded-xl font-medium transition-all',
                                        localFilters.category === category.slug
                                            ? 'bg-wine-600 text-white'
                                            : 'text-pearl-700 dark:text-pearl-300 hover:bg-pearl-100 dark:hover:bg-pearl-800'
                                    ]"
                                >
                                    {{ category.name }}
                                </button>
                            </div>
                        </div>

                        <!-- Difficulty Filter -->
                        <div v-if="query">
                            <h3 class="font-display text-sm/tight font-bold text-pearl-900 dark:text-pearl-50 mb-3">Filter by Difficulty</h3>
                            <div class="space-y-2">
                                <button
                                    v-for="difficulty in ['beginner', 'intermediate', 'advanced']"
                                    :key="difficulty"
                                    @click="toggleFilter('difficulty', difficulty)"
                                    :class="[
                                        'w-full text-left px-4 py-2 rounded-xl font-medium transition-all capitalize',
                                        localFilters.difficulty === difficulty
                                            ? 'bg-wine-600 text-white'
                                            : 'text-pearl-700 dark:text-pearl-300 hover:bg-pearl-100 dark:hover:bg-pearl-800'
                                    ]"
                                >
                                    {{ difficulty }}
                                </button>
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        <button
                            v-if="hasActiveFilters"
                            @click="clearFilters"
                            class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600
                                   text-pearl-700 dark:text-pearl-300 hover:bg-pearl-100 dark:hover:bg-pearl-800
                                   font-semibold transition-all"
                        >
                            Clear Filters
                        </button>
                    </div>
                </aside>

                <!-- Results -->
                <div class="flex-1 min-h-[60vh] flex flex-col">
                    <!-- Results Grid -->
                    <div v-if="results.data && results.data.length" class="space-y-6 flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <Link
                                v-for="guide in results.data"
                                :key="guide.id"
                                :href="`/guides/${guide.slug}`"
                                class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-6
                                       bg-white dark:bg-pearl-800 hover:border-wine-400 dark:hover:border-wine-600
                                       transition-all duration-300 hover:shadow-lg hover:shadow-wine-600/10"
                            >
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xs/relaxed font-semibold px-3 py-1 rounded-lg
                                                 bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400">
                                        {{ guide.category }}
                                    </span>
                                    <span :class="[
                                        'text-xs/relaxed font-semibold px-3 py-1 rounded-lg capitalize',
                                        guide.difficulty === 'beginner' ? 'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400' :
                                        guide.difficulty === 'intermediate' ? 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400' :
                                        'bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400'
                                    ]">
                                        {{ guide.difficulty }}
                                    </span>
                                </div>

                                <h2 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2
                                           group-hover:text-wine-600 dark:group-hover:text-wine-400 transition-colors"
                                    v-html="highlightQuery(guide.title)">
                                </h2>

                                <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400 mb-4"
                                   v-html="highlightQuery(guide.tldr)">
                                </p>

                                <div class="flex items-center gap-4 text-xs/relaxed text-pearl-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ guide.view_count || 0 }} views
                                    </span>
                                </div>
                            </Link>
                        </div>

                        <!-- Pagination -->
                        <div v-if="results.last_page > 1" class="flex items-center justify-center gap-2 mt-8">
                            <Link
                                v-if="results.prev_page_url"
                                :href="results.prev_page_url"
                                class="px-4 py-2 rounded-xl border-2 border-pearl-300 dark:border-pearl-600
                                       text-pearl-700 dark:text-pearl-300 hover:border-wine-500
                                       font-medium transition-all"
                            >
                                Previous
                            </Link>
                            <span class="text-sm/relaxed text-pearl-600 dark:text-pearl-400 px-4">
                                Page {{ results.current_page }} of {{ results.last_page }}
                            </span>
                            <Link
                                v-if="results.next_page_url"
                                :href="results.next_page_url"
                                class="px-4 py-2 rounded-xl border-2 border-pearl-300 dark:border-pearl-600
                                       text-pearl-700 dark:text-pearl-300 hover:border-wine-500
                                       font-medium transition-all"
                            >
                                Next
                            </Link>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else-if="query" class="flex-1 flex items-center justify-center">
                        <div class="text-center py-16">
                            <svg class="size-16 mx-auto text-pearl-300 dark:text-pearl-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
                                No guides found
                            </h3>
                            <p class="text-pearl-600 dark:text-pearl-400 mb-4">
                                Try different keywords or clear your filters
                            </p>
                            <button
                                v-if="hasActiveFilters"
                                @click="clearFilters"
                                class="inline-flex items-center px-6 py-3 rounded-xl bg-wine-600 hover:bg-wine-700
                                       text-white font-semibold transition-all shadow-lg shadow-wine-600/30"
                            >
                                Clear Filters
                            </button>
                        </div>
                    </div>

                    <!-- Initial State - Show Popular Guides -->
                    <div v-else class="flex-1">
                        <div class="text-center mb-8">
                            <svg class="size-12 mx-auto text-pearl-300 dark:text-pearl-700 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
                                Start searching
                            </h3>
                            <p class="text-pearl-600 dark:text-pearl-400">
                                Enter a search term above, or browse popular guides below
                            </p>
                        </div>

                        <!-- Popular Guides -->
                        <div v-if="popularGuides && popularGuides.length">
                            <h4 class="font-display text-lg/tight font-bold text-pearl-900 dark:text-pearl-50 mb-4">
                                Popular Guides
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <Link
                                    v-for="guide in popularGuides"
                                    :key="guide.id"
                                    :href="`/guides/${guide.slug}`"
                                    class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-6
                                           bg-white dark:bg-pearl-800 hover:border-wine-400 dark:hover:border-wine-600
                                           transition-all duration-300 hover:shadow-lg hover:shadow-wine-600/10"
                                >
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-xs/relaxed font-semibold px-3 py-1 rounded-lg
                                                     bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400">
                                            {{ guide.category }}
                                        </span>
                                        <span :class="[
                                            'text-xs/relaxed font-semibold px-3 py-1 rounded-lg capitalize',
                                            guide.difficulty === 'beginner' ? 'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400' :
                                            guide.difficulty === 'intermediate' ? 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400' :
                                            'bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400'
                                        ]">
                                            {{ guide.difficulty }}
                                        </span>
                                    </div>

                                    <h2 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2
                                               group-hover:text-wine-600 dark:group-hover:text-wine-400 transition-colors">
                                        {{ guide.title }}
                                    </h2>

                                    <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400 mb-4">
                                        {{ guide.tldr }}
                                    </p>

                                    <div class="flex items-center gap-4 text-xs/relaxed text-pearl-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ guide.view_count || 0 }} views
                                        </span>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { debounce } from 'lodash'

const props = defineProps({
    results: Object,
    query: String,
    categories: Array,
    popularGuides: Array,
    filters: Object,
})

const localQuery = ref(props.query || '')
const localFilters = ref({
    category: props.filters.category,
    difficulty: props.filters.difficulty,
})

const hasActiveFilters = computed(() => {
    return localFilters.value.category || localFilters.value.difficulty
})

const handleSearch = debounce(() => {
    performSearch()
}, 300)

const performSearch = () => {
    router.get('/search', {
        q: localQuery.value,
        ...localFilters.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const toggleFilter = (type, value) => {
    localFilters.value[type] = localFilters.value[type] === value ? null : value
    performSearch()
}

const clearFilters = () => {
    localFilters.value = {
        category: null,
        difficulty: null,
    }
    performSearch()
}

const highlightQuery = (text) => {
    if (!props.query || !text) return text
    const regex = new RegExp(`(${props.query})`, 'gi')
    return text.replace(regex, '<mark class="bg-wine-100 dark:bg-wine-600 text-wine-900 dark:text-white font-semibold px-1 rounded">$1</mark>')
}
</script>
