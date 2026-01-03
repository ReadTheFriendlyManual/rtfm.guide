<template>
    <div class="group border-2 border-pearl-200 dark:border-pearl-700 rounded-2xl p-6
                bg-white dark:bg-pearl-800 hover:border-wine-400 dark:hover:border-wine-600
                transition-all duration-300 hover:shadow-xl hover:shadow-wine-600/10">
        <!-- Writer Header -->
        <div class="flex items-start gap-4 mb-4">
            <!-- Avatar -->
            <Link
                :href="`/profile/${writer.id}`"
                class="shrink-0 group/avatar"
            >
                <div v-if="writer.avatar" class="size-16 rounded-xl overflow-hidden ring-2 ring-pearl-200 dark:ring-pearl-700 group-hover/avatar:ring-wine-400 dark:group-hover/avatar:ring-wine-600 transition-all">
                    <img :src="writer.avatar" :alt="writer.name" class="size-full object-cover" />
                </div>
                <div v-else class="size-16 rounded-xl bg-wine-100 dark:bg-wine-900/30 flex items-center justify-center ring-2 ring-pearl-200 dark:ring-pearl-700 group-hover/avatar:ring-wine-400 dark:group-hover/avatar:ring-wine-600 transition-all">
                    <span class="text-2xl/none font-bold text-wine-700 dark:text-wine-400">
                        {{ writer.name.charAt(0).toUpperCase() }}
                    </span>
                </div>
            </Link>

            <!-- Name & Featured Badge -->
            <div class="flex-1 min-w-0">
                <Link
                    :href="`/profile/${writer.id}`"
                    class="font-display text-xl/6 font-bold text-pearl-900 dark:text-pearl-50 hover:text-wine-600 dark:hover:text-wine-400 transition-colors block truncate"
                >
                    {{ writer.name }}
                </Link>
                <div class="flex items-center gap-1.5 mt-2">
                    <svg class="size-4 text-gold-600 dark:text-gold-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-xs/none font-bold text-gold-700 dark:text-gold-400">Featured Writer</span>
                </div>
            </div>
        </div>

        <!-- Bio -->
        <p v-if="displayBio" class="text-sm/6 text-pearl-600 dark:text-pearl-400 mb-4 line-clamp-3">
            {{ displayBio }}
        </p>

        <!-- Social Links -->
        <div v-if="hasSocialLinks" class="flex items-center gap-2 pt-4 border-t-2 border-pearl-100 dark:border-pearl-700">
            <!-- GitHub -->
            <a
                v-if="writer.github_username"
                :href="`https://github.com/${writer.github_username}`"
                target="_blank"
                rel="noopener noreferrer"
                class="size-9 rounded-lg bg-pearl-100 dark:bg-pearl-700 hover:bg-wine-100 dark:hover:bg-wine-900/30
                       text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400
                       flex items-center justify-center transition-all"
                title="GitHub"
            >
                <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
            </a>

            <!-- GitLab -->
            <a
                v-if="writer.gitlab_username"
                :href="`https://gitlab.com/${writer.gitlab_username}`"
                target="_blank"
                rel="noopener noreferrer"
                class="size-9 rounded-lg bg-pearl-100 dark:bg-pearl-700 hover:bg-wine-100 dark:hover:bg-wine-900/30
                       text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400
                       flex items-center justify-center transition-all"
                title="GitLab"
            >
                <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.955 13.587l-1.342-4.135-2.664-8.189a.455.455 0 00-.867 0L16.418 9.45H7.582L4.919 1.263a.455.455 0 00-.867 0L1.388 9.452.046 13.587a.924.924 0 00.331 1.023L12 23.054l11.623-8.443a.92.92 0 00.332-1.024"/>
                </svg>
            </a>

            <!-- Twitter/X -->
            <a
                v-if="writer.twitter_username"
                :href="`https://twitter.com/${writer.twitter_username}`"
                target="_blank"
                rel="noopener noreferrer"
                class="size-9 rounded-lg bg-pearl-100 dark:bg-pearl-700 hover:bg-wine-100 dark:hover:bg-wine-900/30
                       text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400
                       flex items-center justify-center transition-all"
                title="Twitter/X"
            >
                <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </a>

            <!-- LinkedIn -->
            <a
                v-if="writer.linkedin_username"
                :href="`https://linkedin.com/in/${writer.linkedin_username}`"
                target="_blank"
                rel="noopener noreferrer"
                class="size-9 rounded-lg bg-pearl-100 dark:bg-pearl-700 hover:bg-wine-100 dark:hover:bg-wine-900/30
                       text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400
                       flex items-center justify-center transition-all"
                title="LinkedIn"
            >
                <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
            </a>

            <!-- Website -->
            <a
                v-if="writer.website_url"
                :href="writer.website_url"
                target="_blank"
                rel="noopener noreferrer"
                class="size-9 rounded-lg bg-pearl-100 dark:bg-pearl-700 hover:bg-wine-100 dark:hover:bg-wine-900/30
                       text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400
                       flex items-center justify-center transition-all"
                title="Website"
            >
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </a>

            <!-- View Profile Link -->
            <Link
                :href="`/profile/${writer.id}`"
                class="ml-auto text-sm/none font-semibold text-wine-600 dark:text-wine-400 hover:text-wine-700 dark:hover:text-wine-300 transition-colors"
            >
                View Profile →
            </Link>
        </div>

        <!-- No social links - just view profile -->
        <div v-else class="pt-4 border-t-2 border-pearl-100 dark:border-pearl-700">
            <Link
                :href="`/profile/${writer.id}`"
                class="inline-flex items-center text-sm/none font-semibold text-wine-600 dark:text-wine-400 hover:text-wine-700 dark:hover:text-wine-300 transition-colors"
            >
                View Profile
                <svg class="size-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </Link>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    writer: {
        type: Object,
        required: true,
    },
})

const displayBio = computed(() => {
    return props.writer.featured_bio || props.writer.bio || null
})

const hasSocialLinks = computed(() => {
    return !!(
        props.writer.github_username ||
        props.writer.gitlab_username ||
        props.writer.twitter_username ||
        props.writer.linkedin_username ||
        props.writer.website_url
    )
})
</script>
