<template>
    <div
        class="bg-white dark:bg-pearl-800 border-2 border-pearl-300 dark:border-pearl-600 rounded-2xl p-5 shadow-sm"
    >
        <!-- User Avatar Header -->
        <div class="flex items-center gap-3 mb-4">
            <div class="size-8 rounded-full bg-gold-100 dark:bg-gold-900/30 overflow-hidden flex items-center justify-center">
                <svg class="size-4 text-gold-600 dark:text-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="h-2 w-16 bg-pearl-300 dark:bg-pearl-600 rounded mb-1.5"></div>
                <div class="h-1.5 w-10 bg-pearl-200 dark:bg-pearl-700 rounded"></div>
            </div>
        </div>

        <!-- Card Content -->
        <slot>
            <!-- Default skeleton content -->
            <div class="space-y-2">
                <div class="h-2 w-full bg-pearl-200 dark:bg-pearl-700 rounded"></div>
                <div class="h-2 w-5/6 bg-pearl-200 dark:bg-pearl-700 rounded"></div>
                <div class="h-2 w-4/6 bg-pearl-200 dark:bg-pearl-700 rounded"></div>
            </div>
        </slot>

        <!-- Card Footer -->
        <div v-if="showFooter" class="mt-4 flex justify-center">
            <svg class="size-5 text-pearl-300 dark:text-pearl-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </div>
    </div>
</template>

<script setup>
defineProps({
    showFooter: {
        type: Boolean,
        default: true
    }
})
</script>

