<template>
    <nav class="fixed top-0 w-full bg-white/80 dark:bg-pearl-950/90 backdrop-blur-xl border-b-2 border-pearl-200 dark:border-pearl-800 z-50 shadow-sm dark:shadow-pearl-900/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <span class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-pearl-50">
                        RTFM<span class="bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent">.guide</span>
                    </span>
                </div>

                <!-- Right side controls -->
                <div class="flex items-center gap-4">
                    <!-- NSFW/SFW Toggle -->
                    <div class="flex items-center gap-2 text-xs/relaxed font-medium" data-toggle-mode>
                        <span class="text-pearl-500 dark:text-pearl-400">SFW</span>
                        <Toggle v-model="isNsfw" label="Toggle NSFW mode" />
                        <span class="text-wine-600 dark:text-wine-400">NSFW</span>
                    </div>

                    <!-- Theme Toggle -->
                    <button
                        @click="toggleTheme"
                        class="p-2 rounded-xl hover:bg-pearl-100 dark:hover:bg-pearl-800 text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-all duration-200"
                        :aria-label="`Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`"
                    >
                        <svg v-if="theme === 'dark'" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg v-else class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Sign In Button -->
                    <Button href="/login" variant="primary" size="sm" class="bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold shadow-lg shadow-wine-600/20">
                        Sign In
                    </Button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useTheme } from '@/Composables/useTheme'
import { usePreferencesStore } from '@/Stores/preferences'
import Button from '@/Components/UI/Button.vue'
import Toggle from '@/Components/UI/Toggle.vue'

const { theme, toggleTheme } = useTheme()
const preferencesStore = usePreferencesStore()

const isNsfw = computed({
    get: () => preferencesStore.mode === 'nsfw',
    set: (value) => {
        if ((value && preferencesStore.mode !== 'nsfw') || (!value && preferencesStore.mode === 'nsfw')) {
            preferencesStore.toggleMode()
        }
    }
})
</script>
