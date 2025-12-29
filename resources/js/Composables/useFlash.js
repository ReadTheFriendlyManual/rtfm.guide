import { usePage } from '@inertiajs/vue3'
import { computed, watch, ref } from 'vue'

export function useFlash() {
    const page = usePage()
    const showToast = ref(false)
    const toastMessage = ref('')
    const toastType = ref('success')

    const flash = computed(() => page.props.flash)

    watch(flash, (newFlash) => {
        if (newFlash.success) {
            toastMessage.value = newFlash.success
            toastType.value = 'success'
            showToast.value = true
            setTimeout(() => showToast.value = false, 5000)
        } else if (newFlash.error) {
            toastMessage.value = newFlash.error
            toastType.value = 'error'
            showToast.value = true
            setTimeout(() => showToast.value = false, 5000)
        }
    }, { immediate: true, deep: true })

    return { showToast, toastMessage, toastType }
}
