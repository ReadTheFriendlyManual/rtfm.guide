<template>
    <component
        :is="href ? 'a' : 'button'"
        :href="href"
        :type="!href ? type : undefined"
        :class="buttonClasses"
        :disabled="disabled || loading"
    >
        <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <slot />
    </component>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'ghost', 'danger', 'link'].includes(value)
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    href: String,
    type: {
        type: String,
        default: 'button'
    },
    disabled: Boolean,
    loading: Boolean,
})

const buttonClasses = computed(() => {
    const base = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-hidden focus:ring-3 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed'

    const sizes = {
        sm: 'px-4 py-2 text-sm/tight rounded-xl',
        md: 'px-5 py-2.5 text-base/tight rounded-xl',
        lg: 'px-8 py-3.5 text-lg/tight rounded-2xl'
    }

    const variants = {
        primary: 'bg-wine-600 hover:bg-wine-700 text-white focus:ring-wine-500 shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40',
        secondary: 'bg-pearl-200 dark:bg-pearl-700 hover:bg-pearl-300 dark:hover:bg-pearl-600 text-pearl-900 dark:text-pearl-50 focus:ring-pearl-500',
        ghost: 'hover:bg-pearl-100 dark:hover:bg-pearl-800 text-pearl-700 dark:text-pearl-300 focus:ring-pearl-500',
        danger: 'bg-wine-600 hover:bg-wine-700 text-white focus:ring-wine-500 shadow-lg shadow-wine-600/30',
        link: 'text-wine-600 hover:text-wine-700 dark:text-wine-400 dark:hover:text-wine-300 focus:ring-wine-500'
    }

    return [base, sizes[props.size], variants[props.variant]].join(' ')
})
</script>
