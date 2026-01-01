<template>
    <footer class="bg-pearl-50 dark:bg-pearl-950 border-t-2 border-pearl-200 dark:border-pearl-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="font-display text-2xl/tight font-bold mb-3">
                        <span class="text-pearl-900 dark:text-pearl-50">RTFM</span><span class="bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent">.guide</span>
                    </h3>
                    <p class="text-pearl-600 dark:text-pearl-400 max-w-md text-base/relaxed">
                        {{ tagline }}
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="font-display font-semibold text-pearl-900 dark:text-pearl-50 mb-3">Resources</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors font-medium">Guides</a></li>
                        <li><a href="#" class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors font-medium">Categories</a></li>
                        <li><a href="#" class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors font-medium">Search</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-display font-semibold text-pearl-900 dark:text-pearl-50 mb-3">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="https://docs.rtfm.guide/legal/privacy" class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors font-medium">Privacy</a></li>
                        <li><a href="https://docs.rtfm.guide/legal/terms" class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors font-medium">Terms</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t-2 border-pearl-200 dark:border-pearl-800 text-center text-pearl-600 dark:text-pearl-400 text-sm/relaxed">
                <p>&copy; {{ new Date().getFullYear() }} RTFM.guide. Built with <span class="text-wine-600 dark:text-wine-500">♥</span> for developers who forget to read the manual.</p>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue'
import { usePreferencesStore } from '@/Stores/preferences'

const preferencesStore = usePreferencesStore()

const tagline = computed(() => {
    return preferencesStore.mode === 'nsfw'
        ? "You should have RTFM... but we did it for you."
        : "We read the manual so you don't have to."
})
</script>
