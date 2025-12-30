<template>
    <div class="bg-white dark:bg-pearl-800 border-2 border-pearl-300 dark:border-pearl-600 rounded-2xl p-5 shadow-sm">
        <!-- User Avatar Header -->
        <div class="flex items-center gap-3 mb-4">
            <div class="size-8 rounded-full bg-pearl-200 dark:bg-pearl-700 overflow-hidden flex items-center justify-center">
                <svg class="size-4 text-pearl-500 dark:text-pearl-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="h-2 w-20 bg-pearl-300 dark:bg-pearl-600 rounded mb-1.5"></div>
                <div class="h-1.5 w-12 bg-pearl-200 dark:bg-pearl-700 rounded"></div>
            </div>
        </div>

        <!-- Terminal Content -->
        <div class="bg-pearl-100 dark:bg-pearl-700/50 rounded-lg p-3 text-xs font-mono text-pearl-600 dark:text-pearl-400 text-left whitespace-nowrap">
            <div class="mb-1">
                <span class="text-pearl-500 dark:text-pearl-400">$</span>
                <span class="ml-1">composer global require rtfm-guide</span>
            </div>
            <div class="text-pearl-500 dark:text-pearl-500">&gt; Adding knowledge...</div>
            <div class="text-pearl-500 dark:text-pearl-500">&gt; Done!</div>
        </div>
    </div>
</template>

<script setup>
</script>

