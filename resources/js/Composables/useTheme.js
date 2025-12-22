import { ref, watch, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

export function useTheme() {
    const getInitialTheme = () => {
        const stored = localStorage.getItem('rtfm_theme')
        if (stored) return stored
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    }

    const theme = ref(getInitialTheme())

    const toggleTheme = () => {
        theme.value = theme.value === 'dark' ? 'light' : 'dark'
    }

    const setTheme = (newTheme) => {
        theme.value = newTheme
    }

    watch(theme, (newTheme) => {
        localStorage.setItem('rtfm_theme', newTheme)
        document.documentElement.classList.toggle('dark', newTheme === 'dark')

        // Sync to backend for authenticated users
        if (window.rtfm?.user?.id) {
            router.post('/api/preferences/theme',
                { theme: newTheme },
                { preserveState: true, preserveScroll: true }
            )
        }
    })

    onMounted(() => {
        document.documentElement.classList.toggle('dark', theme.value === 'dark')
    })

    return { theme, toggleTheme, setTheme }
}
