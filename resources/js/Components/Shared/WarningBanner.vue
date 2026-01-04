<template>
    <div v-if="flags.length > 0" class="space-y-3">
        <div
            v-for="flag in flags"
            :key="flag.id"
            :class="[
                'px-4 py-3 rounded-xl border-2 backdrop-blur-sm transition-all',
                getBannerClasses(flag.color)
            ]"
        >
            <div class="flex items-start gap-3">
                <!-- Icon -->
                <div v-if="flag.icon" class="shrink-0 text-lg/tight">
                    {{ flag.icon }}
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-semibold text-sm/tight">
                            {{ flag.name }}
                        </h4>
                    </div>
                    <p v-if="flag.description" class="text-sm/relaxed opacity-90">
                        {{ flag.description }}
                    </p>
                    <p v-if="flag.pivot?.notes" class="text-xs/relaxed mt-2 opacity-80 italic">
                        {{ flag.pivot.notes }}
                    </p>
                </div>

                <!-- Warning Icon -->
                <div class="shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    flags: {
        type: Array,
        default: () => []
    }
});

const getBannerClasses = (color) => {
    const colorMap = {
        red: 'bg-red-50/80 dark:bg-red-950/50 border-red-300 dark:border-red-800 text-red-900 dark:text-red-100',
        yellow: 'bg-gold-50/80 dark:bg-gold-950/50 border-gold-300 dark:border-gold-800 text-gold-900 dark:text-gold-100',
        orange: 'bg-orange-50/80 dark:bg-orange-950/50 border-orange-300 dark:border-orange-800 text-orange-900 dark:text-orange-100',
        blue: 'bg-blue-50/80 dark:bg-blue-950/50 border-blue-300 dark:border-blue-800 text-blue-900 dark:text-blue-100',
        purple: 'bg-purple-50/80 dark:bg-purple-950/50 border-purple-300 dark:border-purple-800 text-purple-900 dark:text-purple-100',
        green: 'bg-sage-50/80 dark:bg-sage-950/50 border-sage-300 dark:border-sage-800 text-sage-900 dark:text-sage-100'
    };

    return colorMap[color] || colorMap.red;
};
</script>
