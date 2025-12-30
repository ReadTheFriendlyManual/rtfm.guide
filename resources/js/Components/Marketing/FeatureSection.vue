<template>
    <div class="pt-16 pb-24 bg-white dark:bg-pearl-950 relative overflow-hidden -mt-16">
        <!-- Top gradient to blend with hero -->
        <div class="absolute top-0 left-0 right-0 h-24 bg-linear-to-b from-pearl-50 dark:from-pearl-950 to-transparent pointer-events-none"></div>

        <!-- Subtle background pattern -->
        <div class="absolute inset-0 pattern-grid text-pearl-200/20 dark:text-pearl-800/20"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="text-center mb-16 animate-fade-in-up">
                <h2 class="font-display text-4xl/tight sm:text-5xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-4">
                    Why Waste Time Reading?
                </h2>
                <p class="text-xl/relaxed text-pearl-600 dark:text-pearl-400">
                    We already did that for you.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group p-8 rounded-2xl bg-linear-to-br from-wine-50/50 to-wine-100/30 dark:from-pearl-900 dark:to-pearl-800 border-2 border-wine-200 dark:border-pearl-700 hover:border-wine-400 dark:hover:border-wine-700 hover:shadow-xl hover:shadow-wine-600/10 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="size-12 bg-linear-to-br from-wine-600 to-wine-700 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-wine-600/40 group-hover:scale-110 transition-transform duration-300">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
                        No BS Guides
                    </h3>
                    <p class="text-pearl-600 dark:text-pearl-400">
                        Skip the fluff. Get straight to the code that works.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-8 rounded-2xl bg-linear-to-br from-gold-50/50 to-gold-100/30 dark:from-pearl-900 dark:to-pearl-800 border-2 border-gold-200 dark:border-pearl-700 hover:border-gold-400 dark:hover:border-gold-700 hover:shadow-xl hover:shadow-gold-600/10 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="size-12 bg-linear-to-br from-gold-600 to-gold-700 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-gold-600/40 group-hover:scale-110 transition-transform duration-300">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
                        Copy-Paste Ready
                    </h3>
                    <p class="text-pearl-600 dark:text-pearl-400">
                        One-click to copy. Works first time, every time.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-8 rounded-2xl bg-linear-to-br from-sage-50/50 to-sage-100/30 dark:from-pearl-900 dark:to-pearl-800 border-2 border-sage-200 dark:border-pearl-700 hover:border-sage-400 dark:hover:border-sage-700 hover:shadow-xl hover:shadow-sage-600/10 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="size-12 bg-linear-to-br from-sage-600 to-sage-700 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-sage-600/40 group-hover:scale-110 transition-transform duration-300">
                        <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
                        Community Driven
                    </h3>
                    <p class="text-pearl-600 dark:text-pearl-400">
                        Real developers, real solutions, real attitude.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
