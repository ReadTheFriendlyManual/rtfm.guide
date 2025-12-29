<template>
    <PublicLayout>
        <!-- Header -->
        <div class="bg-linear-to-br from-wine-600 via-wine-500 to-wine-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="flex items-center gap-6">
                    <div class="size-24 bg-white/20 rounded-2xl flex items-center justify-center text-white font-bold text-4xl shadow-xl backdrop-blur-sm">
                        {{ profileUser.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <h1 class="font-display text-4xl/tight font-bold mb-2">
                            {{ profileUser.name }}
                        </h1>
                        <p class="text-wine-100">
                            Member since {{ stats.member_since }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <div class="bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-xl bg-wine-100 dark:bg-wine-900/30 flex items-center justify-center">
                            <svg class="size-6 text-wine-600 dark:text-wine-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-3xl/tight font-bold text-pearl-900 dark:text-white">{{ stats.total_guides }}</div>
                            <div class="text-sm/tight text-pearl-600 dark:text-pearl-400">Published Guides</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-xl bg-gold-100 dark:bg-gold-900/30 flex items-center justify-center">
                            <svg class="size-6 text-gold-600 dark:text-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-3xl/tight font-bold text-pearl-900 dark:text-white">{{ stats.total_views.toLocaleString() }}</div>
                            <div class="text-sm/tight text-pearl-600 dark:text-pearl-400">Total Views</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guides -->
            <div>
                <h2 class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-white mb-6">
                    Published Guides
                </h2>

                <div v-if="guides.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="guide in guides.data"
                        :key="guide.id"
                        :href="`/guides/${guide.slug}`"
                        class="group bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-6 hover:border-wine-400 dark:hover:border-wine-600 transition-all"
                    >
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs/tight font-semibold px-2.5 py-1 rounded-lg bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400">
                                {{ guide.category.name }}
                            </span>
                            <span
                                class="text-xs/tight font-semibold px-2.5 py-1 rounded-lg capitalize"
                                :class="{
                                    'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400': guide.difficulty === 'beginner',
                                    'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400': guide.difficulty === 'intermediate',
                                    'bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400': guide.difficulty === 'advanced',
                                }"
                            >
                                {{ guide.difficulty }}
                            </span>
                        </div>
                        <h3 class="font-display text-lg/tight font-bold text-pearl-900 dark:text-white mb-2 group-hover:text-wine-600 dark:group-hover:text-wine-400 transition-colors">
                            {{ guide.title }}
                        </h3>
                        <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400 mb-4 line-clamp-2">
                            {{ guide.tldr }}
                        </p>
                        <div class="flex items-center gap-4 text-xs/tight text-pearl-500">
                            <span class="flex items-center gap-1">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ guide.view_count }} views
                            </span>
                            <span>{{ guide.published_at }}</span>
                        </div>
                    </Link>
                </div>

                <div v-else class="text-center py-16">
                    <div class="inline-flex items-center justify-center size-16 rounded-full bg-pearl-100 dark:bg-pearl-800 mb-4">
                        <svg class="size-8 text-pearl-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-pearl-600 dark:text-pearl-400">
                        No published guides yet
                    </p>
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
        </div>
    </PublicLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({
    profileUser: Object,
    guides: Object,
    stats: Object,
})
</script>
