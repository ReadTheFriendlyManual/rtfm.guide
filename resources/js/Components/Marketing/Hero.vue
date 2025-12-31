<template>
    <div class="relative flex flex-col overflow-hidden bg-pearl-50 dark:bg-pearl-950">
        <!-- Grain texture overlay -->
        <div class="absolute inset-0 texture-grain"></div>

        <div class="relative flex flex-col justify-center">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 lg:pt-16 pb-4 sm:pb-6 lg:pb-8">
                <div class="text-center">
                    <h1 class="font-display text-3xl/tight sm:text-4xl/tight md:text-6xl/tight lg:text-7xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-3 sm:mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                        <span class="block">Stop Googling.</span>
                        <span class="block">Start Copying.</span>
                    </h1>

                    <p class="text-sm/relaxed sm:text-base/relaxed md:text-xl/relaxed text-pearl-600 dark:text-pearl-400 mb-4 sm:mb-8 max-w-2xl mx-auto px-2 animate-fade-in-up" style="animation-delay: 0.3s;">
                        {{ subtitle }}
                    </p>

                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-center items-center mb-6 sm:mb-16 animate-fade-in-up" style="animation-delay: 0.5s;">
                        <Button
                            href="/guides"
                            variant="primary"
                            size="md"
                            class="group bg-pearl-900 hover:bg-pearl-800 dark:bg-white dark:hover:bg-pearl-100 dark:text-pearl-900 text-white font-semibold shadow-xl shadow-pearl-900/20 dark:shadow-none border-0 rounded-full text-sm sm:text-base"
                        >
                            Start Reading
                            <svg class="ml-2 size-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </Button>
                        <Button
                            href="/register"
                            variant="secondary"
                            size="md"
                            class="bg-white dark:bg-pearl-800 border-2 border-pearl-200 dark:border-pearl-700 text-pearl-900 dark:text-pearl-50 hover:bg-pearl-50 dark:hover:bg-pearl-700 font-semibold rounded-full text-sm sm:text-base"
                        >
                            Join the Community
                        </Button>
                    </div>

                    <!-- Hero Illustration Section -->
                    <div class="relative w-full max-w-5xl mx-auto animate-fade-in-up" style="animation-delay: 0.7s;">
                        <!-- Three column layout - items aligned to bottom -->
                        <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 items-end">
                            <!-- Left Card -->
                            <div class="hidden md:flex justify-center">
                                <div class="w-full max-w-sm">
                                    <HeroFloatingCard :show-footer="true">
                                        <div class="space-y-2">
                                            <div class="h-2 w-full bg-pearl-200 dark:bg-pearl-700 rounded"></div>
                                            <div class="h-2 w-5/6 bg-pearl-200 dark:bg-pearl-700 rounded"></div>
                                            <div class="h-2 w-4/6 bg-pearl-200 dark:bg-pearl-700 rounded"></div>
                                        </div>
                                    </HeroFloatingCard>
                                </div>
                            </div>

                            <!-- Center - Hero Image with Speech Bubble -->
                            <div class="relative z-20 flex flex-col items-center">
                                <!-- Speech Bubble -->
                                <div class="mb-1 sm:mb-2">
                                    <HeroSpeechBubble>{{ speechBubbleText }}</HeroSpeechBubble>
                                </div>
                                <!-- Hero Image -->
                                <div class="relative w-72 h-72 sm:w-72 sm:h-72 md:w-80 md:h-80 lg:w-96 lg:h-96">
                                    <img
                                        src="/images/hero-fully-transparent.png"
                                        alt="Developer thinking"
                                        class="w-full h-full object-contain dark:invert"
                                    />
                                </div>
                            </div>

                            <!-- Right Card - Terminal -->
                            <div class="hidden md:flex justify-center">
                                <div class="w-full max-w-sm">
                                    <HeroCodeCard />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom fade gradient to blend into next section -->
        <div class="absolute bottom-0 left-0 right-0 h-8 sm:h-32 bg-linear-to-t from-white dark:from-pearl-950 to-transparent pointer-events-none"></div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePreferencesStore } from '@/Stores/preferences'
import Button from '@/Components/UI/Button.vue'
import HeroFloatingCard from '@/Components/Marketing/HeroFloatingCard.vue'
import HeroCodeCard from '@/Components/Marketing/HeroCodeCard.vue'
import HeroSpeechBubble from '@/Components/Marketing/HeroSpeechBubble.vue'

const preferencesStore = usePreferencesStore()

const subtitle = computed(() => {
    return preferencesStore.mode === 'nsfw'
        ? 'Copy-paste ready code snippets with clear explanations. Just the fucking answer.'
        : 'Copy-paste ready code snippets with clear explanations. Just the answer you need.'
})

const speechBubbleText = computed(() => {
    return preferencesStore.mode === 'nsfw'
        ? 'Read the fucking manual'
        : 'Read the !@#$%^&* manual'
})
</script>
