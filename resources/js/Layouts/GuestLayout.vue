<template>
    <div class="min-h-dvh bg-linear-to-br from-pearl-50 via-white to-pearl-100 dark:from-pearl-950 dark:via-pearl-900 dark:to-pearl-900 flex flex-col relative overflow-hidden">
        <!-- Elegant background pattern -->
        <div class="absolute inset-0 pattern-grid text-pearl-200/20 dark:text-pearl-800/20"></div>

        <!-- Subtle grain texture -->
        <div class="absolute inset-0 texture-grain"></div>

        <!-- Warm color accent overlay -->
        <div class="absolute inset-0 bg-linear-to-tr from-wine-500/5 via-transparent to-gold-500/5 dark:from-wine-600/8 dark:via-transparent dark:to-gold-600/8"></div>

        <!-- Header -->
        <header class="relative z-10 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2 group">
                    <span class="font-display text-2xl/tight font-bold">
                        <span class="text-pearl-900 dark:text-pearl-50">RTFM</span><span class="bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent group-hover:from-wine-500 group-hover:to-gold-500 transition-all">.guide</span>
                    </span>
                </Link>

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
            </div>
        </header>

        <!-- Main Content -->
        <main class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
            <div class="w-full max-w-md animate-scale-in">
                <!-- Auth Card -->
                <div class="bg-white/90 dark:bg-pearl-900/90 backdrop-blur-xl rounded-2xl shadow-2xl shadow-wine-600/5 dark:shadow-wine-900/20 p-8 border-2 border-pearl-200 dark:border-pearl-700">
                    <slot />
                </div>

                <!-- Back to home link -->
                <div class="mt-6 text-center">
                    <Link href="/" class="text-sm/relaxed font-medium text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors">
                        ← Back to home
                    </Link>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-10 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto text-center text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                &copy; {{ new Date().getFullYear() }} RTFM.guide. All rights reserved.
            </div>
        </footer>

        <!-- Toast Notifications -->
        <Toast
            :visible="showToast"
            :message="toastMessage"
            :type="toastType"
            @close="hideToast"
        />
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { useTheme } from '@/Composables/useTheme'
import { useFlash } from '@/Composables/useFlash'
import Toast from '@/Components/UI/Toast.vue'

const { theme, toggleTheme } = useTheme()
const { showToast, toastMessage, toastType, hideToast } = useFlash()
</script>
