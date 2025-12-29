<template>
    <PublicLayout>
        <!-- Header -->
        <div class="bg-linear-to-br from-wine-600 via-wine-500 to-wine-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="font-display text-4xl/tight sm:text-5xl/tight font-bold mb-4">
                    Welcome back, {{ $page.props.auth.user.name }}!
                </h1>
                <p class="text-wine-100 text-lg/relaxed">
                    Your personal guide management hub
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-white dark:bg-pearl-800 rounded-2xl p-6 border-2 border-pearl-200 dark:border-pearl-700">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="size-10 rounded-xl bg-wine-100 dark:bg-wine-900/30 flex items-center justify-center">
                            <svg class="size-5 text-wine-600 dark:text-wine-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-3xl/tight font-bold text-pearl-900 dark:text-white">{{ stats.guides_created }}</div>
                            <div class="text-sm/tight text-pearl-600 dark:text-pearl-400">Guides Created</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-pearl-800 rounded-2xl p-6 border-2 border-pearl-200 dark:border-pearl-700">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="size-10 rounded-xl bg-sage-100 dark:bg-sage-900/30 flex items-center justify-center">
                            <svg class="size-5 text-sage-600 dark:text-sage-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-3xl/tight font-bold text-pearl-900 dark:text-white">{{ stats.guides_saved }}</div>
                            <div class="text-sm/tight text-pearl-600 dark:text-pearl-400">Saved Guides</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-pearl-800 rounded-2xl p-6 border-2 border-pearl-200 dark:border-pearl-700">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="size-10 rounded-xl bg-gold-100 dark:bg-gold-900/30 flex items-center justify-center">
                            <svg class="size-5 text-gold-600 dark:text-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

                <div class="bg-white dark:bg-pearl-800 rounded-2xl p-6 border-2 border-pearl-200 dark:border-pearl-700">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="size-10 rounded-xl bg-wine-100 dark:bg-wine-900/30 flex items-center justify-center">
                            <svg class="size-5 text-wine-600 dark:text-wine-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-3xl/tight font-bold text-pearl-900 dark:text-white">{{ stats.reputation_points }}</div>
                            <div class="text-sm/tight text-pearl-600 dark:text-pearl-400">Reputation</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <Link
                    href="/guides/create"
                    class="group bg-linear-to-br from-wine-600 to-wine-700 rounded-2xl p-8 text-white
                           hover:from-wine-500 hover:to-wine-600 transition-all duration-300 shadow-lg shadow-wine-600/30"
                >
                    <div class="flex items-center gap-4 mb-4">
                        <div class="size-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h3 class="font-display text-xl/tight font-bold">Create Guide</h3>
                    </div>
                    <p class="text-wine-100">Share your knowledge with the community</p>
                </Link>

                <Link
                    href="/saved-guides"
                    class="group bg-white dark:bg-pearl-800 rounded-2xl p-8 border-2 border-pearl-200 dark:border-pearl-700
                           hover:border-wine-400 dark:hover:border-wine-600 transition-all duration-300"
                >
                    <div class="flex items-center gap-4 mb-4">
                        <div class="size-12 rounded-xl bg-sage-100 dark:bg-sage-900/30 flex items-center justify-center">
                            <svg class="size-6 text-sage-600 dark:text-sage-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </div>
                        <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-white">Saved Guides</h3>
                    </div>
                    <p class="text-pearl-600 dark:text-pearl-400">Access your bookmarked guides</p>
                </Link>

                <Link
                    href="/my-guides"
                    class="group bg-white dark:bg-pearl-800 rounded-2xl p-8 border-2 border-pearl-200 dark:border-pearl-700
                           hover:border-wine-400 dark:hover:border-wine-600 transition-all duration-300"
                >
                    <div class="flex items-center gap-4 mb-4">
                        <div class="size-12 rounded-xl bg-gold-100 dark:bg-gold-900/30 flex items-center justify-center">
                            <svg class="size-6 text-gold-600 dark:text-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-white">My Guides</h3>
                    </div>
                    <p class="text-pearl-600 dark:text-pearl-400">Manage your created guides</p>
                </Link>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Saved Guides -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-white">
                            Recently Saved
                        </h2>
                        <Link
                            href="/saved-guides"
                            class="text-sm/tight font-semibold text-wine-600 dark:text-wine-400 hover:underline"
                        >
                            View all →
                        </Link>
                    </div>

                    <div v-if="recentSaved.length" class="space-y-4">
                        <Link
                            v-for="guide in recentSaved"
                            :key="guide.id"
                            :href="`/guides/${guide.slug}`"
                            class="block bg-white dark:bg-pearl-800 rounded-xl p-4 border-2 border-pearl-200 dark:border-pearl-700
                                   hover:border-wine-400 dark:hover:border-wine-600 transition-all"
                        >
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs/tight font-semibold px-2 py-1 rounded-lg
                                             bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400">
                                    {{ guide.category }}
                                </span>
                                <span :class="[
                                    'text-xs/tight font-semibold px-2 py-1 rounded-lg capitalize',
                                    guide.difficulty === 'beginner' ? 'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400' :
                                    guide.difficulty === 'intermediate' ? 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400' :
                                    'bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400'
                                ]">
                                    {{ guide.difficulty }}
                                </span>
                            </div>
                            <h3 class="font-semibold text-pearl-900 dark:text-white">{{ guide.title }}</h3>
                        </Link>
                    </div>
                    <div v-else class="text-center py-12 bg-white dark:bg-pearl-800 rounded-xl border-2 border-dashed border-pearl-300 dark:border-pearl-700">
                        <p class="text-pearl-600 dark:text-pearl-400">No saved guides yet</p>
                    </div>
                </div>

                <!-- My Recent Guides -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-white">
                            My Recent Guides
                        </h2>
                        <Link
                            href="/my-guides"
                            class="text-sm/tight font-semibold text-wine-600 dark:text-wine-400 hover:underline"
                        >
                            View all →
                        </Link>
                    </div>

                    <div v-if="myRecentGuides.length" class="space-y-4">
                        <Link
                            v-for="guide in myRecentGuides"
                            :key="guide.id"
                            :href="`/guides/${guide.slug}`"
                            class="block bg-white dark:bg-pearl-800 rounded-xl p-4 border-2 border-pearl-200 dark:border-pearl-700
                                   hover:border-wine-400 dark:hover:border-wine-600 transition-all"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs/tight font-semibold px-2 py-1 rounded-lg
                                             bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400">
                                    {{ guide.category }}
                                </span>
                                <span :class="[
                                    'text-xs/tight font-semibold px-2 py-1 rounded-lg capitalize',
                                    guide.status === 'published' ? 'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400' :
                                    guide.status === 'draft' ? 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400' :
                                    'bg-pearl-100 dark:bg-pearl-700 text-pearl-700 dark:text-pearl-300'
                                ]">
                                    {{ guide.status }}
                                </span>
                            </div>
                            <h3 class="font-semibold text-pearl-900 dark:text-white mb-1">{{ guide.title }}</h3>
                            <p class="text-xs/tight text-pearl-500">{{ guide.view_count }} views</p>
                        </Link>
                    </div>
                    <div v-else class="text-center py-12 bg-white dark:bg-pearl-800 rounded-xl border-2 border-dashed border-pearl-300 dark:border-pearl-700">
                        <p class="text-pearl-600 dark:text-pearl-400 mb-4">No guides created yet</p>
                        <Link
                            href="/guides/create"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-wine-600 hover:bg-wine-700
                                   text-white font-semibold transition-all"
                        >
                            Create your first guide
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const $page = usePage()

defineProps({
    stats: Object,
    recentSaved: Array,
    myRecentGuides: Array,
    trending: Array,
})
</script>
