<template>
    <div class="group">
        <!-- Comment -->
        <div class="flex gap-4">
            <!-- Avatar -->
            <div class="shrink-0">
                <div class="size-10 rounded-xl bg-wine-100 dark:bg-wine-900/30 flex items-center justify-center text-wine-700 dark:text-wine-400 font-bold">
                    {{ comment.user.name.charAt(0).toUpperCase() }}
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-4">
                    <!-- Header -->
                    <div class="flex items-center gap-2 mb-2">
                        <span class="font-semibold text-pearl-900 dark:text-white">
                            {{ comment.user.name }}
                        </span>
                        <span class="text-xs/tight text-pearl-500">
                            {{ comment.created_at }}
                        </span>
                        <span v-if="!comment.is_approved" class="text-xs/tight font-semibold px-2 py-0.5 rounded-lg bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400">
                            Pending Approval
                        </span>
                        <span v-if="comment.updated_at !== comment.created_at" class="text-xs/tight text-pearl-500">
                            (edited)
                        </span>
                    </div>

                    <!-- Comment Content -->
                    <div v-if="!isEditing" class="text-pearl-700 dark:text-pearl-300 whitespace-pre-wrap break-words">
                        {{ comment.content }}
                    </div>

                    <!-- Edit Form -->
                    <div v-else class="space-y-3">
                        <textarea
                            v-model="editContent"
                            rows="3"
                            class="w-full px-4 py-3 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-900 text-pearl-900 dark:text-white focus:outline-hidden focus:border-wine-500 dark:focus:border-wine-400 resize-none"
                            placeholder="Edit your comment..."
                        />
                        <div class="flex gap-2">
                            <button
                                @click="saveEdit"
                                :disabled="isSaving || !editContent.trim()"
                                class="px-4 py-2 rounded-xl bg-wine-600 hover:bg-wine-700 text-white font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ isSaving ? 'Saving...' : 'Save' }}
                            </button>
                            <button
                                @click="cancelEdit"
                                :disabled="isSaving"
                                class="px-4 py-2 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 text-pearl-700 dark:text-pearl-300 hover:border-wine-400 dark:hover:border-wine-600 font-medium transition-colors disabled:opacity-50"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div v-if="!isEditing" class="flex items-center gap-4 mt-3 pt-3 border-t-2 border-pearl-100 dark:border-pearl-700">
                        <button
                            @click="$emit('reply', comment)"
                            class="text-sm/tight font-medium text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors"
                        >
                            Reply
                        </button>

                        <button
                            v-if="comment.can_edit"
                            @click="startEdit"
                            class="text-sm/tight font-medium text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors"
                        >
                            Edit
                        </button>

                        <button
                            v-if="comment.can_delete"
                            @click="deleteComment"
                            :disabled="isDeleting"
                            class="text-sm/tight font-medium text-pearl-600 dark:text-pearl-400 hover:text-red-600 dark:hover:text-red-400 transition-colors disabled:opacity-50"
                        >
                            {{ isDeleting ? 'Deleting...' : 'Delete' }}
                        </button>

                        <button
                            v-if="!comment.can_edit"
                            @click="$emit('report', comment)"
                            class="text-sm/tight font-medium text-pearl-600 dark:text-pearl-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                        >
                            Report
                        </button>
                    </div>
                </div>

                <!-- Replies -->
                <div v-if="comment.replies && comment.replies.length > 0" class="mt-4 space-y-4 ml-4 pl-4 border-l-2 border-pearl-200 dark:border-pearl-700">
                    <CommentItem
                        v-for="reply in comment.replies"
                        :key="reply.id"
                        :comment="reply"
                        @reply="$emit('reply', $event)"
                        @report="$emit('report', $event)"
                        @updated="$emit('updated')"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['reply', 'report', 'updated'])

const isEditing = ref(false)
const editContent = ref('')
const isSaving = ref(false)
const isDeleting = ref(false)

const startEdit = () => {
    editContent.value = props.comment.content
    isEditing.value = true
}

const cancelEdit = () => {
    isEditing.value = false
    editContent.value = ''
}

const saveEdit = async () => {
    if (!editContent.value.trim()) return

    isSaving.value = true

    try {
        await axios.put(`/api/comments/${props.comment.id}`, {
            content: editContent.value,
        })

        isEditing.value = false
        emit('updated')
    } catch (error) {
        console.error('Failed to update comment:', error)
        alert('Failed to update comment. Please try again.')
    } finally {
        isSaving.value = false
    }
}

const deleteComment = async () => {
    if (!confirm('Are you sure you want to delete this comment?')) return

    isDeleting.value = true

    try {
        await axios.delete(`/api/comments/${props.comment.id}`)
        emit('updated')
    } catch (error) {
        console.error('Failed to delete comment:', error)
        alert('Failed to delete comment. Please try again.')
    } finally {
        isDeleting.value = false
    }
}
</script>
