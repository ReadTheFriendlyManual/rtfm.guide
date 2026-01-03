<template>
    <Link
        :href="`/guides/${guide.slug}`"
        class="group relative border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-6
               bg-white dark:bg-pearl-800 hover:border-wine-400 dark:hover:border-wine-600
               transition-all duration-300 hover:shadow-xl hover:shadow-wine-600/10 hover:-translate-y-1
               overflow-hidden"
    >
        <!-- Featured Badge -->
        <div class="absolute top-4 right-4">
            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400">
                <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-xs/none font-bold">Featured</span>
            </div>
        </div>

        <!-- Category & Difficulty Badges -->
        <div class="flex items-center gap-2 mb-4 pr-24">
            <span class="text-xs/none font-semibold px-3 py-1.5 rounded-lg
                         bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400">
                {{ guide.category.name }}
            </span>
            <span :class="[
                'text-xs/none font-semibold px-3 py-1.5 rounded-lg capitalize',
                guide.difficulty === 'beginner' ? 'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400' :
                guide.difficulty === 'intermediate' ? 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400' :
                'bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400'
            ]">
                {{ guide.difficulty }}
            </span>
        </div>

        <!-- Title -->
        <h3 class="font-display text-xl/7 font-bold text-pearl-900 dark:text-pearl-50 mb-3 group-hover:text-wine-600 dark:group-hover:text-wine-400 transition-colors">
            {{ guide.title }}
        </h3>

        <!-- Description -->
        <p class="text-sm/6 text-pearl-600 dark:text-pearl-400 mb-4 line-clamp-2">
            {{ preferencesStore.mode === 'nsfw' ? guide.tldr_nsfw : guide.tldr_sfw }}
        </p>

        <!-- Author & Meta -->
        <div class="flex items-center justify-between pt-4 border-t-2 border-pearl-100 dark:border-pearl-700">
            <div class="flex items-center gap-2">
                <div v-if="guide.user.avatar" class="size-8 rounded-lg overflow-hidden shrink-0">
                    <img :src="guide.user.avatar" :alt="guide.user.name" class="size-full object-cover" />
                </div>
                <div v-else class="size-8 rounded-lg bg-wine-100 dark:bg-wine-900/30 flex items-center justify-center shrink-0">
                    <span class="text-xs/none font-bold text-wine-700 dark:text-wine-400">
                        {{ guide.user.name.charAt(0).toUpperCase() }}
                    </span>
                </div>
                <span class="text-sm/none font-medium text-pearl-700 dark:text-pearl-300">
                    {{ guide.user.name }}
                </span>
            </div>

            <div class="flex items-center gap-4 text-xs/none text-pearl-500 dark:text-pearl-500">
                <span class="flex items-center gap-1">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ guide.view_count || 0 }}
                </span>
                <span v-if="guide.estimated_minutes" class="flex items-center gap-1">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ guide.estimated_minutes }}m
                </span>
            </div>
        </div>

        <!-- Hover Effect Gradient -->
        <div class="absolute inset-0 bg-linear-to-br from-wine-500/0 to-wine-500/0 group-hover:from-wine-500/5 group-hover:to-transparent transition-all duration-300 rounded-2xl pointer-events-none"></div>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { usePreferencesStore } from '@/Stores/preferences'

const preferencesStore = usePreferencesStore()

defineProps({
    guide: {
        type: Object,
        required: true,
    },
})
</script>
