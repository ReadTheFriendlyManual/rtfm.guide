<template>
    <footer class="bg-pearl-50 dark:bg-pearl-950 border-t-2 border-pearl-200 dark:border-pearl-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Newsletter Signup -->
            <div class="mb-12 max-w-2xl mx-auto">
                <div class="bg-linear-to-br from-wine-50/80 via-pearl-50 to-gold-50/50 dark:from-wine-950/30 dark:via-pearl-900 dark:to-gold-950/20 rounded-2xl p-8 border-2 border-wine-200/40 dark:border-wine-800/40 relative overflow-hidden">
                    <!-- Decorative background -->
                    <div class="absolute top-0 right-0 size-32 bg-linear-to-br from-gold-400/10 to-wine-400/10 dark:from-gold-500/5 dark:to-wine-500/5 rounded-full blur-3xl"></div>

                    <div class="relative">
                        <h3 class="font-display text-xl/tight font-bold text-pearl-900 dark:text-pearl-50 mb-2">
                            {{ newsletterHeading }}
                        </h3>
                        <p class="text-sm/relaxed text-pearl-700 dark:text-pearl-300 mb-4">
                            {{ newsletterDescription }}
                        </p>

                        <form @submit.prevent="submitForm">
                            <div class="flex items-start gap-3">
                                <div class="flex-1">
                                    <input
                                        id="newsletter-email"
                                        v-model="form.email"
                                        type="email"
                                        name="email"
                                        placeholder="your@email.com"
                                        required
                                        class="w-full px-4 py-2.5 rounded-xl border-2 border-pearl-300 dark:border-pearl-600 bg-white dark:bg-pearl-800 text-pearl-900 dark:text-pearl-50 focus:ring-3 focus:ring-wine-500/30 focus:border-wine-500 transition-all placeholder:text-pearl-400 dark:placeholder:text-pearl-500 text-sm"
                                        :class="{ 'border-wine-500 dark:border-wine-500': form.errors.email }"
                                    />
                                    <p v-if="form.errors.email" class="mt-1.5 text-xs/relaxed text-wine-600 dark:text-wine-400 font-medium">
                                        {{ form.errors.email }}
                                    </p>
                                </div>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="shrink-0 px-6 py-2.5 bg-linear-to-r from-wine-600 to-wine-700 hover:from-wine-500 hover:to-wine-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-wine-600/30 dark:shadow-wine-700/40 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                                >
                                    {{ form.processing ? newsletterButtonProcessing : newsletterButton }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="font-display text-2xl/tight font-bold mb-3">
                        <span class="text-pearl-900 dark:text-pearl-50">RTFM</span><span class="bg-linear-to-r from-wine-600 via-wine-500 to-gold-600 bg-clip-text text-transparent">.guide</span>
                    </h3>
                    <p class="text-pearl-600 dark:text-pearl-400 max-w-md text-base/relaxed">
                        {{ tagline }}
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="font-display font-semibold text-pearl-900 dark:text-pearl-50 mb-3">Resources</h4>
                    <ul class="space-y-2">
                        <li><Link href="/guides" class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors font-medium">Guides</Link></li>
                        <li><Link href="/categories" class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors font-medium">Categories</Link></li>
                        <li><Link href="/search" class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors font-medium">Search</Link></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-display font-semibold text-pearl-900 dark:text-pearl-50 mb-3">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="https://docs.rtfm.guide/legal/privacy" class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors font-medium">Privacy</a></li>
                        <li><a href="https://docs.rtfm.guide/legal/terms" class="text-pearl-600 dark:text-pearl-400 hover:text-wine-600 dark:hover:text-wine-400 transition-colors font-medium">Terms</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t-2 border-pearl-200 dark:border-pearl-800 text-center text-pearl-600 dark:text-pearl-400 text-sm/relaxed">
                <p>&copy; {{ new Date().getFullYear() }} RTFM.guide. Built with <span class="text-wine-600 dark:text-wine-500">♥</span> for developers who forget to read the manual.</p>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { usePreferencesStore } from '@/Stores/preferences'

const preferencesStore = usePreferencesStore()

const form = useForm({
    email: ''
})

const submitForm = () => {
    form.post('/newsletter/subscribe', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        }
    })
}

const tagline = computed(() => {
    return preferencesStore.mode === 'nsfw'
        ? "You should have RTFM... but we did it for you."
        : "We read the manual so you don't have to."
})

const newsletterHeading = computed(() => {
    return preferencesStore.mode === 'nsfw'
        ? "💀 Don't Miss the Good Shit"
        : "📬 Stay Updated"
})

const newsletterDescription = computed(() => {
    return preferencesStore.mode === 'nsfw'
        ? "Get weekly drops of unfucked guides and dev wisdom straight to your inbox. No bullshit, just the good stuff."
        : "Get weekly highlights of the best guides, tips, and updates delivered to your inbox."
})

const newsletterButton = computed(() => {
    return preferencesStore.mode === 'nsfw'
        ? "Sign Me Up"
        : "Subscribe"
})

const newsletterButtonProcessing = computed(() => {
    return preferencesStore.mode === 'nsfw'
        ? "Signing Up..."
        : "Subscribing..."
})
</script>
