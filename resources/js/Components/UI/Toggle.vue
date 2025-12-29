<template>
    <Switch
        v-model="enabled"
        :class="enabled ? 'bg-sky-500' : 'bg-slate-300 dark:bg-slate-600'"
        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
    >
        <span class="sr-only">{{ label }}</span>
        <span
            :class="enabled ? 'translate-x-6' : 'translate-x-1'"
            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"
        />
    </Switch>
</template>

<script setup>
import { Switch } from '@headlessui/vue'
import { computed } from 'vue'

const props = defineProps({
    modelValue: Boolean,
    label: String,
})

const emit = defineEmits(['update:modelValue'])

const enabled = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})
</script>
