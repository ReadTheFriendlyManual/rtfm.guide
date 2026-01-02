import { usePage } from '@inertiajs/vue3'
import { computed, watch, ref } from 'vue'

export function useFlash() {
    const page = usePage()
    const showToast = ref(false)
    const toastMessage = ref('')
    const toastType = ref('success')
    let timeoutId = null

    const flash = computed(() => page.props.flash)

    const hideToast = () => {
        showToast.value = false
        if (timeoutId) {
            clearTimeout(timeoutId)
            timeoutId = null
        }
    }

    const displayToast = (message, type = 'info') => {
        hideToast()
        toastMessage.value = message
        toastType.value = type
        showToast.value = true
        timeoutId = setTimeout(hideToast, 5000)
    }

    watch(flash, (newFlash) => {
        if (newFlash.success) {
            displayToast(newFlash.success, 'success')
        } else if (newFlash.error) {
            displayToast(newFlash.error, 'error')
        } else if (newFlash.info) {
            displayToast(newFlash.info, 'info')
        }
    }, { immediate: true, deep: true })

    return { showToast, toastMessage, toastType, hideToast }
}
