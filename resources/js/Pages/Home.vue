<template>
    <SiteLayout :mode="mode" :theme="theme" :on-toggle-mode="toggleMode" :on-toggle-theme="toggleTheme">
        <section class="mx-auto max-w-6xl px-6 py-16 sm:py-20">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200/60 bg-white/70 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-slate-300">
                        <span class="text-base" aria-hidden="true">{{ mode === 'sfw' ? '🧭' : '💥' }}</span>
                        <span>{{ mode === 'sfw' ? 'Friendly voice' : 'No-BS voice' }}</span>
                    </div>
                    <div class="space-y-4">
                        <h1 class="text-4xl font-black leading-tight text-slate-900 sm:text-5xl sm:leading-tight dark:text-white" v-text="activeCopy.hero" />
                        <p class="text-lg text-slate-700 sm:text-xl dark:text-slate-300" v-html="activeCopy.tagline" />
                        <p class="text-base text-slate-600 dark:text-slate-400" v-text="activeCopy.description" />
                    </div>
                    <div class="flex flex-wrap items-center gap-4">
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-500 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-sky-500/30 transition hover:-translate-y-0.5 hover:shadow-xl"
                            @click="toggleMode(mode === 'sfw' ? 'nsfw' : 'sfw')"
                        >
                            <span>{{ mode === 'sfw' ? 'Show me the NSFW version' : 'Switch back to SFW' }}</span>
                            <span aria-hidden="true">→</span>
                        </button>
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-base font-semibold text-slate-800 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-400 hover:text-indigo-600 dark:border-white/10 dark:bg-white/5 dark:text-white dark:hover:border-indigo-400"
                            @click="toggleTheme"
                        >
                            <span>{{ theme === 'dark' ? 'Light mode' : 'Dark mode' }}</span>
                            <span aria-hidden="true">{{ theme === 'dark' ? '☀️' : '🌙' }}</span>
                        </button>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/5">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center gap-3">
                                <span class="grid h-12 w-12 place-items-center rounded-xl bg-sky-100 text-lg text-sky-700 dark:bg-sky-500/10 dark:text-sky-200">⚡️</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white">Pre-built guides, ready to copy</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Every answer comes with context, commands, and shortcuts.</p>
                                </div>
                            </div>
                            <div class="ms-auto flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                <span>NSFW stays hidden until you opt-in.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute inset-4 -z-10 rounded-3xl bg-gradient-to-br from-sky-500/20 via-indigo-500/10 to-transparent blur-3xl" />
                    <div class="rounded-3xl border border-slate-200/60 bg-white/80 shadow-2xl shadow-sky-500/10 ring-1 ring-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-slate-900/80 dark:ring-white/10">
                        <div class="space-y-6 p-8">
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Early access</p>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Get the drop when we launch</h2>
                                <p class="text-sm text-slate-600 dark:text-slate-400">We only email when something useful ships. SFW by default, NSFW only if you ask.</p>
                            </div>
                            <form class="space-y-3" @submit.prevent="submit">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200">Work email</label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        autocomplete="email"
                                        required
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-white/10 dark:bg-white/10 dark:text-white dark:placeholder:text-slate-400"
                                        placeholder="you@company.com"
                                    />
                                    <p v-if="form.errors.email" class="text-sm text-rose-500">{{ form.errors.email }}</p>
                                </div>
                                <button
                                    type="submit"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-sky-500 px-4 py-3 text-base font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5 hover:shadow-xl"
                                    :disabled="form.processing"
                                >
                                    <span v-if="!subscribed">Join the beta waitlist</span>
                                    <span v-else>You're on the list!</span>
                                    <span aria-hidden="true">→</span>
                                </button>
                                <p v-if="successMessage" class="text-sm font-medium text-emerald-500">{{ successMessage }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">No spam. Just concise release notes and the occasional spicy tip.</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-y border-white/10 bg-gradient-to-b from-white via-slate-50 to-white py-16 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950">
            <div class="mx-auto max-w-6xl px-6">
                <div class="space-y-4 text-center">
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white">A preview of the guides you get on day one</h3>
                    <p class="text-lg text-slate-600 dark:text-slate-400">Every guide ships with the TL;DR, commands, context, and the optional NSFW take.</p>
                </div>
                <div class="mt-12 grid gap-8 lg:grid-cols-[1.4fr_1fr]">
                    <div class="rounded-3xl border border-slate-200/60 bg-white/90 p-6 shadow-xl shadow-slate-900/5 ring-1 ring-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-slate-900/80 dark:ring-white/10">
                        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                            <span class="text-slate-900 dark:text-white">{{ guide.path }}</span>
                            <span class="h-1 w-1 rounded-full bg-slate-300" />
                            <span>Last updated {{ guide.updated }} by {{ guide.author }}</span>
                        </div>
                        <h4 class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">{{ guide.title }}</h4>
                        <div class="mt-4 rounded-2xl bg-sky-50 p-4 text-sky-900 ring-1 ring-sky-100 dark:bg-sky-500/10 dark:text-sky-100 dark:ring-sky-500/30">
                            <div class="flex items-start gap-3">
                                <span class="text-xl">💡</span>
                                <div>
                                    <p class="text-sm font-semibold">Quick Answer (TL;DR)</p>
                                    <p class="text-sm leading-relaxed">{{ guide.summary }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 space-y-3">
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">Prerequisites</p>
                            <ul class="grid gap-2 sm:grid-cols-2">
                                <li v-for="(item, index) in guide.steps" :key="index" class="flex items-start gap-3 rounded-xl border border-slate-200/60 bg-white/70 px-4 py-3 text-sm text-slate-700 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                                    <span class="mt-0.5 text-sm">✔</span>
                                    <span>{{ item }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-6 rounded-xl bg-slate-950 p-4 text-sm text-slate-100 shadow-inner shadow-slate-900/50">
                            <div class="flex items-center justify-between text-slate-400">
                                <span class="text-xs uppercase tracking-[0.2em]">Commands</span>
                                <button type="button" class="text-xs font-semibold text-slate-200 hover:text-white" @click="copyCommands">Copy</button>
                            </div>
                            <pre class="mt-3 whitespace-pre-wrap text-sm leading-relaxed">{{ guide.commands.join('\n') }}</pre>
                        </div>
                        <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">{{ guide.footer }}</p>
                    </div>
                    <div class="flex flex-col justify-between gap-6">
                        <div class="rounded-3xl border border-slate-200/60 bg-white/90 p-6 shadow-xl shadow-slate-900/5 ring-1 ring-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-slate-900/80 dark:ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-300">Why RTFM.guide?</p>
                            <h4 class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">Answers that match your tone</h4>
                            <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">Stay in SFW mode when you share screens with the team. Flip the NSFW toggle (or use <code class="rounded bg-slate-900 px-2 py-1 text-white dark:bg-white dark:text-slate-900">?mode=nsfw</code>) when you want the blunt, no-fluff version.</p>
                            <div class="mt-4 grid gap-3">
                                <div class="flex items-center gap-3 rounded-xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-800 dark:bg-white/10 dark:text-white">
                                    <span class="text-lg">🧊</span>
                                    <span>SFW: Friendly guidance, polished for demos and docs.</span>
                                </div>
                                <div class="flex items-center gap-3 rounded-xl bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-100 ring-1 ring-rose-300/40 dark:bg-rose-500/20 dark:text-white dark:ring-rose-400/40">
                                    <span class="text-lg">🔥</span>
                                    <span>NSFW: Direct, irreverent, and impossible to misinterpret.</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid gap-4 rounded-3xl border border-slate-200/60 bg-gradient-to-br from-indigo-500/10 via-sky-500/10 to-white p-6 text-center shadow-xl shadow-slate-900/5 ring-1 ring-slate-900/5 backdrop-blur dark:border-white/10 dark:from-indigo-500/15 dark:via-sky-500/10 dark:to-slate-900/80 dark:ring-white/10">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-600 dark:text-slate-300">Launch metrics</p>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div v-for="stat in stats" :key="stat.label" class="rounded-2xl border border-white/20 bg-white/60 px-4 py-5 shadow-sm backdrop-blur dark:bg-white/5">
                                    <p class="text-3xl font-black text-slate-900 dark:text-white">{{ stat.value }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ stat.label }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-6 py-16">
            <div class="space-y-3 text-center">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">Designed for builders, contributors, and the quietly furious</h3>
                <p class="text-lg text-slate-600 dark:text-slate-400">Everything you need to read (or ignore) the manual faster.</p>
            </div>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="feature in features"
                    :key="feature.title"
                    class="group relative overflow-hidden rounded-3xl border border-slate-200/60 bg-white/90 p-6 shadow-md shadow-slate-900/5 ring-1 ring-slate-900/5 transition hover:-translate-y-1 hover:shadow-xl dark:border-white/10 dark:bg-slate-900/80 dark:ring-white/10"
                >
                    <div class="absolute inset-0 -z-10 opacity-0 blur-3xl transition duration-300 group-hover:opacity-100" :class="colorClass(feature.color)" />
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-white to-slate-100 text-2xl shadow-sm dark:from-white/10 dark:to-white/5">
                        {{ feature.icon }}
                    </div>
                    <h4 class="mt-4 text-xl font-bold text-slate-900 dark:text-white">{{ feature.title }}</h4>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ feature.description }}</p>
                </article>
            </div>
        </section>
    </SiteLayout>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import SiteLayout from '../Layouts/SiteLayout.vue';

const props = defineProps({
    mode: {
        type: String,
        default: 'sfw',
    },
    messages: {
        type: Object,
        default: () => ({}),
    },
    features: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Array,
        default: () => [],
    },
    guidePreview: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();
const mode = ref(props.mode ?? 'sfw');
const theme = ref(
    localStorage.getItem('rtfm_theme') ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
);

const applyTheme = (value) => {
    const html = document.documentElement;
    html.classList.toggle('dark', value === 'dark');
    localStorage.setItem('rtfm_theme', value);
};

onMounted(() => {
    applyTheme(theme.value);
});

watch(theme, (value) => applyTheme(value));
watch(
    () => page.props.mode,
    (value) => {
        mode.value = value ?? 'sfw';
    },
);

const activeCopy = computed(() => props.messages?.[mode.value] ?? props.messages?.sfw ?? {});
const guide = computed(() => ({
    title: '',
    path: '',
    updated: '',
    author: '',
    summary: '',
    steps: [],
    commands: [],
    footer: '',
    ...props.guidePreview,
}));

const form = useForm({
    email: '',
});

const subscribed = computed(() => Boolean(page.props.flash?.subscribed));
const successMessage = computed(() => page.props.flash?.success ?? null);

const submit = () => {
    form.post('/subscribe', {
        preserveScroll: true,
        onSuccess: () => form.reset('email'),
    });
};

const toggleMode = (targetMode) => {
    const nextMode = targetMode ?? (mode.value === 'sfw' ? 'nsfw' : 'sfw');

    router.post(
        '/mode',
        { mode: nextMode },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                mode.value = nextMode;
            },
        },
    );
};

const toggleTheme = () => {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
};

const copyCommands = async () => {
    if (!guide.value.commands?.length) {
        return;
    }

    await navigator.clipboard.writeText(guide.value.commands.join('\n'));
};

const colorClassMap = {
    sky: 'bg-sky-500/20',
    indigo: 'bg-indigo-500/20',
    rose: 'bg-rose-500/20',
    amber: 'bg-amber-500/20',
    emerald: 'bg-emerald-500/20',
    violet: 'bg-violet-500/20',
};

const colorClass = (tone) => colorClassMap[tone] ?? 'bg-slate-500/10';
</script>
