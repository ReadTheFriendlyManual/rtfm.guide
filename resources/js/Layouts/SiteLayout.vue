<template>
    <div class="min-h-screen bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-white">
        <div class="absolute inset-x-0 top-0 -z-10 h-64 bg-gradient-to-b from-sky-500/20 via-indigo-500/10 to-transparent blur-3xl" />

        <header class="sticky top-0 z-20 border-b border-white/10 backdrop-blur-xl">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-500 text-lg font-semibold text-white shadow-lg shadow-sky-500/30">RT</span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">RTFM.guide</p>
                        <p class="text-lg font-bold leading-tight text-slate-900 dark:text-white">Read The Manual – or let us.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="flex items-center gap-2 rounded-full border border-white/10 bg-white/60 px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:bg-white/10 dark:text-white dark:hover:bg-white/15"
                        @click="onToggleMode(mode === 'sfw' ? 'nsfw' : 'sfw')"
                    >
                        <span class="text-base" aria-hidden="true">{{ mode === 'sfw' ? '🧊' : '🔥' }}</span>
                        <span>{{ mode === 'sfw' ? 'Spice it up' : 'Keep it friendly' }}</span>
                    </button>
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/60 text-lg text-slate-800 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:bg-white/10 dark:text-white dark:hover:bg-white/15"
                        @click="onToggleTheme()"
                        :aria-label="theme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme'"
                    >
                        <span v-if="theme === 'dark'">🌙</span>
                        <span v-else>☀️</span>
                    </button>
                </div>
            </div>
        </header>

        <main>
            <slot />
        </main>

        <footer class="border-t border-white/10 bg-white/70 backdrop-blur dark:bg-slate-900/70">
            <div class="mx-auto max-w-6xl px-6 py-10">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold">Built for operators who move fast.</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">RTFM.guide stays SFW until you say otherwise.</p>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400">
                        <span>Made by humans who actually read the docs.</span>
                        <span class="hidden sm:inline">•</span>
                        <span>Open source & community powered.</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
defineProps({
    mode: {
        type: String,
        default: 'sfw',
    },
    theme: {
        type: String,
        default: 'dark',
    },
    onToggleMode: {
        type: Function,
        default: () => {},
    },
    onToggleTheme: {
        type: Function,
        default: () => {},
    },
});
</script>
