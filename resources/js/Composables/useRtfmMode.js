import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

export function useRtfmMode() {
    const mode = ref(localStorage.getItem('rtfm_mode') || 'sfw')

    const toggleMode = () => {
        mode.value = mode.value === 'sfw' ? 'nsfw' : 'sfw'
    }

    const setMode = (newMode) => {
        mode.value = newMode
    }

    watch(mode, (newMode) => {
        localStorage.setItem('rtfm_mode', newMode)

        // Sync to backend for authenticated users
        if (window.rtfm?.user?.id) {
            router.post('/api/preferences/mode',
                { mode: newMode },
                { preserveState: true, preserveScroll: true }
            )
        }
    })

    return { mode, toggleMode, setMode, isNsfw: () => mode.value === 'nsfw' }
}
