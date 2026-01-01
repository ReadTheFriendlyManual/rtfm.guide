<template>
    <div class="bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-6">
        <!-- Reply indicator -->
        <div v-if="replyTo" class="mb-4 p-3 rounded-xl bg-pearl-50 dark:bg-pearl-900/50 border-2 border-pearl-200 dark:border-pearl-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm/tight font-medium text-pearl-700 dark:text-pearl-300">
                    Replying to {{ replyTo.user.name }}
                </span>
                <button
                    @click="$emit('cancel-reply')"
                    class="text-pearl-500 hover:text-pearl-700 dark:hover:text-pearl-300"
                >
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-sm/tight text-pearl-600 dark:text-pearl-400 line-clamp-2">
                {{ replyTo.content }}
            </p>
        </div>

        <form @submit.prevent="submitComment">
            <textarea
                v-model="content"
                :placeholder="replyTo ? 'Write your reply...' : 'Share your thoughts...'"
                rows="4"
                class="w-full px-4 py-3 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-900 text-pearl-900 dark:text-white placeholder-pearl-400 dark:placeholder-pearl-500 focus:outline-hidden focus:border-wine-500 dark:focus:border-wine-400 resize-none"
                :disabled="isSubmitting"
            />

            <div class="flex items-center justify-between mt-4">
                <p class="text-sm/tight text-pearl-600 dark:text-pearl-400">
                    <span v-if="isFirstComment" class="font-medium text-gold-600 dark:text-gold-400">
                        Your first comment will be reviewed before appearing publicly.
                    </span>
                    <span v-else>
                        Markdown supported
                    </span>
                </p>

                <button
                    type="submit"
                    :disabled="isSubmitting || !content.trim()"
                    class="px-6 py-2.5 rounded-xl bg-wine-600 hover:bg-wine-700 text-white font-medium transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ isSubmitting ? 'Posting...' : (replyTo ? 'Post Reply' : 'Post Comment') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { useFathom } from '@/Composables/useFathom'

const { trackEvent } = useFathom()

const props = defineProps({
    guideId: {
        type: Number,
        required: true,
    },
    replyTo: {
        type: Object,
        default: null,
    },
})

const emit = defineEmits(['submitted', 'cancel-reply'])

const page = usePage()
const content = ref('')
const isSubmitting = ref(false)

const isFirstComment = computed(() => {
    // Check if user has any approved comments
    const user = page.props.auth?.user
    if (!user) return false

    // This would ideally come from the backend
    // For now, we'll show the message to all users
    return true
})

const submitComment = async () => {
    if (!content.value.trim()) return

    isSubmitting.value = true

    try {
        const payload = {
            content: content.value,
        }

        if (props.replyTo) {
            payload.parent_id = props.replyTo.id
        }

        await axios.post(`/api/guides/${props.guideId}/comments`, payload)

        content.value = ''
        emit('submitted')

        // Track comment submission
        if (props.replyTo) {
            trackEvent('comment_reply')
            emit('cancel-reply')
        } else {
            trackEvent('comment_posted')
        }
    } catch (error) {
        console.error('Failed to post comment:', error)

        if (error.response?.status === 422) {
            alert('Please check your comment and try again.')
        } else {
            alert('Failed to post comment. Please try again.')
        }
    } finally {
        isSubmitting.value = false
    }
}
</script>
