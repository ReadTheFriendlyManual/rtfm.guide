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
                                Report Comment
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

                        <!-- Comment Preview -->
                        <div class="mb-6 p-4 rounded-xl bg-pearl-50 dark:bg-pearl-900/50 border-2 border-pearl-200 dark:border-pearl-700">
                            <p class="text-sm/tight font-medium text-pearl-700 dark:text-pearl-300 mb-2">
                                Comment by {{ comment.user.name }}
                            </p>
                            <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400 line-clamp-3">
                                {{ comment.content }}
                            </p>
                        </div>

                        <!-- Form -->
                        <form @submit.prevent="submitReport">
                            <!-- Reason -->
                            <div class="mb-4">
                                <label class="block text-sm/tight font-medium text-pearl-700 dark:text-pearl-300 mb-2">
                                    Reason for reporting
                                </label>
                                <select
                                    v-model="reason"
                                    class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-900 text-pearl-900 dark:text-white focus:outline-hidden focus:border-wine-500 dark:focus:border-wine-400"
                                    required
                                >
                                    <option value="">Select a reason...</option>
                                    <option value="spam">Spam</option>
                                    <option value="harassment">Harassment or Bullying</option>
                                    <option value="offensive_content">Offensive Content</option>
                                    <option value="misinformation">Misinformation</option>
                                    <option value="off_topic">Off Topic</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <label class="block text-sm/tight font-medium text-pearl-700 dark:text-pearl-300 mb-2">
                                    Additional details (optional)
                                </label>
                                <textarea
                                    v-model="description"
                                    rows="3"
                                    class="w-full px-4 py-3 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-900 text-pearl-900 dark:text-white placeholder-pearl-400 dark:placeholder-pearl-500 focus:outline-hidden focus:border-wine-500 dark:focus:border-wine-400 resize-none"
                                    placeholder="Provide any additional context..."
                                    maxlength="1000"
                                />
                                <p class="text-xs/tight text-pearl-500 mt-1">
                                    {{ description.length }}/1000 characters
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3">
                                <button
                                    type="submit"
                                    :disabled="isSubmitting || !reason"
                                    class="flex-1 px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ isSubmitting ? 'Reporting...' : 'Submit Report' }}
                                </button>
                                <button
                                    type="button"
                                    @click="close"
                                    :disabled="isSubmitting"
                                    class="px-6 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 text-pearl-700 dark:text-pearl-300 hover:border-wine-400 dark:hover:border-wine-600 font-medium transition-all disabled:opacity-50"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
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
    comment: {
        type: Object,
        default: null,
    },
})

const emit = defineEmits(['close', 'reported'])

const reason = ref('')
const description = ref('')
const isSubmitting = ref(false)

watch(() => props.isOpen, (newValue) => {
    if (newValue) {
        reason.value = ''
        description.value = ''
    }
})

const close = () => {
    if (!isSubmitting.value) {
        emit('close')
    }
}

const submitReport = async () => {
    if (!reason.value || !props.comment) return

    isSubmitting.value = true

    try {
        await axios.post(`/api/comments/${props.comment.id}/flag`, {
            reason: reason.value,
            description: description.value || null,
        })

        emit('reported')
        emit('close')

        alert('Thank you for your report. We will review it shortly.')
    } catch (error) {
        console.error('Failed to report comment:', error)

        if (error.response?.status === 422) {
            const message = error.response.data.message || 'Invalid report details.'
            alert(message)
        } else {
            alert('Failed to submit report. Please try again.')
        }
    } finally {
        isSubmitting.value = false
    }
}
</script>
