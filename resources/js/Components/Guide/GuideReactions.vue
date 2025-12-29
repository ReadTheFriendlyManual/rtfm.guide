<template>
    <div class="flex flex-wrap items-center gap-2">
        <button
            v-for="reaction in reactionTypes"
            :key="reaction.type"
            @click="toggleReaction(reaction.type)"
            :disabled="!page.props.auth.user || isProcessing"
            class="group inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            :class="getReactionClasses(reaction.type)"
            :title="!page.props.auth.user ? 'Sign in to react' : reaction.label"
        >
            <span class="text-lg/none">{{ reaction.icon }}</span>
            <span
                class="text-sm/tight font-medium transition-colors"
                :class="getReactionTextClasses(reaction.type)"
            >
                {{ localReactions[reaction.type] || 0 }}
            </span>
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

const page = usePage()

const props = defineProps({
    guideId: {
        type: Number,
        required: true,
    },
    reactions: {
        type: Object,
        required: true,
    },
    userReactions: {
        type: Array,
        default: () => [],
    },
})

const reactionTypes = [
    { type: 'helpful', label: 'This helped me', icon: '👍', color: 'sage' },
    { type: 'saved_me', label: 'This saved me time', icon: '💾', color: 'wine' },
    { type: 'outdated', label: 'This is outdated', icon: '⏰', color: 'gold' },
    { type: 'needs_update', label: 'Needs updating', icon: '🔄', color: 'wine' },
]

const localReactions = ref({ ...props.reactions })
const localUserReactions = ref([...props.userReactions])
const isProcessing = ref(false)

const userHasReaction = (type) => {
    return localUserReactions.value.includes(type)
}

const getReactionClasses = (type) => {
    const active = userHasReaction(type)

    if (active) {
        if (type === 'helpful') {
            return 'border-sage-500 bg-sage-100 dark:bg-sage-900/30'
        } else if (type === 'saved_me' || type === 'needs_update') {
            return 'border-wine-500 bg-wine-100 dark:bg-wine-900/30'
        } else if (type === 'outdated') {
            return 'border-gold-500 bg-gold-100 dark:bg-gold-900/30'
        }
    }

    return 'border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-800 hover:border-pearl-400 dark:hover:border-pearl-500'
}

const getReactionTextClasses = (type) => {
    const active = userHasReaction(type)

    if (active) {
        if (type === 'helpful') {
            return 'text-sage-700 dark:text-sage-300'
        } else if (type === 'saved_me' || type === 'needs_update') {
            return 'text-wine-700 dark:text-wine-300'
        } else if (type === 'outdated') {
            return 'text-gold-700 dark:text-gold-300'
        }
    }

    return 'text-pearl-700 dark:text-pearl-300'
}

const toggleReaction = async (type) => {
    if (!page.props.auth.user || isProcessing.value) {
        return
    }

    isProcessing.value = true
    const hadReaction = userHasReaction(type)

    try {
        if (hadReaction) {
            // Remove reaction
            const response = await axios.delete(`/api/guides/${props.guideId}/reactions`, {
                data: { type }
            })

            // Update local state
            localReactions.value = response.data.reactions
            localUserReactions.value = response.data.userReactions
        } else {
            // Add reaction
            const response = await axios.post(`/api/guides/${props.guideId}/reactions`, {
                type
            })

            // Update local state
            localReactions.value = response.data.reactions
            localUserReactions.value = response.data.userReactions
        }
    } catch (error) {
        console.error('Failed to toggle reaction:', error)
        // Optionally show error message to user
    } finally {
        isProcessing.value = false
    }
}
</script>
