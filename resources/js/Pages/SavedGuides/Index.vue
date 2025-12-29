<template>
    <PublicLayout>
        <!-- Header -->
        <div class="bg-linear-to-br from-pearl-50 via-white to-pearl-100 dark:from-pearl-950 dark:via-pearl-900 dark:to-pearl-900 border-b-2 border-pearl-200 dark:border-pearl-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="font-display text-4xl/tight sm:text-5xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-4">
                    Saved Guides
                </h1>
                <p class="text-lg/relaxed text-pearl-600 dark:text-pearl-400 max-w-3xl">
                    Your bookmarked guides for quick access
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Breadcrumbs -->
            <Breadcrumbs :items="breadcrumbs" />

            <!-- Empty State -->
            <div v-if="!savedGuides.data || savedGuides.data.length === 0" class="flex flex-col items-center justify-center py-20">
                <svg class="size-20 text-pearl-300 dark:text-pearl-700 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                </svg>
                <h2 class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-3">
                    No saved guides yet
                </h2>
                <p class="text-pearl-600 dark:text-pearl-400 mb-8 text-center max-w-md">
                    Start bookmarking guides to build your personal reference library
                </p>
                <Link
                    href="/guides"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-wine-600 hover:bg-wine-700
                           text-white font-semibold transition-all shadow-lg shadow-wine-600/30"
                >
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Browse Guides
                </Link>
            </div>

            <!-- Saved Guides Grid -->
            <div v-else>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="saved in savedGuides.data"
                        :key="saved.id"
                        :href="`/guides/${saved.guide.slug}`"
                        class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-6
                               bg-white dark:bg-pearl-800 hover:border-wine-400 dark:hover:border-wine-600
                               transition-all duration-300 hover:shadow-xl hover:shadow-wine-600/10"
                    >
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs/tight font-semibold px-3 py-1 rounded-lg
                                         bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400">
                                {{ saved.guide.category }}
                            </span>
                            <span :class="[
                                'text-xs/tight font-semibold px-3 py-1 rounded-lg capitalize',
                                saved.guide.difficulty === 'beginner' ? 'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400' :
                                saved.guide.difficulty === 'intermediate' ? 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400' :
                                'bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400'
                            ]">
                                {{ saved.guide.difficulty }}
                            </span>
                        </div>

                        <h2 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2
                                   group-hover:text-wine-600 dark:group-hover:text-wine-400 transition-colors">
                            {{ saved.guide.title }}
                        </h2>

                        <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400 mb-4 line-clamp-2">
                            {{ activeTldr(saved.guide) }}
                        </p>

                        <div class="flex items-center justify-between text-xs/tight text-pearl-500">
                            <span class="flex items-center gap-1">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ saved.guide.view_count || 0 }} views
                            </span>
                            <span>Saved {{ formatDate(saved.saved_at) }}</span>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="savedGuides.last_page > 1" class="flex items-center justify-center gap-2 mt-12">
                    <Link
                        v-if="savedGuides.prev_page_url"
                        :href="savedGuides.prev_page_url"
                        class="px-4 py-2 rounded-xl border-2 border-pearl-300 dark:border-pearl-600
                               text-pearl-700 dark:text-pearl-300 hover:border-wine-500
                               font-medium transition-all"
                    >
                        Previous
                    </Link>
                    <span class="text-sm/tight text-pearl-600 dark:text-pearl-400 px-4">
                        Page {{ savedGuides.current_page }} of {{ savedGuides.last_page }}
                    </span>
                    <Link
                        v-if="savedGuides.next_page_url"
                        :href="savedGuides.next_page_url"
                        class="px-4 py-2 rounded-xl border-2 border-pearl-300 dark:border-pearl-600
                               text-pearl-700 dark:text-pearl-300 hover:border-wine-500
                               font-medium transition-all"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue'
import { usePreferencesStore } from '@/Stores/preferences'

const preferencesStore = usePreferencesStore()

defineProps({
    savedGuides: Object,
})

const breadcrumbs = computed(() => [
    { label: 'Home', href: '/' },
    { label: 'Saved Guides' }
])

const activeTldr = (guide) => {
    return preferencesStore.mode === 'nsfw' ? (guide.tldr_nsfw || guide.tldr) : guide.tldr
}

const formatDate = (date) => {
    const d = new Date(date)
    const now = new Date()
    const diff = now - d
    const days = Math.floor(diff / (1000 * 60 * 60 * 24))

    if (days === 0) return 'today'
    if (days === 1) return 'yesterday'
    if (days < 7) return `${days} days ago`
    if (days < 30) return `${Math.floor(days / 7)} weeks ago`
    if (days < 365) return `${Math.floor(days / 30)} months ago`
    return `${Math.floor(days / 365)} years ago`
}
</script>
