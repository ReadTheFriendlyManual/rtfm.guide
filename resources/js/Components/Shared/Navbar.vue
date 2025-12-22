<template>
    <nav class="fixed top-0 w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-bold bg-gradient-to-r from-sky-500 to-blue-600 bg-clip-text text-transparent">
                        RTFM.guide
                    </span>
                </div>

                <!-- Right side controls -->
                <div class="flex items-center gap-4">
                    <!-- NSFW/SFW Toggle -->
                    <div class="flex items-center gap-2" data-toggle-mode>
                        <span class="text-sm text-slate-600 dark:text-slate-400">SFW</span>
                        <Toggle v-model="isNsfw" label="Toggle NSFW mode" />
                        <span class="text-sm text-slate-600 dark:text-slate-400">NSFW</span>
                    </div>

                    <!-- Theme Toggle -->
                    <button
                        @click="toggleTheme"
                        class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors"
                        :aria-label="`Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`"
                    >
                        <svg v-if="theme === 'dark'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Sign In Button -->
                    <Button href="/login" variant="primary" size="sm">
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
