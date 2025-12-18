<div
    x-data="{
        mode: localStorage.getItem('rtfm_mode') || 'sfw',
        theme: localStorage.getItem('rtfm_theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
        messages: @js($this->messages),
        toggleMode() {
            this.mode = this.mode === 'sfw' ? 'nsfw' : 'sfw';
            localStorage.setItem('rtfm_mode', this.mode);
        },
        toggleTheme() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('rtfm_theme', this.theme);
        }
    }"
    x-init="
        document.documentElement.classList.toggle('dark', theme === 'dark');
        $watch('theme', value => document.documentElement.classList.toggle('dark', value === 'dark'))
    "
    :class="theme === 'dark' ? 'bg-black text-white' : 'bg-white text-slate-900'"
    class="font-sans antialiased"
>
    <div>
        <main class="relative min-h-screen flex flex-col">

            <section class="flex min-h-[calc(80vh)] items-center">
                <div class="mx-auto w-full max-w-5xl px-6 text-center">
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)" x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        <h1 class="text-6xl font-extrabold tracking-tighter text-slate-900 dark:text-white sm:text-7xl md:text-8xl" x-text="messages[mode].hero"></h1>
                        <p class="mx-auto max-w-3xl text-balance text-xl text-slate-700 dark:text-slate-300 sm:text-2xl" x-html="messages[mode].tagline"></p>
                    </div>
                    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 600)" x-show="show" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mt-16">
                         @if($subscribed)
                            <div class="inline-flex animate-fade-in-up items-center gap-3 rounded-lg bg-emerald-500/10 px-6 py-3 text-emerald-400 ring-1 ring-inset ring-emerald-500/20">
                                <svg class="h-6 w-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span class="font-medium">You're on the list! We'll be in touch.</span>
                            </div>
                        @else
                            <form wire:submit="subscribe" class="mx-auto flex max-w-2xl items-center gap-3">
                                 <div class="relative flex-1">
                                    <flux:input wire:model="email" type="email" placeholder="your.email@example.com" class="w-full rounded-xl bg-slate-50 px-6 py-3.5 text-base text-slate-900 placeholder-slate-500 shadow-sm ring-1 ring-slate-900/5 transition-all hover:bg-white focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 dark:bg-white/10 dark:text-white dark:ring-white/20 dark:placeholder-slate-400 dark:hover:bg-white/15 dark:focus:bg-white/15 dark:focus:ring-sky-400" />
                                    @error('email') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <flux:button type="submit" class="h-[52px] whitespace-nowrap rounded-xl bg-gradient-to-r from-sky-500 to-sky-600 px-8 text-base font-semibold text-white shadow-lg shadow-sky-500/30 transition-all hover:scale-105 hover:shadow-xl hover:shadow-sky-500/40">
                                    Get Early Access
                                </flux:button>
                            </form>
                            <p class="mt-6 text-sm text-slate-600 dark:text-slate-400">No spam, just launch updates.</p>
                        @endif
                    </div>
                </div>
            </section>

            <!-- Sneak Peek Section -->
            <section class="border-t border-slate-200 py-12 dark:border-white/10">
                <div class="mx-auto max-w-7xl">
                    <div class="mx-auto max-w-3xl text-center">
                        <div class="mb-12">
                            <h2 class="text-5xl font-extrabold tracking-tighter text-slate-900 dark:text-white sm:text-6xl md:text-7xl">
                                <span x-show="mode === 'sfw'">We Read The Manual So You Don't Have To</span>
                                <span x-show="mode === 'nsfw'" x-cloak>You Should've RTFM'd, But You're Too Lazy So Here's The F***ing Answer</span>
                            </h2>
                            <p class="mt-6 text-balance text-xl font-medium text-slate-700 dark:text-slate-300 sm:text-2xl">
                                <span x-show="mode === 'sfw'">Friendly guides that actually make sense</span>
                                <span x-show="mode === 'nsfw'" x-cloak>Brutally honest guides for developers who don't have time for corporate bullsh*t</span>
                            </p>
                        </div>
                        <p class="text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                            <span x-show="mode === 'sfw'">Clear, helpful documentation with a smile. We've done the reading so you can get back to building.</span>
                            <span x-show="mode === 'nsfw'" x-cloak>Zero fluff. Zero hand-holding. Just the f***ing solution that works, because we know you've already wasted 3 hours Googling this sh*t.</span>
                        </p>
                    </div>
                    <div class="mt-16 flow-root">
                        <div class="relative -m-2 rounded-xl bg-slate-50 p-2 ring-1 ring-inset ring-slate-900/5 dark:bg-zinc-900 dark:ring-white/10 lg:-m-4 lg:rounded-2xl lg:p-4">
                            <div class="relative rounded-xl bg-white p-4 shadow-xl ring-1 ring-slate-900/5 dark:bg-zinc-950 dark:ring-white/10 sm:p-8 lg:p-12">
                                <div class="mb-8">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-slate-500 dark:text-slate-400">/server/nginx/</span>
                                    </div>
                                    <h3 class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">How to Restart Nginx</h3>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Last updated: 3 days ago by <span class="font-medium text-slate-800 dark:text-slate-300">Pete</span></p>
                                </div>

                                <div class="rounded-lg border border-sky-200 bg-sky-50 p-4 dark:border-sky-500/30 dark:bg-sky-500/10">
                                    <h4 class="flex items-center gap-2 text-sm font-semibold text-sky-900 dark:text-sky-300">
                                        <span class="text-lg">💡</span>
                                        <span>Quick Answer (TL;DR)</span>
                                    </h4>
                                    <p class="mt-2 text-sky-900 dark:text-sky-200">To gracefully restart Nginx without dropping connections, use <code class="rounded bg-sky-100 px-2 py-1 font-semibold text-sky-900 dark:bg-sky-900 dark:text-sky-100">sudo systemctl reload nginx</code>.</p>
                                </div>

                                <div class="mt-8">
                                    <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Prerequisites</h4>
                                    <ul class="mt-4 list-disc space-y-2 pl-5 text-slate-700 dark:text-slate-200">
                                        <li>A server running Ubuntu 22.04.</li>
                                        <li>Nginx installed and configured.</li>
                                        <li>You're logged in as a user with <code class="rounded bg-slate-100 px-2 py-1 font-semibold text-slate-900 dark:bg-slate-700 dark:text-slate-100">sudo</code> privileges.</li>
                                    </ul>
                                </div>

                                <div class="mt-8 max-w-none text-slate-700 dark:text-slate-200">
                                    <p>So, you've tweaked your Nginx config and now you need to apply the changes. You could just pull the plug, but let's be civilized. Here's how to do it without making your users angry.</p>
                                </div>

                                <div class="syntax-highlight mt-6">
                                    <div class="flex items-center justify-between rounded-t-lg bg-slate-700 px-4 py-2">
                                        <span class="text-xs font-medium uppercase text-slate-400">bash</span>
                                        <button class="flex items-center gap-1 text-xs font-medium text-slate-300 hover:text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                            <span>Copy</span>
                                        </button>
                                    </div>
                                    <pre class="!m-0 !rounded-none !rounded-b-lg !bg-slate-800"><code class="language-bash"><span class="token comment"># Check your config for syntax errors first!</span>
<span class="token keyword">sudo</span> nginx -t

<span class="token comment"># If all is well, gracefully reload the service.</span>
<span class="token comment"># This finishes active connections before reloading.</span>
<span class="token keyword">sudo</span> systemctl reload nginx</code></pre>
                                </div>

                                <div class="mt-6 max-w-none text-slate-700 dark:text-slate-200">
                                    <p>If you really need to force a full restart (which will drop connections), you can use <code class="rounded bg-slate-100 px-2 py-1 font-semibold text-slate-900 dark:bg-slate-700 dark:text-slate-100">restart</code> instead of <code class="rounded bg-slate-100 px-2 py-1 font-semibold text-slate-900 dark:bg-slate-700 dark:text-slate-100">reload</code>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mx-auto max-w-7xl pt-24">
                     <div class="mx-auto max-w-2xl text-center">
                        <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-4xl">And So Much More...</h2>
                        <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">A full suite of features designed to make learning and contributing as seamless as possible.</p>
                    </div>
                    <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        @php
                            $features = [
                                ['icon' => '⚡️', 'title' => 'Blazing Fast Search', 'description' => 'Find what you need in milliseconds. Our Meilisearch engine is so fast, you\'ll think it\'s magic.', 'color' => 'sky'],
                                ['icon' => '👥', 'title' => 'Community Driven', 'description' => 'Submit guides, suggest edits, and earn reputation. This platform is built by you, for you.', 'color' => 'indigo'],
                                ['icon' => '📚', 'title' => 'Versioned Content', 'description' => 'Laravel 10 or 11? Ubuntu 22.04 or 24.04? Get the right answer for your specific stack.', 'color' => 'rose'],
                                ['icon' => '🏆', 'title' => 'Gamified Learning', 'description' => 'Unlock badges, climb leaderboards, and prove you\'re the master of your craft. Learning should be fun.', 'color' => 'amber'],
                                ['icon' => '🔌', 'title' => 'API First', 'description' => 'Integrate with anything. Build your own tools on top of our comprehensive and public REST API.', 'color' => 'emerald'],
                                ['icon' => '🌍', 'title' => 'Multi-Language', 'description' => 'From English to Español, contribute and read guides in the language you\'re most comfortable with.', 'color' => 'violet'],
                            ];
                        @endphp

                        @foreach($features as $index => $feature)
                            <div class="group relative rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-all duration-300 hover:z-10 hover:-translate-y-1 hover:border-{{ $feature['color'] }}-400 hover:shadow-xl hover:shadow-{{ $feature['color'] }}-500/20 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:border-{{ $feature['color'] }}-500 dark:hover:shadow-{{ $feature['color'] }}-500/30">
                                <div class="relative">
                                    <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-{{ $feature['color'] }}-400 to-{{ $feature['color'] }}-600 text-2xl shadow-lg shadow-{{ $feature['color'] }}-500/30">
                                        <span class="transform transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6">{{ $feature['icon'] }}</span>
                                    </div>
                                    <h3 class="mb-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $feature['title'] }}</h3>
                                    <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ $feature['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="border-y border-slate-200 bg-gradient-to-b from-white to-slate-50 py-20 dark:border-slate-700 dark:from-slate-900 dark:to-slate-800">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-y-12 text-center sm:grid-cols-3">
                        <div x-data="{ target: 50, current: 0, start() { let interval = setInterval(() => { if (this.current < this.target) { this.current++ } else { clearInterval(interval) } }, 40); } }" x-intersect.once="start()">
                            <div class="text-5xl font-bold tracking-tighter text-sky-600 dark:text-sky-400"><span x-text="current"></span>+</div>
                            <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">Guides at Launch</p>
                        </div>
                        <div x-data="{ target: 50, current: 150, start() { let interval = setInterval(() => { if (this.current > this.target) { this.current -= 2 } else { this.current = this.target; clearInterval(interval) } }, 20); } }" x-intersect.once="start()">
                            <div class="text-5xl font-bold tracking-tighter text-indigo-600 dark:text-indigo-400">&lt;<span x-text="current"></span>ms</div>
                            <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">Search Response Time</p>
                        </div>
                        <div x-data="{ target: 100, current: 0, start() { let interval = setInterval(() => { if (this.current < this.target) { this.current++ } else { clearInterval(interval) } }, 20); } }" x-intersect.once="start()">
                            <div class="text-5xl font-bold tracking-tighter text-rose-600 dark:text-rose-400"><span x-text="current"></span>%</div>
                            <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">Free & Open Source</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

        <style>
            /* Define Tailwind JIT-safe classes for dynamic colors */
            /* Gradient backgrounds */
            .from-sky-400 { --tw-gradient-from: #38bdf8; }
            .to-sky-600 { --tw-gradient-to: #0284c7; }
            .from-indigo-400 { --tw-gradient-from: #818cf8; }
            .to-indigo-600 { --tw-gradient-to: #4f46e5; }
            .from-rose-400 { --tw-gradient-from: #fb7185; }
            .to-rose-600 { --tw-gradient-to: #e11d48; }
            .from-amber-400 { --tw-gradient-from: #fbbf24; }
            .to-amber-600 { --tw-gradient-to: #d97706; }
            .from-emerald-400 { --tw-gradient-from: #34d399; }
            .to-emerald-600 { --tw-gradient-to: #059669; }
            .from-violet-400 { --tw-gradient-from: #a78bfa; }
            .to-violet-600 { --tw-gradient-to: #7c3aed; }

            /* Hover borders */
            .hover\:border-sky-400:hover { border-color: #38bdf8; }
            .hover\:border-indigo-400:hover { border-color: #818cf8; }
            .hover\:border-rose-400:hover { border-color: #fb7185; }
            .hover\:border-amber-400:hover { border-color: #fbbf24; }
            .hover\:border-emerald-400:hover { border-color: #34d399; }
            .hover\:border-violet-400:hover { border-color: #a78bfa; }

            /* Dark mode hover borders */
            .dark .dark\:hover\:border-sky-500:hover { border-color: #0ea5e9; }
            .dark .dark\:hover\:border-indigo-500:hover { border-color: #6366f1; }
            .dark .dark\:hover\:border-rose-500:hover { border-color: #f43f5e; }
            .dark .dark\:hover\:border-amber-500:hover { border-color: #f59e0b; }
            .dark .dark\:hover\:border-emerald-500:hover { border-color: #10b981; }
            .dark .dark\:hover\:border-violet-500:hover { border-color: #8b5cf6; }

            /* Shadows */
            .shadow-sky-500\/30 { --tw-shadow-color: rgb(14 165 233 / 0.3); box-shadow: var(--tw-shadow); }
            .shadow-indigo-500\/30 { --tw-shadow-color: rgb(99 102 241 / 0.3); box-shadow: var(--tw-shadow); }
            .shadow-rose-500\/30 { --tw-shadow-color: rgb(244 63 94 / 0.3); box-shadow: var(--tw-shadow); }
            .shadow-amber-500\/30 { --tw-shadow-color: rgb(245 158 11 / 0.3); box-shadow: var(--tw-shadow); }
            .shadow-emerald-500\/30 { --tw-shadow-color: rgb(16 185 129 / 0.3); box-shadow: var(--tw-shadow); }
            .shadow-violet-500\/30 { --tw-shadow-color: rgb(139 92 246 / 0.3); box-shadow: var(--tw-shadow); }

            /* Hover shadows */
            .hover\:shadow-sky-500\/20:hover { --tw-shadow-color: rgb(14 165 233 / 0.2); }
            .hover\:shadow-indigo-500\/20:hover { --tw-shadow-color: rgb(99 102 241 / 0.2); }
            .hover\:shadow-rose-500\/20:hover { --tw-shadow-color: rgb(244 63 94 / 0.2); }
            .hover\:shadow-amber-500\/20:hover { --tw-shadow-color: rgb(245 158 11 / 0.2); }
            .hover\:shadow-emerald-500\/20:hover { --tw-shadow-color: rgb(16 185 129 / 0.2); }
            .hover\:shadow-violet-500\/20:hover { --tw-shadow-color: rgb(139 92 246 / 0.2); }

            /* Dark mode hover shadows */
            .dark .dark\:hover\:shadow-sky-500\/30:hover { --tw-shadow-color: rgb(14 165 233 / 0.3); }
            .dark .dark\:hover\:shadow-indigo-500\/30:hover { --tw-shadow-color: rgb(99 102 241 / 0.3); }
            .dark .dark\:hover\:shadow-rose-500\/30:hover { --tw-shadow-color: rgb(244 63 94 / 0.3); }
            .dark .dark\:hover\:shadow-amber-500\/30:hover { --tw-shadow-color: rgb(245 158 11 / 0.3); }
            .dark .dark\:hover\:shadow-emerald-500\/30:hover { --tw-shadow-color: rgb(16 185 129 / 0.3); }
            .dark .dark\:hover\:shadow-violet-500\/30:hover { --tw-shadow-color: rgb(139 92 246 / 0.3); }

            /* Polished Syntax Highlighting */
            .prose code:not(pre code) {
                color: #be185d; /* pink-700 */
                font-weight: 600;
                background-color: #fce7f3; /* pink-50 */
                padding: 0.125rem 0.25rem;
                border-radius: 0.25rem;
            }
            .syntax-highlight pre {
                padding: 0 !important;
                margin: 0 !important;
                background-color: transparent !important;
                border: none !important;
            }
            .syntax-highlight code {
                display: block;
                padding: 1.25rem 1.5rem;
                font-family: 'JetBrains Mono', monospace;
                font-size: 0.875rem;
                line-height: 1.7;
                color: #e2e8f0; /* slate-200 */
            }
            .syntax-highlight .token.comment,
            .syntax-highlight .token.prolog,
            .syntax-highlight .token.doctype,
            .syntax-highlight .token.cdata {
                color: #64748b; /* slate-500 */
            }
            .syntax-highlight .token.punctuation {
                color: #94a3b8; /* slate-400 */
            }
            .syntax-highlight .token.property,
            .syntax-highlight .token.tag,
            .syntax_highlight .token.boolean,
            .syntax-highlight .token.number,
            .syntax-highlight .token.constant,
            .syntax-highlight .token.symbol,
            .syntax-highlight .token.deleted {
                color: #ec4899; /* pink-500 */
            }
            .syntax-highlight .token.selector,
            .syntax-highlight .token.attr-name,
            .syntax-highlight .token.string,
            .syntax-highlight .token.char,
            .syntax-highlight .token.builtin,
            .syntax-highlight .token.inserted {
                color: #22c55e; /* green-500 */
            }
            .syntax-highlight .token.operator,
            .syntax-highlight .token.entity,
            .syntax-highlight .token.url,
            .language-css .syntax-highlight .token.string,
            .style .syntax-highlight .token.string {
                color: #6366f1; /* indigo-500 */
            }
            .syntax-highlight .token.atrule,
            .syntax-highlight .token.attr-value,
            .syntax-highlight .token.keyword {
                color: #8b5cf6; /* violet-500 */
            }
            .syntax-highlight .token.function,
            .syntax-highlight .token.class-name {
                color: #3b82f6; /* blue-500 */
            }
            .syntax-highlight .token.regex,
            .syntax-highlight .token.important,
            .syntax-highlight .token.variable {
                color: #f59e0b; /* amber-500 */
            }
        </style>
    </div>
</div>
