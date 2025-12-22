<template>
    <footer class="bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-sky-500 to-blue-600 bg-clip-text text-transparent mb-3">
                        RTFM.guide
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 max-w-md">
                        {{ tagline }}
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="font-semibold text-slate-900 dark:text-white mb-3">Resources</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-sky-500 dark:hover:text-sky-400 transition-colors">Guides</a></li>
                        <li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-sky-500 dark:hover:text-sky-400 transition-colors">Categories</a></li>
                        <li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-sky-500 dark:hover:text-sky-400 transition-colors">Search</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-semibold text-slate-900 dark:text-white mb-3">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-sky-500 dark:hover:text-sky-400 transition-colors">Privacy</a></li>
                        <li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-sky-500 dark:hover:text-sky-400 transition-colors">Terms</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-800 text-center text-slate-600 dark:text-slate-400 text-sm">
                <p>&copy; {{ new Date().getFullYear() }} RTFM.guide. Built with ❤️ for developers who forget to read the manual.</p>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue'
import { useRtfmMode } from '@/Composables/useRtfmMode'

const { mode } = useRtfmMode()

const tagline = computed(() => {
    return mode.value === 'nsfw'
        ? "You should have RTFM... but we did it for you."
        : "We read the manual so you don't have to."
})
</script>
