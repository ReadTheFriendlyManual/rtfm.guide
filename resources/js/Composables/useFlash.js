import { usePage } from '@inertiajs/vue3'
import { computed, watch, ref, onBeforeUnmount } from 'vue'

export function useFlash() {
    const page = usePage()
    const showToast = ref(false)
    const toastMessage = ref('')
    const toastType = ref('success')
    let timeoutId = null

    const flash = computed(() => page.props.flash)
    const mode = computed(() => page.props.preferences?.mode || 'sfw')

    const hideToast = () => {
        showToast.value = false
        if (timeoutId) {
            clearTimeout(timeoutId)
            timeoutId = null
        }
    }

    const translateMessage = (message) => {
        // Newsletter message translations
        const translations = {
            'newsletter.subscribed': {
                sfw: 'Thanks for subscribing! Please check your email to confirm your subscription.',
                nsfw: "Hell yeah! Check your inbox and confirm your email. Don't fucking ignore it."
            },
            'newsletter.verification_resent': {
                sfw: 'A verification email has been sent to your inbox. Please check your email.',
                nsfw: 'We sent another email to your inbox. Check your damn email and verify already.'
            }
        }

        // Check if message is a translation key
        if (translations[message]) {
            return translations[message][mode.value] || translations[message].sfw
        }

        // Return original message if no translation found
        return message
    }

    const displayToast = (message, type = 'info') => {
        hideToast()
        toastMessage.value = translateMessage(message)
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

    // Cleanup timeout on component unmount
    onBeforeUnmount(() => {
        if (timeoutId) {
            clearTimeout(timeoutId)
            timeoutId = null
        }
    })

    return { showToast, toastMessage, toastType, hideToast, displayToast }
}
