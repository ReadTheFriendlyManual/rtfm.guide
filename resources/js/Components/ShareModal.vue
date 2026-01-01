<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isOpen"
                class="fixed inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 z-50"
                @click.self="close"
            >
                <Transition
                    enter-active-class="transition-all duration-200"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition-all duration-200"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        v-if="isOpen"
                        class="bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-6 max-w-lg w-full"
                    >
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-white">
                                Share Guide
                            </h2>
                            <button
                                @click="close"
                                class="text-pearl-500 hover:text-pearl-700 dark:hover:text-pearl-300 transition-colors"
                            >
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Loading State -->
                        <div v-if="isLoading" class="flex items-center justify-center py-12">
                            <div class="size-8 border-4 border-wine-600 border-t-transparent rounded-full animate-spin"></div>
                        </div>

                        <!-- Share Links -->
                        <div v-else-if="shareLinks" class="space-y-4">
                            <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400 mb-6">
                                Choose which version to share:
                            </p>

                            <!-- SFW Link -->
                            <div class="p-4 rounded-xl border-2 transition-all"
                                :class="currentMode === 'sfw'
                                    ? 'bg-sage-50 dark:bg-sage-900/20 border-sage-400 dark:border-sage-600'
                                    : 'bg-pearl-50 dark:bg-pearl-900/50 border-pearl-300 dark:border-pearl-700'"
                            >
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-pearl-900 dark:text-white">
                                            Safe for Work
                                        </h3>
                                        <span v-if="currentMode === 'sfw'" class="text-xs font-semibold px-2 py-1 rounded-lg bg-sage-200 dark:bg-sage-800 text-sage-900 dark:text-sage-200">
                                            Current
                                        </span>
                                    </div>
                                    <button
                                        @click="copyLink('sfw')"
                                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all"
                                        :class="copiedLink === 'sfw'
                                            ? 'bg-sage-600 text-white'
                                            : 'bg-wine-600 hover:bg-wine-700 text-white'"
                                    >
                                        {{ copiedLink === 'sfw' ? 'Copied!' : 'Copy' }}
                                    </button>
                                </div>
                                <div class="bg-white dark:bg-pearl-800 border-2 border-pearl-200 dark:border-pearl-700 rounded-lg p-3 font-mono text-sm/tight text-pearl-700 dark:text-pearl-300 break-all">
                                    {{ shareLinks.sfw }}
                                </div>
                            </div>

                            <!-- NSFW Link -->
                            <div class="p-4 rounded-xl border-2 transition-all"
                                :class="currentMode === 'nsfw'
                                    ? 'bg-wine-50 dark:bg-wine-900/20 border-wine-400 dark:border-wine-600'
                                    : 'bg-pearl-50 dark:bg-pearl-900/50 border-pearl-300 dark:border-pearl-700'"
                            >
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-pearl-900 dark:text-white">
                                            Not Safe for Work
                                        </h3>
                                        <span v-if="currentMode === 'nsfw'" class="text-xs font-semibold px-2 py-1 rounded-lg bg-wine-200 dark:bg-wine-800 text-wine-900 dark:text-wine-200">
                                            Current
                                        </span>
                                    </div>
                                    <button
                                        @click="copyLink('nsfw')"
                                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all"
                                        :class="copiedLink === 'nsfw'
                                            ? 'bg-sage-600 text-white'
                                            : 'bg-wine-600 hover:bg-wine-700 text-white'"
                                    >
                                        {{ copiedLink === 'nsfw' ? 'Copied!' : 'Copy' }}
                                    </button>
                                </div>
                                <div class="bg-white dark:bg-pearl-800 border-2 border-pearl-200 dark:border-pearl-700 rounded-lg p-3 font-mono text-sm/tight text-pearl-700 dark:text-pearl-300 break-all">
                                    {{ shareLinks.nsfw }}
                                </div>
                            </div>
                        </div>

                        <!-- Error State -->
                        <div v-else-if="error" class="py-8 text-center">
                            <p class="text-red-600 dark:text-red-400 mb-4">{{ error }}</p>
                            <button
                                @click="loadShareLinks"
                                class="px-6 py-2.5 rounded-xl bg-wine-600 hover:bg-wine-700 text-white font-medium transition-all"
                            >
                                Try Again
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true,
    },
    guideId: {
        type: Number,
        required: true,
    },
    currentMode: {
        type: String,
        default: 'sfw',
    },
})

const emit = defineEmits(['close'])

const shareLinks = ref(null)
const isLoading = ref(false)
const error = ref(null)
const copiedLink = ref(null)

watch(() => props.isOpen, (newValue) => {
    if (newValue) {
        loadShareLinks()
    }
})

const close = () => {
    emit('close')
}

const loadShareLinks = async () => {
    isLoading.value = true
    error.value = null
    shareLinks.value = null

    try {
        const response = await axios.post(`/api/guides/${props.guideId}/share-links`)
        shareLinks.value = response.data
    } catch (err) {
        console.error('Failed to load share links:', err)
        error.value = 'Failed to generate share links. Please try again.'
    } finally {
        isLoading.value = false
    }
}

const copyLink = async (mode) => {
    const link = shareLinks.value[mode]

    try {
        await navigator.clipboard.writeText(link)
        copiedLink.value = mode

        setTimeout(() => {
            copiedLink.value = null
        }, 2000)
    } catch (err) {
        console.error('Failed to copy link:', err)

        const textArea = document.createElement('textarea')
        textArea.value = link
        textArea.style.position = 'fixed'
        textArea.style.left = '-999999px'
        document.body.appendChild(textArea)
        textArea.select()

        try {
            document.execCommand('copy')
            copiedLink.value = mode

            setTimeout(() => {
                copiedLink.value = null
            }, 2000)
        } catch (fallbackErr) {
            console.error('Fallback copy failed:', fallbackErr)
        }

        document.body.removeChild(textArea)
    }
}
</script>
