<template>
    <div class="relative h-[calc(100vh-4rem)] flex flex-col overflow-hidden bg-linear-to-br from-pearl-50 via-white to-pearl-100 dark:from-pearl-950 dark:via-pearl-900 dark:to-pearl-800">
        <!-- Elegant grid pattern -->
        <div class="absolute inset-0 pattern-grid text-pearl-200/30 dark:text-pearl-700/20"></div>

        <!-- Grain texture overlay -->
        <div class="absolute inset-0 texture-grain"></div>

        <!-- Sophisticated color accents -->
        <div class="absolute inset-0 bg-linear-to-tr from-wine-600/5 via-transparent to-gold-500/5 dark:from-wine-700/10 dark:via-transparent dark:to-gold-600/10"></div>

        <div class="relative flex-1 flex items-center">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
                <div class="text-center">
                <!-- Bold headline with Bricolage Grotesque -->
                <h1 class="font-display text-5xl/tight sm:text-6xl/tight md:text-7xl/tight lg:text-8xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-6 sm:mb-8 px-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <span class="inline-block">Stop Googling.</span>
                    <br />
                    <span class="inline-block bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent animate-shimmer">Start Copying.</span>
                </h1>

                <!-- Subheadline -->
                <p class="text-lg/relaxed sm:text-xl/relaxed md:text-2xl/relaxed text-pearl-600 dark:text-pearl-400 mb-8 sm:mb-10 max-w-3xl mx-auto px-4 animate-fade-in-up" style="animation-delay: 0.3s;">
                    {{ subtitle }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up" style="animation-delay: 0.5s;">
                    <Button href="/guides" variant="primary" size="lg" class="bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold shadow-xl shadow-wine-600/30 dark:shadow-wine-700/40 border-2 border-wine-700/50">
                        Browse Guides
                        <svg class="ml-2 size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </Button>
                    <Button href="/register" variant="secondary" size="lg" class="bg-transparent border-2 border-gold-600 text-gold-700 dark:text-gold-500 hover:bg-gold-600/10 font-semibold">
                        Join the Community
                    </Button>
                </div>

                <!-- Stats with elegant cards -->
                <div class="mt-16 sm:mt-20 grid grid-cols-3 gap-4 sm:gap-6 max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.7s;">
                    <div class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-4 sm:p-6 bg-white/80 dark:bg-pearl-800/50 backdrop-blur-sm hover:border-wine-400 dark:hover:border-wine-600 transition-all duration-300 hover:shadow-lg hover:shadow-wine-600/10">
                        <p class="font-display text-3xl/tight sm:text-4xl/tight font-bold bg-linear-to-br from-wine-600 to-wine-700 bg-clip-text text-transparent">500+</p>
                        <p class="text-xs/relaxed sm:text-sm/relaxed text-pearl-600 dark:text-pearl-400 mt-1 font-medium">Guides</p>
                    </div>
                    <div class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-4 sm:p-6 bg-white/80 dark:bg-pearl-800/50 backdrop-blur-sm hover:border-gold-500 dark:hover:border-gold-600 transition-all duration-300 hover:shadow-lg hover:shadow-gold-600/10">
                        <p class="font-display text-3xl/tight sm:text-4xl/tight font-bold bg-linear-to-br from-gold-600 to-gold-700 bg-clip-text text-transparent">10k+</p>
                        <p class="text-xs/relaxed sm:text-sm/relaxed text-pearl-600 dark:text-pearl-400 mt-1 font-medium">Developers</p>
                    </div>
                    <div class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-4 sm:p-6 bg-white/80 dark:bg-pearl-800/50 backdrop-blur-sm hover:border-sage-500 dark:hover:border-sage-600 transition-all duration-300 hover:shadow-lg hover:shadow-sage-600/10">
                        <p class="font-display text-3xl/tight sm:text-4xl/tight font-bold bg-linear-to-br from-sage-600 to-sage-700 bg-clip-text text-transparent">50+</p>
                        <p class="text-xs/relaxed sm:text-sm/relaxed text-pearl-600 dark:text-pearl-400 mt-1 font-medium">Categories</p>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <!-- Elegant scroll indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-float">
            <svg class="size-6 text-pearl-400 dark:text-pearl-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
