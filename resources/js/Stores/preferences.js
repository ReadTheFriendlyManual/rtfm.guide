import { defineStore } from 'pinia'
import { ref } from 'vue'

export const usePreferencesStore = defineStore('preferences', () => {
    const theme = ref(localStorage.getItem('rtfm_theme') || 'light')
    const mode = ref(localStorage.getItem('rtfm_mode') || 'sfw')

    const setTheme = (newTheme) => {
        theme.value = newTheme
        localStorage.setItem('rtfm_theme', newTheme)
    }

    const setMode = (newMode) => {
        mode.value = newMode
        localStorage.setItem('rtfm_mode', newMode)
    }

    const toggleTheme = () => {
        setTheme(theme.value === 'dark' ? 'light' : 'dark')
    }

    const toggleMode = () => {
        setMode(mode.value === 'sfw' ? 'nsfw' : 'sfw')
    }

    return { theme, mode, setTheme, setMode, toggleTheme, toggleMode }
})
