import { watch, onMounted, computed } from 'vue'
import { usePreferencesStore } from '@/Stores/preferences'

export function useTheme() {
    const preferencesStore = usePreferencesStore()

    // Use the preferences store as the source of truth
    const theme = computed({
        get: () => preferencesStore.theme,
        set: (value) => preferencesStore.setTheme(value)
    })

    const toggleTheme = () => {
        preferencesStore.toggleTheme()
    }

    const setTheme = (newTheme) => {
        preferencesStore.setTheme(newTheme)
    }

    // Watch for theme changes to update document class for Tailwind dark mode
    watch(() => preferencesStore.theme, (newTheme) => {
        document.documentElement.classList.toggle('dark', newTheme === 'dark')
    }, { immediate: true })

    // Apply theme class on mount
    onMounted(() => {
        document.documentElement.classList.toggle('dark', preferencesStore.theme === 'dark')
    })

    return { theme, toggleTheme, setTheme }
}
