<template>
    <PublicLayout>
        <!-- Header -->
        <div class="bg-linear-to-br from-pearl-50 via-white to-pearl-100 dark:from-pearl-950 dark:via-pearl-900 dark:to-pearl-900 border-b-2 border-pearl-200 dark:border-pearl-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="font-display text-4xl/tight sm:text-5xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-4">
                    Browse Guides
                </h1>
                <p class="text-lg/relaxed text-pearl-600 dark:text-pearl-400 max-w-3xl">
                    Find exactly what you need. No fluff, just solutions.
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filters Sidebar -->
                <aside class="lg:w-64 shrink-0">
                    <div class="sticky top-20 space-y-6">
                        <!-- Sort -->
                        <div>
                            <h3 class="font-display text-sm/tight font-bold text-pearl-900 dark:text-pearl-50 mb-3">Sort By</h3>
                            <select
                                v-model="localFilters.sort"
                                @change="applyFilters"
                                class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600
                                       bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50
                                       focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all"
                            >
                                <option value="latest">Latest</option>
                                <option value="popular">Most Popular</option>
                                <option value="trending">Trending This Week</option>
                            </select>
                        </div>

                        <!-- Category Filter -->
                        <div v-if="categories.length">
                            <h3 class="font-display text-sm/tight font-bold text-pearl-900 dark:text-pearl-50 mb-3">Category</h3>
                            <div class="space-y-2">
                                <button
                                    @click="toggleCategory(null)"
                                    :class="[
                                        'w-full text-left px-4 py-2 rounded-xl font-medium transition-all',
                                        !localFilters.category
                                            ? 'bg-wine-600 text-white'
                                            : 'text-pearl-700 dark:text-pearl-300 hover:bg-pearl-100 dark:hover:bg-pearl-800'
                                    ]"
                                >
                                    All Guides
                                </button>
                                <button
                                    v-for="category in categories"
                                    :key="category.id"
                                    @click="toggleCategory(category.slug)"
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
                        <div>
                            <h3 class="font-display text-sm/tight font-bold text-pearl-900 dark:text-pearl-50 mb-3">Difficulty</h3>
                            <div class="space-y-2">
                                <button
                                    v-for="difficulty in ['beginner', 'intermediate', 'advanced']"
                                    :key="difficulty"
                                    @click="toggleDifficulty(difficulty)"
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

                <!-- Guides Grid -->
                <div class="flex-1">
                    <div v-if="guides.data.length" class="space-y-6">
                        <!-- Guide Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <Link
                                v-for="guide in guides.data"
                                :key="guide.id"
                                :href="`/guides/${guide.slug}`"
                                class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-6
                                       bg-white dark:bg-pearl-800 hover:border-wine-400 dark:hover:border-wine-600
                                       transition-all duration-300 hover:shadow-lg hover:shadow-wine-600/10"
                            >
                                <!-- Category Badge -->
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xs/relaxed font-semibold px-3 py-1 rounded-lg
                                                 bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400">
                                        {{ guide.category.name }}
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

                                <h2 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2 group-hover:text-wine-600 dark:group-hover:text-wine-400 transition-colors">
                                    {{ guide.title }}
                                </h2>

                                <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400 mb-4">
                                    {{ guide.tldr }}
                                </p>

                                <!-- Meta -->
                                <div class="flex items-center gap-4 text-xs/relaxed text-pearl-500 dark:text-pearl-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ guide.view_count || 0 }} views
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ guide.estimated_minutes }} min
                                    </span>
                                </div>
                            </Link>
                        </div>

                        <!-- Pagination -->
                        <div v-if="guides.last_page > 1" class="flex items-center justify-center gap-2 mt-8">
                            <Link
                                v-if="guides.prev_page_url"
                                :href="guides.prev_page_url"
                                class="px-4 py-2 rounded-xl border-2 border-pearl-300 dark:border-pearl-600
                                       text-pearl-700 dark:text-pearl-300 hover:border-wine-500 dark:hover:border-wine-500
                                       font-medium transition-all"
                            >
                                Previous
                            </Link>
                            <span class="text-sm/relaxed text-pearl-600 dark:text-pearl-400 px-4">
                                Page {{ guides.current_page }} of {{ guides.last_page }}
                            </span>
                            <Link
                                v-if="guides.next_page_url"
                                :href="guides.next_page_url"
                                class="px-4 py-2 rounded-xl border-2 border-pearl-300 dark:border-pearl-600
                                       text-pearl-700 dark:text-pearl-300 hover:border-wine-500 dark:hover:border-wine-500
                                       font-medium transition-all"
                            >
                                Next
                            </Link>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-16">
                        <svg class="size-16 mx-auto text-pearl-300 dark:text-pearl-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
                            No guides found
                        </h3>
                        <p class="text-pearl-600 dark:text-pearl-400 mb-4">
                            Try adjusting your filters
                        </p>
                        <button
                            @click="clearFilters"
                            class="inline-flex items-center px-6 py-3 rounded-xl bg-wine-600 hover:bg-wine-700
                                   text-white font-semibold transition-all shadow-lg shadow-wine-600/30"
                        >
                            Clear Filters
                        </button>
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

const props = defineProps({
    guides: Object,
    categories: Array,
    filters: Object,
})

const localFilters = ref({
    category: props.filters.category,
    difficulty: props.filters.difficulty,
    os: props.filters.os,
    sort: props.filters.sort || 'latest',
})

const hasActiveFilters = computed(() => {
    return localFilters.value.category || localFilters.value.difficulty || localFilters.value.os
})

const toggleCategory = (slug) => {
    localFilters.value.category = localFilters.value.category === slug ? null : slug
    applyFilters()
}

const toggleDifficulty = (level) => {
    localFilters.value.difficulty = localFilters.value.difficulty === level ? null : level
    applyFilters()
}

const applyFilters = () => {
    router.get('/guides', localFilters.value, {
        preserveState: true,
        preserveScroll: true,
    })
}

const clearFilters = () => {
    localFilters.value = {
        category: null,
        difficulty: null,
        os: null,
        sort: 'latest',
    }
    applyFilters()
}
</script>
