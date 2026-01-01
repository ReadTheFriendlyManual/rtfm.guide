<template>
    <PublicLayout>
        <div class="bg-linear-to-br from-wine-600 via-wine-500 to-wine-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="font-display text-4xl/tight sm:text-5xl/tight font-bold">
                    Pending Revisions
                </h1>
                <p class="text-wine-100 text-lg/relaxed mt-4">
                    Review and moderate guide edits
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div v-if="revisions.data.length === 0" class="text-center py-12">
                <p class="text-pearl-500">No pending revisions to review</p>
            </div>

            <div v-else class="space-y-4">
                <Link
                    v-for="revision in revisions.data"
                    :key="revision.id"
                    :href="`/revisions/${revision.id}`"
                    class="block bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-6 hover:border-wine-500 transition-colors"
                >
                    <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-white mb-2">
                        {{ revision.title }}
                    </h3>
                    <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                        Guide: {{ revision.guide.title }} • By {{ revision.user.name }} • {{ revision.created_at }}
                    </p>
                </Link>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({
    revisions: Object,
})
</script>
