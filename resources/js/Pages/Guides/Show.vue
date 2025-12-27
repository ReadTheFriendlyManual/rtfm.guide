<template>
    <PublicLayout>
        <!-- RTFM Header -->
        <div class="bg-linear-to-br from-wine-600 via-wine-500 to-wine-700 text-white relative overflow-hidden">
            <div class="absolute inset-0 pattern-grid text-white/5"></div>
            <div class="absolute inset-0 texture-grain"></div>

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
                <div class="animate-fade-in-up">
                    <p class="font-display text-2xl sm:text-3xl font-bold mb-3">
                        {{ rtfmMessage }}
                    </p>
                    <p class="text-wine-100 text-sm">
                        But seriously, here's what you need to know:
                    </p>
                </div>
            </div>
        </div>

        <!-- Guide Content -->
        <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Meta Header -->
            <header class="mb-12">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <Link
                        :href="`/guides?category=${guide.category.slug}`"
                        class="text-xs font-semibold px-3 py-1.5 rounded-lg
                               bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400
                               hover:bg-wine-200 dark:hover:bg-wine-900/50 transition-colors"
                    >
                        {{ guide.category.name }}
                    </Link>
                    <span :class="[
                        'text-xs font-semibold px-3 py-1.5 rounded-lg capitalize',
                        guide.difficulty === 'beginner' ? 'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400' :
                        guide.difficulty === 'intermediate' ? 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400' :
                        'bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400'
                    ]">
                        {{ guide.difficulty }}
                    </span>
                    <span class="text-xs text-pearl-500 flex items-center gap-1.5">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ guide.estimated_minutes }} min read
                    </span>
                    <span class="text-xs text-pearl-500 flex items-center gap-1.5">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ guide.view_count }} views
                    </span>
                </div>

                <h1 class="font-display text-5xl sm:text-6xl font-bold text-pearl-900 dark:text-white leading-tight mb-8">
                    {{ guide.title }}
                </h1>

                <!-- TL;DR -->
                <div class="bg-gold-50 dark:bg-gold-900/10 border-l-4 border-gold-500 p-6 rounded-r-2xl">
                    <p class="font-bold text-gold-900 dark:text-gold-400 text-sm uppercase tracking-wide mb-2">TL;DR</p>
                    <p class="text-lg text-pearl-800 dark:text-pearl-200 leading-relaxed">{{ activeTldr }}</p>
                </div>
            </header>

            <!-- Rendered HTML Content -->
            <div
                v-html="activeHtml"
                class="article-content"
            ></div>

            <!-- Author Info -->
            <footer class="mt-16 pt-12 border-t-2 border-pearl-200 dark:border-pearl-800">
                <div class="flex items-center gap-5">
                    <div class="size-16 bg-linear-to-br from-wine-600 to-wine-700 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                        {{ guide.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <p class="font-bold text-lg text-pearl-900 dark:text-pearl-50">{{ guide.user.name }}</p>
                        <p class="text-sm text-pearl-600 dark:text-pearl-400">
                            Published {{ formatDate(guide.published_at) }}
                        </p>
                    </div>
                </div>
            </footer>
        </article>

        <!-- Related Guides -->
        <div v-if="relatedGuides.length" class="bg-pearl-50 dark:bg-pearl-950 border-t-2 border-pearl-200 dark:border-pearl-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h2 class="font-display text-3xl font-bold text-pearl-900 dark:text-pearl-50 mb-8">
                    Related Guides
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <Link
                        v-for="relatedGuide in relatedGuides"
                        :key="relatedGuide.id"
                        :href="`/guides/${relatedGuide.slug}`"
                        class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-6
                               bg-white dark:bg-pearl-800 hover:border-wine-400 dark:hover:border-wine-600
                               transition-all duration-300 hover:shadow-xl hover:shadow-wine-600/10 hover:scale-105"
                    >
                        <h3 class="font-display text-xl font-bold text-pearl-900 dark:text-pearl-50 mb-3
                                   group-hover:text-wine-600 dark:group-hover:text-wine-400 transition-colors">
                            {{ relatedGuide.title }}
                        </h3>
                        <p class="text-sm leading-relaxed text-pearl-600 dark:text-pearl-400">
                            {{ relatedGuide.tldr }}
                        </p>
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { usePreferencesStore } from '@/Stores/preferences'

const props = defineProps({
    guide: Object,
    content: Object,
    defaultMode: String,
    rtfmMessage: String,
    relatedGuides: Array,
})

// Use the global preferences store
const preferencesStore = usePreferencesStore()

// Initialize store mode from server-side default if needed
if (props.defaultMode && preferencesStore.mode !== props.defaultMode) {
    preferencesStore.setMode(props.defaultMode)
}

const activeTldr = computed(() => {
    return props.content[preferencesStore.mode]?.tldr || ''
})

const activeHtml = computed(() => {
    return props.content[preferencesStore.mode]?.html || ''
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const addCopyButtons = () => {
    const codeBlocks = document.querySelectorAll('.article-content pre')

    codeBlocks.forEach((pre) => {
        // Skip if button already exists
        if (pre.querySelector('.copy-button')) return

        const button = document.createElement('button')
        button.textContent = 'Copy'
        button.className = 'copy-button absolute top-3 right-3 px-4 py-2 text-xs font-bold rounded-lg bg-wine-600 hover:bg-wine-700 text-white transition-all shadow-lg hover:scale-105'

        button.addEventListener('click', async () => {
            const code = pre.querySelector('code')
            if (code) {
                await navigator.clipboard.writeText(code.textContent)
                button.textContent = 'Copied!'
                setTimeout(() => {
                    button.textContent = 'Copy'
                }, 2000)
            }
        })

        pre.style.position = 'relative'
        pre.appendChild(button)
    })
}

onMounted(() => {
    addCopyButtons()
})

// Re-add copy buttons when global mode changes
watch(() => preferencesStore.mode, () => {
    setTimeout(addCopyButtons, 10)
})
</script>

<style>
.article-content h1,
.article-content h2,
.article-content h3,
.article-content h4,
.article-content h5,
.article-content h6 {
    font-family: var(--font-display);
    font-weight: 700;
    line-height: 1.2;
    color: var(--color-pearl-900);
}

html.dark .article-content h1,
html.dark .article-content h2,
html.dark .article-content h3,
html.dark .article-content h4,
html.dark .article-content h5,
html.dark .article-content h6 {
    color: white;
}

.article-content h1 {
    font-size: 2.25rem;
    margin-top: 4rem;
    margin-bottom: 1.5rem;
}

.article-content h2 {
    font-size: 1.875rem;
    margin-top: 3.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--color-pearl-200);
}

html.dark .article-content h2 {
    border-bottom-color: var(--color-pearl-800);
}

.article-content h3 {
    font-size: 1.5rem;
    margin-top: 3rem;
    margin-bottom: 1.25rem;
}

.article-content h4 {
    font-size: 1.25rem;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
}

.article-content p {
    font-size: 1.125rem;
    line-height: 1.75;
    color: var(--color-pearl-700);
    margin-bottom: 1.5rem;
}

html.dark .article-content p {
    color: #e5e5e5;
}

.article-content ul,
.article-content ol {
    margin-top: 2rem;
    margin-bottom: 2rem;
    font-size: 1.125rem;
}

.article-content ul {
    list-style-type: disc;
    padding-left: 2rem;
}

.article-content ol {
    list-style-type: decimal;
    padding-left: 2rem;
}

.article-content li {
    color: var(--color-pearl-700);
    line-height: 1.75;
    margin-top: 0.75rem;
    margin-bottom: 0.75rem;
}

html.dark .article-content li {
    color: #e5e5e5;
}

.article-content li > ul,
.article-content li > ol {
    margin-top: 0.75rem;
    margin-bottom: 0.75rem;
}

.article-content a {
    color: var(--color-wine-600);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}

.article-content a:hover {
    text-decoration: underline;
    color: var(--color-wine-700);
}

html.dark .article-content a {
    color: var(--color-wine-400);
}

html.dark .article-content a:hover {
    color: var(--color-wine-300);
}

.article-content strong {
    color: var(--color-pearl-900);
    font-weight: 700;
}

html.dark .article-content strong {
    color: white;
}

.article-content em {
    font-style: italic;
    color: var(--color-pearl-800);
}

html.dark .article-content em {
    color: #d4d4d4;
}

.article-content code {
    font-size: 0.875rem;
    font-family: ui-monospace, monospace;
    color: var(--color-wine-600);
    background-color: var(--color-pearl-100);
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
}

html.dark .article-content code {
    color: var(--color-wine-400);
    background-color: var(--color-pearl-800);
}

.article-content pre {
    margin-top: 2.5rem;
    margin-bottom: 2.5rem;
    padding: 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    overflow-x: auto;
    border: 1px solid var(--color-pearl-700);
    background-color: #1e1e1e;
}

html.dark .article-content pre {
    border-color: var(--color-pearl-800);
}

.article-content pre code {
    font-size: 0.875rem;
    background: transparent;
    padding: 0;
    color: #f0f0f0;
}

.article-content blockquote {
    margin-top: 2.5rem;
    margin-bottom: 2.5rem;
    padding-left: 2rem;
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
    border-left: 4px solid var(--color-wine-500);
    background-color: var(--color-wine-50);
    border-radius: 0 1rem 1rem 0;
    font-style: italic;
    color: var(--color-pearl-700);
    font-size: 1.125rem;
    line-height: 1.75;
}

html.dark .article-content blockquote {
    background-color: rgba(219, 39, 119, 0.15);
    color: #e5e5e5;
}

.article-content blockquote p {
    margin-bottom: 1rem;
}

.article-content blockquote p:last-child {
    margin-bottom: 0;
}

.article-content table {
    margin-top: 2.5rem;
    margin-bottom: 2.5rem;
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border-radius: 0.75rem;
    overflow: hidden;
}

.article-content thead {
    background-color: var(--color-pearl-100);
}

html.dark .article-content thead {
    background-color: var(--color-pearl-800);
}

.article-content th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 700;
    color: var(--color-pearl-900);
    border: 1px solid var(--color-pearl-300);
}

html.dark .article-content th {
    color: white;
    border-color: var(--color-pearl-700);
}

.article-content td {
    padding: 1rem 1.5rem;
    border: 1px solid var(--color-pearl-200);
    color: var(--color-pearl-700);
}

html.dark .article-content td {
    border-color: var(--color-pearl-700);
    color: #e5e5e5;
}

.article-content tbody tr:hover {
    background-color: var(--color-pearl-50);
}

html.dark .article-content tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.2);
}

.article-content hr {
    margin-top: 4rem;
    margin-bottom: 4rem;
    border-top: 2px solid var(--color-pearl-200);
}

html.dark .article-content hr {
    border-top-color: var(--color-pearl-800);
}

.article-content img {
    margin-top: 2rem;
    margin-bottom: 2rem;
    border-radius: 1rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}
</style>
