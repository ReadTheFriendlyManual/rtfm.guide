<template>
    <PublicLayout>
        <div class="bg-linear-to-br from-wine-600 via-wine-500 to-wine-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="font-display text-4xl/tight sm:text-5xl/tight font-bold">
                    Review Revision
                </h1>
                <p class="text-wine-100 text-lg/relaxed mt-4">
                    Compare changes and approve or reject
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Current Version -->
                <div class="bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-6">
                    <h2 class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-white mb-4">
                        Current Version
                    </h2>
                    <div class="prose prose-sm dark:prose-invert max-w-none">
                        <h3>{{ guide.title }}</h3>
                        <p>{{ guide.tldr }}</p>
                    </div>
                </div>

                <!-- Proposed Changes -->
                <div class="bg-wine-50 dark:bg-wine-900/20 rounded-2xl border-2 border-wine-200 dark:border-wine-700 p-6">
                    <h2 class="font-display text-2xl/tight font-bold text-wine-900 dark:text-wine-100 mb-4">
                        Proposed Changes
                    </h2>
                    <div class="prose prose-sm dark:prose-invert max-w-none">
                        <h3>{{ revision.title }}</h3>
                        <p>{{ revision.tldr }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 mt-8">
                <Form :action="`/revisions/${revision.id}/approve`" method="post">
                    <button
                        type="submit"
                        class="bg-sage-600 hover:bg-sage-700 text-white font-semibold py-3 px-6 rounded-xl transition-all"
                    >
                        Approve Changes
                    </button>
                </Form>

                <button
                    @click="showRejectDialog = true"
                    class="bg-wine-600 hover:bg-wine-700 text-white font-semibold py-3 px-6 rounded-xl transition-all"
                >
                    Reject Changes
                </button>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Form } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({
    revision: Object,
    guide: Object,
})

const showRejectDialog = ref(false)
</script>
