<template>
    <div class="relative">
        <div class="bg-pearl-900 dark:bg-white text-white dark:text-pearl-900 px-5 py-3 rounded-2xl text-sm sm:text-base font-display font-semibold shadow-lg whitespace-nowrap">
            <slot>It's in the docs!</slot>
        </div>
        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-pearl-900 dark:bg-white transform rotate-45"></div>
    </div>
</template>

