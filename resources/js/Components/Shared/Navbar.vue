<template>
    <nav class="fixed top-0 w-full bg-white/80 dark:bg-pearl-950/90 backdrop-blur-xl border-b-2 border-pearl-200 dark:border-pearl-800 z-50 shadow-sm dark:shadow-pearl-900/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 gap-4">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2 shrink-0">
                    <span class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-pearl-50">
                        RTFM<span class="bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent">.guide</span>
                    </span>
                </Link>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-md">
                    <div class="relative w-full" v-click-outside="closeDropdown">
                        <input
                            v-model="searchQuery"
                            @input="handleSearchInput"
                            @keyup.enter="performSearch"
                            @focus="showDropdown = true"
                            type="search"
                            placeholder="Search guides..."
                            class="w-full px-4 py-2 pr-10 rounded-xl border-2 border-pearl-300 dark:border-pearl-600
                                   bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50
                                   focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500
                                   transition-all text-sm/tight
                                   placeholder:text-pearl-400 dark:placeholder:text-pearl-500"
                        />
                        <button
                            @click="performSearch"
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 rounded-lg
                                   text-pearl-500 hover:text-wine-600 dark:hover:text-wine-400
                                   hover:bg-pearl-100 dark:hover:bg-pearl-700 transition-all"
                        >
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        <!-- Dropdown Results -->
                        <Transition
                            enter-active-class="transition-all duration-200"
                            enter-from-class="opacity-0 -translate-y-2"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition-all duration-150"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 -translate-y-2"
                        >
                            <div
                                v-if="showDropdown && (searchResults.length > 0 || (searchQuery && !isSearching))"
                                class="absolute top-full mt-2 w-full bg-white dark:bg-pearl-800 rounded-2xl
                                       border-2 border-pearl-300 dark:border-pearl-600 shadow-xl shadow-pearl-900/10
                                       overflow-hidden z-50"
                            >
                                <!-- Loading State -->
                                <div v-if="isSearching" class="px-4 py-3">
                                    <div class="flex items-center gap-2 text-sm/tight text-pearl-500">
                                        <div class="size-4 border-2 border-wine-600 border-t-transparent rounded-full animate-spin"></div>
                                        Searching...
                                    </div>
                                </div>

                                <!-- Results -->
                                <div v-else-if="searchResults.length > 0" class="max-h-96 overflow-y-auto">
                                    <Link
                                        v-for="result in searchResults"
                                        :key="result.id"
                                        :href="`/guides/${result.slug}`"
                                        @click="closeDropdown"
                                        class="block px-4 py-3 hover:bg-pearl-50 dark:hover:bg-pearl-700/50
                                               transition-colors border-b border-pearl-200 dark:border-pearl-700
                                               last:border-b-0"
                                    >
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs/tight font-semibold px-2 py-0.5 rounded-lg
                                                         bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400">
                                                {{ result.category }}
                                            </span>
                                            <span :class="[
                                                'text-xs/tight font-semibold px-2 py-0.5 rounded-lg capitalize',
                                                result.difficulty === 'beginner' ? 'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400' :
                                                result.difficulty === 'intermediate' ? 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400' :
                                                'bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400'
                                            ]">
                                                {{ result.difficulty }}
                                            </span>
                                        </div>
                                        <h3 class="font-semibold text-sm/tight text-pearl-900 dark:text-pearl-50 mb-1">
                                            {{ result.title }}
                                        </h3>
                                        <p class="text-xs/relaxed text-pearl-600 dark:text-pearl-400 line-clamp-2">
                                            {{ result.tldr }}
                                        </p>
                                    </Link>

                                    <!-- View All Results Link -->
                                    <button
                                        @click="performSearch"
                                        class="w-full px-4 py-3 text-sm/tight font-semibold text-wine-600 dark:text-wine-400
                                               hover:bg-pearl-50 dark:hover:bg-pearl-700/50 transition-colors text-center
                                               border-t-2 border-pearl-200 dark:border-pearl-600"
                                    >
                                        View all results for "{{ searchQuery }}"
                                    </button>
                                </div>

                                <!-- No Results -->
                                <div v-else class="px-4 py-6 text-center">
                                    <svg class="size-8 mx-auto text-pearl-300 dark:text-pearl-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <p class="text-sm/tight text-pearl-600 dark:text-pearl-400">
                                        No guides found for "{{ searchQuery }}"
                                    </p>
                                </div>
                            </div>
                        </Transition>
                    </div>
                </div>

                <!-- Right side controls -->
                <div class="flex items-center gap-4 shrink-0">
                    <!-- NSFW/SFW Toggle -->
                    <div class="flex items-center gap-2 text-xs/relaxed font-medium" data-toggle-mode>
                        <span class="text-pearl-500 dark:text-pearl-400">SFW</span>
                        <Toggle v-model="isNsfw" label="Toggle NSFW mode" />
                        <span class="text-wine-600 dark:text-wine-400">NSFW</span>
                    </div>

                    <!-- Theme Toggle -->
                    <button
                        @click="toggleTheme"
                        class="p-2 rounded-xl hover:bg-pearl-100 dark:hover:bg-pearl-800 text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-all duration-200"
                        :aria-label="`Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`"
                    >
                        <svg v-if="theme === 'dark'" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg v-else class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Sign In Button -->
                    <Button href="/login" variant="primary" size="sm" class="bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold shadow-lg shadow-wine-600/20">
                        Sign In
                    </Button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { useTheme } from '@/Composables/useTheme'
import { usePreferencesStore } from '@/Stores/preferences'
import Button from '@/Components/UI/Button.vue'
import Toggle from '@/Components/UI/Toggle.vue'
import { debounce } from 'lodash'
import axios from 'axios'

const { theme, toggleTheme } = useTheme()
const preferencesStore = usePreferencesStore()

const searchQuery = ref('')
const searchResults = ref([])
const showDropdown = ref(false)
const isSearching = ref(false)

const isNsfw = computed({
    get: () => preferencesStore.mode === 'nsfw',
    set: (value) => {
        if ((value && preferencesStore.mode !== 'nsfw') || (!value && preferencesStore.mode === 'nsfw')) {
            preferencesStore.toggleMode()
        }
    }
})

const fetchSearchResults = async () => {
    if (!searchQuery.value || searchQuery.value.length < 2) {
        searchResults.value = []
        return
    }

    isSearching.value = true

    try {
        const response = await axios.get('/api/search/quick', {
            params: {
                q: searchQuery.value
            }
        })
        searchResults.value = response.data.results
    } catch (error) {
        console.error('Search error:', error)
        searchResults.value = []
    } finally {
        isSearching.value = false
    }
}

const debouncedSearch = debounce(fetchSearchResults, 300)

const handleSearchInput = () => {
    showDropdown.value = true
    debouncedSearch()
}

const closeDropdown = () => {
    showDropdown.value = false
}

const performSearch = () => {
    if (searchQuery.value.trim()) {
        closeDropdown()
        router.visit(`/search?q=${encodeURIComponent(searchQuery.value)}`)
    }
}

// Click outside directive
const vClickOutside = {
    mounted(el, binding) {
        el.clickOutsideEvent = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value()
            }
        }
        document.addEventListener('click', el.clickOutsideEvent)
    },
    unmounted(el) {
        document.removeEventListener('click', el.clickOutsideEvent)
    }
}
</script>
