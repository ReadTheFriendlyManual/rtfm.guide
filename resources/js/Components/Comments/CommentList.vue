<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-white">
                Comments
                <span v-if="comments.length > 0" class="text-pearl-500 dark:text-pearl-400">
                    ({{ comments.length }})
                </span>
            </h2>
        </div>

        <!-- Comment Form (for authenticated users) -->
        <div v-if="isAuthenticated">
            <CommentForm
                :guide-id="guideId"
                :reply-to="replyingTo"
                @submitted="refreshComments"
                @cancel-reply="replyingTo = null"
            />
        </div>

        <!-- Login prompt for guests -->
        <div v-else class="bg-wine-50 dark:bg-wine-900/20 border-2 border-wine-200 dark:border-wine-800 rounded-2xl p-6 text-center">
            <p class="text-pearl-700 dark:text-pearl-300 mb-4">
                Please sign in to leave a comment
            </p>
            <Link
                href="/login"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-wine-600 hover:bg-wine-700 text-white font-medium transition-all"
            >
                Sign In
            </Link>
        </div>

        <!-- Comments List -->
        <div v-if="comments.length > 0" class="space-y-6">
            <CommentItem
                v-for="comment in comments"
                :key="comment.id"
                :comment="comment"
                @reply="handleReply"
                @report="handleReport"
                @updated="refreshComments"
            />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <div class="inline-flex items-center justify-center size-16 rounded-full bg-pearl-100 dark:bg-pearl-800 mb-4">
                <svg class="size-8 text-pearl-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <p class="text-pearl-600 dark:text-pearl-400">
                No comments yet. Be the first to share your thoughts!
            </p>
        </div>

        <!-- Report Modal -->
        <ReportCommentModal
            :is-open="isReportModalOpen"
            :comment="reportingComment"
            @close="isReportModalOpen = false"
            @reported="handleReported"
        />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import CommentForm from './CommentForm.vue'
import CommentItem from './CommentItem.vue'
import ReportCommentModal from './ReportCommentModal.vue'

const props = defineProps({
    guideId: {
        type: Number,
        required: true,
    },
    initialComments: {
        type: Array,
        default: () => [],
    },
})

const page = usePage()
const comments = ref(props.initialComments)
const replyingTo = ref(null)
const isReportModalOpen = ref(false)
const reportingComment = ref(null)

const isAuthenticated = computed(() => {
    return page.props.auth?.user !== null && page.props.auth?.user !== undefined
})

const handleReply = (comment) => {
    replyingTo.value = comment

    // Scroll to comment form
    setTimeout(() => {
        const form = document.querySelector('textarea')
        if (form) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' })
            form.focus()
        }
    }, 100)
}

const handleReport = (comment) => {
    reportingComment.value = comment
    isReportModalOpen.value = true
}

const handleReported = () => {
    // Optionally show a success message
}

const refreshComments = () => {
    // Reload the page to get fresh comments
    router.reload({ only: ['comments'] })
}
</script>
