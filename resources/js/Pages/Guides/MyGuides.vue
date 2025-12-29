<template>
    <PublicLayout>
        <!-- Header -->
        <div class="bg-linear-to-br from-wine-600 via-wine-500 to-wine-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="font-display text-4xl/tight sm:text-5xl/tight font-bold mb-4">
                    My Guides
                </h1>
                <p class="text-wine-100 text-lg/relaxed">
                    Manage and edit your published guides
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Create Guide Button -->
            <div class="flex items-center justify-between mb-8">
                <p class="text-pearl-600 dark:text-pearl-400">
                    {{ guides.total }} total guide{{ guides.total !== 1 ? 's' : '' }}
                </p>
                <Link
                    href="/guides/create"
                    class="inline-flex items-center gap-2 bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30"
                >
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create New Guide
                </Link>
            </div>

            <!-- Guides List -->
            <div v-if="guides.data.length > 0" class="space-y-4">
                <Link
                    v-for="guide in guides.data"
                    :key="guide.id"
                    :href="`/guides/${guide.slug}/edit`"
                    class="group block bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-6 hover:border-wine-400 dark:hover:border-wine-600 transition-all"
                >
                    <div class="flex items-start justify-between gap-6">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-white group-hover:text-wine-600 dark:group-hover:text-wine-400 transition-colors truncate">
                                    {{ guide.title }}
                                </h3>
                                <span
                                    class="shrink-0 px-3 py-1 rounded-lg text-xs/tight font-semibold uppercase tracking-wider"
                                    :class="{
                                        'bg-pearl-100 dark:bg-pearl-700 text-pearl-700 dark:text-pearl-300': guide.status.value === 'draft',
                                        'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400': guide.status.value === 'pending',
                                        'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400': guide.status.value === 'published',
                                    }"
                                >
                                    {{ guide.status.label }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-4 text-sm/tight text-pearl-600 dark:text-pearl-400">
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    {{ guide.category.name }}
                                </span>
                                <span
                                    class="px-2.5 py-0.5 rounded-lg text-xs/tight font-medium"
                                    :class="{
                                        'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400': guide.difficulty.value === 'beginner',
                                        'bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400': guide.difficulty.value === 'intermediate',
                                        'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400': guide.difficulty.value === 'advanced',
                                    }"
                                >
                                    {{ guide.difficulty.label }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ guide.view_count }} views
                                </span>
                            </div>

                            <div class="flex items-center gap-4 mt-3 text-xs/tight text-pearl-500">
                                <span>Created {{ guide.created_at }}</span>
                                <span>Updated {{ guide.updated_at }}</span>
                            </div>
                        </div>

                        <div class="shrink-0">
                            <svg class="size-5 text-pearl-400 dark:text-pearl-500 group-hover:text-wine-600 dark:group-hover:text-wine-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </Link>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-16">
                <div class="inline-flex items-center justify-center size-20 rounded-full bg-wine-100 dark:bg-wine-900/30 mb-6">
                    <svg class="size-10 text-wine-600 dark:text-wine-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-white mb-2">
                    No guides yet
                </h3>
                <p class="text-pearl-600 dark:text-pearl-400 mb-6">
                    Share your knowledge by creating your first guide
                </p>
                <Link
                    href="/guides/create"
                    class="inline-flex items-center gap-2 bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30"
                >
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Your First Guide
                </Link>
            </div>

            <!-- Pagination -->
            <div v-if="guides.data.length > 0 && (guides.prev_page_url || guides.next_page_url)" class="flex items-center justify-between mt-8 pt-8 border-t-2 border-pearl-200 dark:border-pearl-700">
                <Link
                    v-if="guides.prev_page_url"
                    :href="guides.prev_page_url"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 text-pearl-700 dark:text-pearl-300 hover:border-wine-400 dark:hover:border-wine-600 hover:text-wine-600 dark:hover:text-wine-400 transition-all font-medium"
                >
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous
                </Link>
                <div v-else></div>

                <span class="text-sm/tight text-pearl-600 dark:text-pearl-400">
                    Page {{ guides.current_page }} of {{ guides.last_page }}
                </span>

                <Link
                    v-if="guides.next_page_url"
                    :href="guides.next_page_url"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 text-pearl-700 dark:text-pearl-300 hover:border-wine-400 dark:hover:border-wine-600 hover:text-wine-600 dark:hover:text-wine-400 transition-all font-medium"
                >
                    Next
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
                <div v-else></div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({
    guides: Object,
})
</script>
