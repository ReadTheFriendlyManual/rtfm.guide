<template>
    <PublicLayout>
        <!-- Hero Header -->
        <div class="bg-linear-to-br from-wine-50 via-pearl-50 to-sage-50 dark:from-wine-950 dark:via-pearl-950 dark:to-sage-950 border-b-2 border-pearl-200 dark:border-pearl-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="flex items-start gap-6">
                    <div v-if="category.icon" class="shrink-0">
                        <div class="size-20 sm:size-24 rounded-2xl bg-wine-100 dark:bg-wine-900/30 flex items-center justify-center text-4xl sm:text-5xl">
                            {{ category.icon }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <h1 class="font-display text-4xl/tight sm:text-5xl/tight lg:text-6xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-4">
                            {{ category.name }}
                        </h1>
                        <p v-if="category.description" class="text-lg/8 sm:text-xl/9 text-pearl-600 dark:text-pearl-400 max-w-4xl">
                            {{ category.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Breadcrumbs -->
            <Breadcrumbs :items="breadcrumbs" />

            <!-- Warning Banners -->
            <div class="mt-6 mb-8">
                <WarningBanner :flags="category.flags || []" />
            </div>

            <!-- Featured Guides Section -->
            <section v-if="featuredGuides.length" class="mt-12">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="font-display text-3xl/tight font-bold text-pearl-900 dark:text-pearl-50">
                        Featured Guides
                    </h2>
                    <Link
                        :href="`/guides?category=${category.slug}`"
                        class="text-wine-600 dark:text-wine-400 hover:text-wine-700 dark:hover:text-wine-300 font-semibold flex items-center gap-2 transition-colors"
                    >
                        View All
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <FeaturedGuideCard
                        v-for="guide in featuredGuides"
                        :key="guide.id"
                        :guide="guide"
                    />
                </div>
            </section>

            <!-- Featured Writers Section -->
            <section v-if="featuredWriters.length" class="mt-16">
                <h2 class="font-display text-3xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-8">
                    Featured Writers
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <FeaturedWriterCard
                        v-for="writer in featuredWriters"
                        :key="writer.id"
                        :writer="writer"
                    />
                </div>
            </section>

            <!-- Empty State -->
            <div v-if="!featuredGuides.length && !featuredWriters.length" class="text-center py-16">
                <svg class="size-16 mx-auto text-pearl-300 dark:text-pearl-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
                    No Featured Content Yet
                </h3>
                <p class="text-pearl-600 dark:text-pearl-400 mb-6">
                    Check back soon for featured guides and writers in this category.
                </p>
                <Link
                    :href="`/guides?category=${category.slug}`"
                    class="inline-flex items-center px-6 py-3 rounded-xl bg-wine-600 hover:bg-wine-700
                           text-white font-semibold transition-all shadow-lg shadow-wine-600/30"
                >
                    Browse All Guides
                </Link>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue'
import FeaturedGuideCard from '@/Components/Category/FeaturedGuideCard.vue'
import FeaturedWriterCard from '@/Components/Category/FeaturedWriterCard.vue'
import WarningBanner from '@/Components/Shared/WarningBanner.vue'

const props = defineProps({
    category: Object,
    featuredGuides: Array,
    featuredWriters: Array,
})

const breadcrumbs = computed(() => [
    { label: 'Home', href: '/' },
    { label: props.category.name }
])
</script>
