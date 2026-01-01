<template>
    <PublicLayout>
        <!-- Header -->
        <div class="bg-linear-to-br from-wine-600 via-wine-500 to-wine-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <Link href="/my-guides" class="inline-flex items-center gap-2 text-wine-100 hover:text-white transition-colors mb-4">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to My Guides
                </Link>
                <h1 class="font-display text-4xl/tight sm:text-5xl/tight font-bold mb-4">
                    Create a New Guide
                </h1>
                <p class="text-wine-100 text-lg/relaxed">
                    Share your knowledge with the RTFM community
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-8">
                        <Form
                            :action="route('guides.store')"
                            method="post"
                            @success="handleSuccess"
                            #default="{ errors, processing }"
                        >
                            <!-- Title -->
                            <div class="mb-6">
                                <label for="title" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                                    Guide Title
                                    <span class="text-wine-600">*</span>
                                </label>
                                <input
                                    id="title"
                                    type="text"
                                    name="title"
                                    required
                                    v-model="form.title"
                                    placeholder="e.g., How to Restart Nginx on Ubuntu 22.04"
                                    class="w-full px-4 py-3 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-900 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500"
                                    :class="{ 'border-wine-500': errors.title }"
                                />
                                <p v-if="errors.title" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400">
                                    {{ errors.title }}
                                </p>
                                <p v-if="form.title" class="mt-1.5 text-xs/relaxed text-pearl-500">
                                    URL: /guides/{{ slugify(form.title) }}
                                </p>
                            </div>

                            <!-- Category -->
                            <div class="mb-6">
                                <label for="category_id" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                                    Category
                                    <span class="text-wine-600">*</span>
                                </label>
                                <select
                                    id="category_id"
                                    name="category_id"
                                    required
                                    v-model="form.category_id"
                                    class="w-full px-4 py-3 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-900 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all"
                                    :class="{ 'border-wine-500': errors.category_id }"
                                >
                                    <option value="">Select a category...</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <p v-if="errors.category_id" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400">
                                    {{ errors.category_id }}
                                </p>
                            </div>

                            <!-- Difficulty & Estimated Time -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="difficulty" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                                        Difficulty
                                        <span class="text-wine-600">*</span>
                                    </label>
                                    <select
                                        id="difficulty"
                                        name="difficulty"
                                        required
                                        v-model="form.difficulty"
                                        class="w-full px-4 py-3 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-900 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all"
                                        :class="{ 'border-wine-500': errors.difficulty }"
                                    >
                                        <option value="">Select difficulty...</option>
                                        <option v-for="difficulty in difficulties" :key="difficulty.value" :value="difficulty.value">
                                            {{ difficulty.label }}
                                        </option>
                                    </select>
                                    <p v-if="errors.difficulty" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400">
                                        {{ errors.difficulty }}
                                    </p>
                                    <p v-if="selectedDifficulty" class="mt-1.5 text-xs/relaxed text-pearl-500">
                                        {{ selectedDifficulty.description }}
                                    </p>
                                </div>

                                <div>
                                    <label for="estimated_minutes" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                                        Estimated Time (minutes)
                                    </label>
                                    <input
                                        id="estimated_minutes"
                                        type="number"
                                        name="estimated_minutes"
                                        v-model.number="form.estimated_minutes"
                                        min="1"
                                        max="999"
                                        placeholder="e.g., 15"
                                        class="w-full px-4 py-3 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-900 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500"
                                        :class="{ 'border-wine-500': errors.estimated_minutes }"
                                    />
                                    <p v-if="errors.estimated_minutes" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400">
                                        {{ errors.estimated_minutes }}
                                    </p>
                                </div>
                            </div>

                            <!-- OS Tags -->
                            <div class="mb-6">
                                <label class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-3">
                                    Operating Systems / Platforms
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    <label
                                        v-for="tag in osTags"
                                        :key="tag"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border-2 cursor-pointer transition-all"
                                        :class="form.os_tags.includes(tag)
                                            ? 'bg-wine-100 dark:bg-wine-900/30 border-wine-500 text-wine-700 dark:text-wine-300'
                                            : 'bg-white dark:bg-pearl-900 border-pearl-300 dark:border-pearl-600 text-pearl-700 dark:text-pearl-300 hover:border-wine-400'"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="tag"
                                            v-model="form.os_tags"
                                            class="sr-only"
                                        />
                                        <span class="text-sm/tight font-medium">{{ tag }}</span>
                                    </label>
                                </div>
                                <p v-if="errors.os_tags" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400">
                                    {{ errors.os_tags }}
                                </p>
                            </div>

                            <!-- TL;DR -->
                            <div class="mb-6">
                                <label for="tldr" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300 mb-2">
                                    TL;DR (Quick Summary)
                                    <span class="text-wine-600">*</span>
                                </label>
                                <textarea
                                    id="tldr"
                                    name="tldr"
                                    required
                                    v-model="form.tldr"
                                    rows="3"
                                    maxlength="1000"
                                    placeholder="A brief summary of what this guide covers..."
                                    class="w-full px-4 py-3 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-900 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500 resize-none"
                                    :class="{ 'border-wine-500': errors.tldr }"
                                ></textarea>
                                <div class="flex items-center justify-between mt-1.5">
                                    <p v-if="errors.tldr" class="text-sm/relaxed text-wine-600 dark:text-wine-400">
                                        {{ errors.tldr }}
                                    </p>
                                    <p class="text-xs/relaxed text-pearl-500 ml-auto">
                                        {{ form.tldr.length }}/1000
                                    </p>
                                </div>
                            </div>

                            <!-- Content with Live Preview -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <label for="content" class="block text-sm/tight font-semibold text-pearl-700 dark:text-pearl-300">
                                        Guide Content (Markdown)
                                        <span class="text-wine-600">*</span>
                                    </label>
                                    <button
                                        type="button"
                                        @click="showPreview = !showPreview"
                                        class="text-sm/tight font-medium text-wine-600 dark:text-wine-400 hover:text-wine-700 dark:hover:text-wine-300 transition-colors"
                                    >
                                        {{ showPreview ? 'Hide Preview' : 'Show Preview' }}
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 gap-4" :class="showPreview ? 'lg:grid-cols-2' : ''">
                                    <div>
                                        <textarea
                                            id="content"
                                            name="content"
                                            required
                                            v-model="form.content"
                                            rows="20"
                                            placeholder="Write your guide content in Markdown...

## Example Heading

Here's how to do something:

1. First step
2. Second step
3. Third step

```bash
# Example code block
sudo systemctl restart nginx
```"
                                            class="w-full px-4 py-3 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-900 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500 resize-none font-mono text-sm/relaxed"
                                            :class="{ 'border-wine-500': errors.content }"
                                        ></textarea>
                                        <p v-if="errors.content" class="mt-1.5 text-sm/relaxed text-wine-600 dark:text-wine-400">
                                            {{ errors.content }}
                                        </p>
                                    </div>

                                    <div v-if="showPreview" class="rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-pearl-50 dark:bg-pearl-900 p-6 overflow-auto max-h-[500px]">
                                        <div class="prose prose-sm dark:prose-invert max-w-none" v-html="renderedContent"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-4 pt-6 border-t-2 border-pearl-200 dark:border-pearl-700">
                                <button
                                    type="submit"
                                    name="status"
                                    value="draft"
                                    :disabled="processing"
                                    class="flex-1 bg-pearl-200 dark:bg-pearl-700 hover:bg-pearl-300 dark:hover:bg-pearl-600 text-pearl-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ processing ? 'Saving...' : 'Save as Draft' }}
                                </button>
                                <button
                                    type="submit"
                                    name="status"
                                    value="pending"
                                    :disabled="processing"
                                    class="flex-1 bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ processing ? 'Submitting...' : 'Submit for Review' }}
                                </button>
                            </div>
                        </Form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Tips Card -->
                    <div class="bg-wine-50 dark:bg-wine-900/20 rounded-2xl border-2 border-wine-200 dark:border-wine-800 p-6">
                        <h3 class="font-display text-lg/tight font-bold text-wine-900 dark:text-wine-100 mb-4">
                            Writing Tips
                        </h3>
                        <ul class="space-y-3 text-sm/relaxed text-wine-800 dark:text-wine-200">
                            <li class="flex items-start gap-2">
                                <svg class="size-5 text-wine-600 dark:text-wine-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Be clear and concise</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="size-5 text-wine-600 dark:text-wine-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Use code blocks for commands</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="size-5 text-wine-600 dark:text-wine-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Include troubleshooting steps</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="size-5 text-wine-600 dark:text-wine-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Add prerequisites if needed</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Markdown Help Card -->
                    <div class="bg-white dark:bg-pearl-800 rounded-2xl border-2 border-pearl-200 dark:border-pearl-700 p-6">
                        <h3 class="font-display text-lg/tight font-bold text-pearl-900 dark:text-white mb-4">
                            Markdown Help
                        </h3>
                        <div class="space-y-3 text-sm/relaxed text-pearl-600 dark:text-pearl-400 font-mono">
                            <div>
                                <div class="text-pearl-800 dark:text-pearl-200">## Heading</div>
                                <div class="text-xs/tight text-pearl-500">Creates a heading</div>
                            </div>
                            <div>
                                <div class="text-pearl-800 dark:text-pearl-200">**bold text**</div>
                                <div class="text-xs/tight text-pearl-500">Makes text bold</div>
                            </div>
                            <div>
                                <div class="text-pearl-800 dark:text-pearl-200">```bash<br>code<br>```</div>
                                <div class="text-xs/tight text-pearl-500">Code block</div>
                            </div>
                            <div>
                                <div class="text-pearl-800 dark:text-pearl-200">`inline code`</div>
                                <div class="text-xs/tight text-pearl-500">Inline code</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Form, Link } from '@inertiajs/vue3'
import { marked } from 'marked'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { useFathom } from '@/Composables/useFathom'

const { trackEvent } = useFathom()

const props = defineProps({
    categories: Array,
    difficulties: Array,
    osTags: Array,
})

const form = ref({
    title: '',
    category_id: '',
    difficulty: '',
    estimated_minutes: null,
    os_tags: [],
    tldr: '',
    content: '',
})

const showPreview = ref(false)

const selectedDifficulty = computed(() => {
    return props.difficulties.find(d => d.value === form.value.difficulty)
})

const renderedContent = computed(() => {
    if (!form.value.content) return '<p class="text-pearl-400 dark:text-pearl-500">Preview will appear here...</p>'
    return marked(form.value.content)
})

function slugify(text) {
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
}

function route(name, params) {
    if (name === 'guides.store') {
        return '/guides'
    }
    return '/'
}

function handleSuccess(page) {
    // Track guide creation based on status
    const status = page.props?.flash?.guide_status || 'draft'

    if (status === 'draft') {
        trackEvent('guide_saved_draft')
    } else if (status === 'pending') {
        trackEvent('guide_submitted_review')
    }
}
</script>
