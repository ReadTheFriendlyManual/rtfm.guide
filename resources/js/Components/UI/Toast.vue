<template>
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="translate-y-4 opacity-0 scale-95"
        enter-to-class="translate-y-0 opacity-100 scale-100"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100 scale-100"
        leave-to-class="translate-y-4 opacity-0 scale-95"
    >
        <div
            v-if="visible"
            class="fixed bottom-6 left-4 right-4 sm:left-auto sm:right-6 z-50 flex items-center justify-end gap-3 max-w-full"
            role="alert"
            :aria-live="type === 'error' ? 'assertive' : 'polite'"
        >
            <!-- Arrow and Text - Desktop (Left of Toast) -->
            <div class="hidden lg:flex items-center gap-3 animate-bounce-subtle pointer-events-none">
                <div class="text-right px-4 py-2 bg-pearl-100/95 dark:bg-pearl-800/95 backdrop-blur-sm rounded-xl border-2 border-pearl-300 dark:border-pearl-600 shadow-lg">
                    <p class="text-sm/tight font-bold text-pearl-800 dark:text-pearl-100">
                        {{ arrowText }}
                    </p>
                </div>
                <svg class="size-12 text-pearl-700 dark:text-pearl-300 drop-shadow-lg shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>

            <!-- Arrow and Text - Mobile (Above Toast) -->
            <div class="flex lg:hidden absolute -top-24 left-1/2 pointer-events-none max-w-xs z-10">
                <div class="flex flex-col items-center gap-2 animate-bounce-subtle-vertical">
                    <div class="text-center px-4 py-2 bg-pearl-100/95 dark:bg-pearl-800/95 backdrop-blur-sm rounded-xl border-2 border-pearl-300 dark:border-pearl-600 shadow-lg">
                        <p class="text-xs/tight font-bold text-pearl-800 dark:text-pearl-100">
                            {{ arrowText }}
                        </p>
                    </div>
                    <svg class="size-8 text-pearl-700 dark:text-pearl-300 drop-shadow-lg shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </div>

            <!-- Toast -->
            <div class="max-w-md w-full sm:w-auto sm:min-w-96 pointer-events-auto shrink-0">
                <div
                    class="rounded-xl shadow-2xl backdrop-blur-xl border-2 p-5 flex items-start gap-4"
                    :class="toastClasses"
                >
                    <!-- Icon -->
                    <div class="shrink-0 mt-0.5">
                        <svg
                            v-if="type === 'success'"
                            class="size-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg
                            v-else-if="type === 'error'"
                            class="size-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg
                            v-else
                            class="size-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <!-- Message -->
                    <div class="flex-1 text-base/relaxed font-semibold">
                        {{ message }}
                    </div>

                    <!-- Close Button -->
                    <button
                        @click="close"
                        class="shrink-0 -mt-1 -mr-1 p-1 rounded-lg hover:bg-black/5 dark:hover:bg-white/5 transition-colors"
                        aria-label="Close notification"
                    >
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
    visible: Boolean,
    message: String,
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['success', 'error', 'info'].includes(value),
    },
})

const emit = defineEmits(['close'])

const toastClasses = computed(() => {
    const classes = {
        success: 'bg-emerald-50/95 dark:bg-emerald-900/90 border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300',
        error: 'bg-rose-50/95 dark:bg-rose-900/90 border-rose-200 dark:border-rose-700 text-rose-700 dark:text-rose-300',
        info: 'bg-sky-50/95 dark:bg-sky-900/90 border-sky-200 dark:border-sky-700 text-sky-700 dark:text-sky-300',
    }

    return classes[props.type]
})

const arrowText = computed(() => {
    const mode = page.props.preferences?.mode || 'sfw'
    const translations = page.props.translations?.toast?.arrow

    if (mode === 'nsfw') {
        return translations?.nsfw || "You don't RTFM but you better read this!"
    }

    return translations?.sfw || "Maybe you don't read manuals but you should read this!"
})

const close = () => {
    emit('close')
}
</script>

<style scoped>
@keyframes bounce-subtle {
    0%, 100% {
        transform: translateX(0);
    }
    50% {
        transform: translateX(-8px);
    }
}

.animate-bounce-subtle {
    animation: bounce-subtle 2s ease-in-out infinite;
}

@keyframes bounce-subtle-vertical {
    0%, 100% {
        transform: translateX(-50%) translateY(0);
    }
    50% {
        transform: translateX(-50%) translateY(-8px);
    }
}

.animate-bounce-subtle-vertical {
    animation: bounce-subtle-vertical 2s ease-in-out infinite;
}
</style>
