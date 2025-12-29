<template>
    <nav aria-label="Breadcrumb" class="mb-8">
        <ol class="flex items-center gap-2 text-sm/tight flex-wrap">
            <li v-for="(item, index) in items" :key="index" class="flex items-center gap-2">
                <Link
                    v-if="item.href && index < items.length - 1"
                    :href="item.href"
                    class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400
                           font-medium transition-colors"
                >
                    {{ item.label }}
                </Link>
                <span
                    v-else
                    class="text-pearl-900 dark:text-pearl-50 font-semibold"
                    :aria-current="index === items.length - 1 ? 'page' : undefined"
                >
                    {{ item.label }}
                </span>

                <svg
                    v-if="index < items.length - 1"
                    class="size-4 text-pearl-400 dark:text-pearl-600 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
        </ol>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    items: {
        type: Array,
        required: true,
        // Expected format: [{ label: 'Home', href: '/' }, { label: 'Guides', href: '/guides' }, { label: 'Current' }]
    }
})
</script>
