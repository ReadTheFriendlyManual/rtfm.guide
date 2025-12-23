<template>
    <PublicLayout>
        <!-- RTFM Header -->
        <div class="bg-linear-to-br from-wine-600 via-wine-500 to-wine-700 text-white relative overflow-hidden">
            <div class="absolute inset-0 pattern-grid text-white/5"></div>
            <div class="absolute inset-0 texture-grain"></div>

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center">
                <div class="animate-fade-in-up">
                    <p class="font-display text-2xl/tight sm:text-3xl/tight font-bold mb-2">
                        {{ rtfmMessage }}
                    </p>
                    <p class="text-wine-100 text-sm/relaxed">
                        But seriously, here's what you need to know:
                    </p>
                </div>
            </div>
        </div>

        <!-- Guide Content -->
        <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Meta Header -->
            <div class="mb-8">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <Link
                        :href="`/guides?category=${guide.category.slug}`"
                        class="text-xs/relaxed font-semibold px-3 py-1 rounded-lg
                               bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400
                               hover:bg-wine-200 dark:hover:bg-wine-900/50 transition-colors"
                    >
                        {{ guide.category.name }}
                    </Link>
                    <span :class="[
                        'text-xs/relaxed font-semibold px-3 py-1 rounded-lg capitalize',
                        guide.difficulty === 'beginner' ? 'bg-sage-100 dark:bg-sage-900/30 text-sage-700 dark:text-sage-400' :
                        guide.difficulty === 'intermediate' ? 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400' :
                        'bg-wine-100 dark:bg-wine-900/30 text-wine-700 dark:text-wine-400'
                    ]">
                        {{ guide.difficulty }}
                    </span>
                    <span class="text-xs/relaxed text-pearl-500 dark:text-pearl-500 flex items-center gap-1">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ guide.estimated_minutes }} min read
                    </span>
                    <span class="text-xs/relaxed text-pearl-500 dark:text-pearl-500 flex items-center gap-1">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ guide.view_count }} views
                    </span>
                </div>

                <h1 class="font-display text-4xl/tight sm:text-5xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-4">
                    {{ guide.title }}
                </h1>

                <!-- TL;DR -->
                <div class="bg-gold-50 dark:bg-gold-900/20 border-l-4 border-gold-600 p-4 rounded-r-xl">
                    <p class="font-semibold text-gold-900 dark:text-gold-400 text-sm/relaxed mb-1">TL;DR</p>
                    <p class="text-pearl-700 dark:text-pearl-300">{{ guide.tldr }}</p>
                </div>
            </div>

            <!-- Markdown Content -->
            <div
                v-html="renderedContent"
                class="prose prose-pearl dark:prose-invert max-w-none
                       prose-headings:font-display prose-headings:font-bold
                       prose-h1:text-3xl/tight prose-h2:text-2xl/tight prose-h3:text-xl/tight
                       prose-p:text-base/relaxed prose-li:text-base/relaxed
                       prose-a:text-wine-600 dark:prose-a:text-wine-400 prose-a:no-underline prose-a:font-semibold
                       hover:prose-a:text-wine-700 dark:hover:prose-a:text-wine-300
                       prose-code:text-wine-600 dark:prose-code:text-wine-400
                       prose-code:bg-pearl-100 dark:prose-code:bg-pearl-800
                       prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded-lg
                       prose-code:before:content-none prose-code:after:content-none
                       prose-pre:bg-pearl-900 dark:prose-pre:bg-pearl-950
                       prose-pre:border-2 prose-pre:border-pearl-700 dark:prose-pre:border-pearl-800
                       prose-pre:rounded-xl prose-pre:shadow-lg"
            ></div>

            <!-- Author Info -->
            <div class="mt-12 pt-8 border-t-2 border-pearl-200 dark:border-pearl-800">
                <div class="flex items-center gap-4">
                    <div class="size-12 bg-linear-to-br from-wine-600 to-wine-700 rounded-full flex items-center justify-center text-white font-bold">
                        {{ guide.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <p class="font-semibold text-pearl-900 dark:text-pearl-50">{{ guide.user.name }}</p>
                        <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                            Published {{ formatDate(guide.published_at) }}
                        </p>
                    </div>
                </div>
            </div>
        </article>

        <!-- Related Guides -->
        <div v-if="relatedGuides.length" class="bg-pearl-50 dark:bg-pearl-950 border-t-2 border-pearl-200 dark:border-pearl-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h2 class="font-display text-2xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-6">
                    Related Guides
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <Link
                        v-for="relatedGuide in relatedGuides"
                        :key="relatedGuide.id"
                        :href="`/guides/${relatedGuide.slug}`"
                        class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-6
                               bg-white dark:bg-pearl-800 hover:border-wine-400 dark:hover:border-wine-600
                               transition-all duration-300 hover:shadow-lg hover:shadow-wine-600/10"
                    >
                        <h3 class="font-display text-lg/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2
                                   group-hover:text-wine-600 dark:group-hover:text-wine-400 transition-colors">
                            {{ relatedGuide.title }}
                        </h3>
                        <p class="text-sm/relaxed text-pearl-600 dark:text-pearl-400">
                            {{ relatedGuide.tldr }}
                        </p>
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { marked } from 'marked'
import hljs from 'highlight.js'
import 'highlight.js/styles/atom-one-dark.css'

const props = defineProps({
    guide: Object,
    rtfmMessage: String,
    relatedGuides: Array,
})

// Configure marked
marked.setOptions({
    highlight: (code, lang) => {
        if (lang && hljs.getLanguage(lang)) {
            return hljs.highlight(code, { language: lang }).value
        }
        return hljs.highlightAuto(code).value
    },
    breaks: true,
    gfm: true,
})

const renderedContent = computed(() => {
    return marked.parse(props.guide.content || '')
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

onMounted(() => {
    // Add copy buttons to code blocks
    document.querySelectorAll('pre code').forEach((block) => {
        const pre = block.parentElement
        const button = document.createElement('button')
        button.textContent = 'Copy'
        button.className = 'absolute top-2 right-2 px-3 py-1 text-xs font-semibold rounded-lg bg-wine-600 hover:bg-wine-700 text-white transition-colors'

        button.addEventListener('click', async () => {
            await navigator.clipboard.writeText(block.textContent)
            button.textContent = 'Copied!'
            setTimeout(() => {
                button.textContent = 'Copy'
            }, 2000)
        })

        pre.style.position = 'relative'
        pre.appendChild(button)
    })
})
</script>
