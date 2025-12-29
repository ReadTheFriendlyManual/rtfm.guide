import { defineStore } from 'pinia'
import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

export const usePreferencesStore = defineStore('preferences', () => {
    const page = usePage()

    // Always initialize from localStorage first for immediate rendering
    const theme = ref(localStorage.getItem('rtfm_theme') || 'light')
    const mode = ref(localStorage.getItem('rtfm_mode') || 'sfw')

    // Set initial cookie on load
    document.cookie = `rtfm_mode=${mode.value}; path=/; max-age=31536000; SameSite=Lax`

    const isAuthenticated = () => Boolean(page.props.auth?.user)

    const setTheme = (newTheme) => {
        theme.value = newTheme
        localStorage.setItem('rtfm_theme', newTheme)

        // Sync to backend if authenticated
        if (isAuthenticated()) {
            router.post('/api/preferences/theme',
                { theme: newTheme },
                {
                    preserveState: true,
                    preserveScroll: true,
                }
            )
        }
    }

    const setMode = (newMode) => {
        mode.value = newMode
        localStorage.setItem('rtfm_mode', newMode)

        // Set cookie for backend to read (for both guests and authenticated users)
        document.cookie = `rtfm_mode=${newMode}; path=/; max-age=31536000; SameSite=Lax`

        // Sync to backend if authenticated
        if (isAuthenticated()) {
            router.post('/api/preferences/mode',
                { mode: newMode },
                {
                    preserveState: true,
                    preserveScroll: true,
                }
            )
        }
    }

    const toggleTheme = () => {
        setTheme(theme.value === 'dark' ? 'light' : 'dark')
    }

    const toggleMode = () => {
        setMode(mode.value === 'sfw' ? 'nsfw' : 'sfw')
    }

    // Watch for backend user preference changes (e.g., on initial page load or login)
    // This ensures backend preferences override localStorage for authenticated users ONLY
    watch(() => page.props.auth?.user, (user) => {
        if (user) {
            // User is authenticated - sync from backend if different
            if (user.preferred_theme && user.preferred_theme !== theme.value) {
                theme.value = user.preferred_theme
                localStorage.setItem('rtfm_theme', user.preferred_theme)
            }

            if (user.preferred_rtfm_mode && user.preferred_rtfm_mode !== mode.value) {
                mode.value = user.preferred_rtfm_mode
                localStorage.setItem('rtfm_mode', user.preferred_rtfm_mode)
            }
        }
    }, { immediate: true })

    return { theme, mode, setTheme, setMode, toggleTheme, toggleMode }
})
