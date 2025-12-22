<template>
    <div class="relative overflow-hidden bg-gradient-to-br from-sky-50 via-white to-blue-50 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900">
        <!-- Animated gradient background -->
        <div class="absolute inset-0 bg-gradient-to-r from-sky-500/10 to-blue-500/10 dark:from-sky-500/5 dark:to-blue-500/5 animate-pulse"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
            <div class="text-center">
                <!-- Headline -->
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold bg-gradient-to-r from-slate-900 via-sky-800 to-blue-900 dark:from-white dark:via-sky-200 dark:to-blue-100 bg-clip-text text-transparent mb-6 leading-tight">
                    Stop Googling.<br />Start Copying.
                </h1>

                <!-- Subheadline -->
                <p class="text-xl sm:text-2xl text-slate-600 dark:text-slate-300 mb-8 max-w-3xl mx-auto">
                    {{ subtitle }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Button href="/guides" variant="primary" size="lg" class="shadow-2xl shadow-sky-500/50">
                        Browse Guides
                        <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </Button>
                    <Button href="/register" variant="secondary" size="lg">
                        Join the Community
                    </Button>
                </div>

                <!-- Stats -->
                <div class="mt-16 grid grid-cols-3 gap-8 max-w-2xl mx-auto">
                    <div>
                        <p class="text-3xl font-bold text-sky-500">500+</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Guides</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-sky-500">10k+</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Developers</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-sky-500">50+</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Categories</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePreferencesStore } from '@/Stores/preferences'
import Button from '@/Components/UI/Button.vue'

const preferencesStore = usePreferencesStore()

const subtitle = computed(() => {
    return preferencesStore.mode === 'nsfw'
        ? 'Copy-paste ready code snippets, no bullshit, no fluff. Just the fucking answer.'
        : 'Copy-paste ready code snippets with clear explanations. Just the answer you need.'
})
</script>
