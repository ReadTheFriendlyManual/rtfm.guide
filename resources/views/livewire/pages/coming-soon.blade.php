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
    x-init="$watch('theme', value => document.documentElement.classList.toggle('dark', value === 'dark'))"
    :class="theme === 'dark' ? 'bg-black text-white' : 'bg-white text-slate-900'"
    class="font-sans antialiased"
>
    <div>
        <main class="relative z-10 px-4 sm:px-6 lg:px-8">
            <header class="mx-auto flex max-w-7xl items-center justify-between py-8">
                <a href="/" class="text-xl font-bold text-slate-900 dark:text-white">
                    RTFM<span class="text-sky-600 dark:text-sky-400">.guide</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden items-center gap-6 md:flex">
                    <a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Guides</a>
                    <a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">Categories</a>
                    <a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">API</a>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden items-center gap-2 text-sm text-slate-500 dark:text-slate-400 sm:flex">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-sky-500"></span>
                        </span>
                        <span>In Development</span>
                    </div>

                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" class="rounded-lg p-2 text-slate-600 transition-colors hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-white/5" title="Toggle theme">
                        <svg x-show="theme === 'dark'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-show="theme === 'light'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- SFW/NSFW Toggle -->
                    <button @click="toggleMode()" class="group relative inline-flex items-center gap-2 rounded-full p-1 text-sm font-medium text-slate-500 transition-colors focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:text-slate-400 dark:focus:ring-offset-slate-900">
                        <span class="pl-2 transition-colors" :class="mode === 'sfw' ? 'text-slate-900 dark:text-white' : ''">SFW</span>
                        <div class="relative h-6 w-11 rounded-full bg-slate-200 transition-colors dark:bg-slate-700" :class="mode === 'nsfw' ? 'bg-sky-500 dark:bg-sky-500' : ''">
                            <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow-sm ring-1 ring-black/5 transition-transform duration-300" :class="mode === 'nsfw' ? 'translate-x-5' : ''"></div>
                        </div>
                        <span class="pr-2 transition-colors" :class="mode === 'nsfw' ? 'text-slate-900 dark:text-white' : ''">NSFW</span>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-sky-500">
                            Sign In
                        </a>
                    @endauth
                </div>
            </header>

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
                            <form wire:submit="subscribe" class="mx-auto flex max-w-2xl flex-col gap-4 sm:flex-row">
                                 <div class="relative flex-1">
                                    <flux:input wire:model="email" type="email" placeholder="your.email@example.com" class="w-full rounded-xl bg-slate-50 px-6 py-4 text-base text-slate-900 placeholder-slate-500 shadow-sm ring-1 ring-slate-900/5 transition-all hover:bg-white focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 dark:bg-white/10 dark:text-white dark:ring-white/20 dark:placeholder-slate-400 dark:hover:bg-white/15 dark:focus:bg-white/15 dark:focus:ring-sky-400" />
                                    @error('email') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <flux:button type="submit" class="whitespace-nowrap rounded-xl bg-sky-500 px-8 py-4 text-base font-semibold text-white shadow-lg transition-all hover:bg-sky-400 hover:shadow-xl">
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
                                <span x-show="mode === 'nsfw'" x-cloak>You Could've RTFM'd, But Here We F***ing Are</span>
                            </h2>
                            <p class="mt-6 text-balance text-xl font-medium text-slate-700 dark:text-slate-300 sm:text-2xl">
                                <span x-show="mode === 'sfw'">Friendly guides that actually make sense</span>
                                <span x-show="mode === 'nsfw'" x-cloak>Straight-talk guides for people who hate bullsh*t</span>
                            </p>
                        </div>
                        <p class="text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                            <span x-show="mode === 'sfw'">Clear, helpful documentation with a smile. We've done the reading so you can get back to building.</span>
                            <span x-show="mode === 'nsfw'" x-cloak>No corporate fluff. No ass-kissing corporate speak. Just the damn solution that actually works.</span>
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
                                    <p class="mt-2 text-sky-900 dark:text-sky-200">To gracefully restart Nginx without dropping connections, use <code class="rounded bg-sky-100 px-1.5 py-0.5 font-semibold dark:bg-sky-400/20">sudo systemctl reload nginx</code>.</p>
                                </div>

                                <div class="mt-8">
                                    <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Prerequisites</h4>
                                    <ul class="mt-4 list-disc space-y-2 pl-5 text-slate-700 dark:text-slate-200">
                                        <li>A server running Ubuntu 22.04.</li>
                                        <li>Nginx installed and configured.</li>
                                        <li>You're logged in as a user with <code class="rounded bg-slate-100 px-1.5 py-0.5 font-semibold dark:bg-white/10">sudo</code> privileges.</li>
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
                                    <p>If you really need to force a full restart (which will drop connections), you can use <code class="rounded bg-slate-100 px-1.5 py-0.5 font-semibold dark:bg-white/10">restart</code> instead of <code class="rounded bg-slate-100 px-1.5 py-0.5 font-semibold dark:bg-white/10">reload</code>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mx-auto max-w-7xl pt-24">
                     <div class="mx-auto max-w-2xl text-center">
                        <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">And So Much More...</h2>
                        <p class="mt-4 text-lg text-slate-600">A full suite of features designed to make learning and contributing as seamless as possible.</p>
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
                            <div class="group relative rounded-2xl border border-slate-200/80 bg-white/60 p-8 backdrop-blur-sm transition-all duration-300 hover:z-10 hover:-translate-y-1 hover:border-slate-300 hover:bg-white/80 hover:shadow-2xl hover:shadow-slate-600/10">
                                <div class="relative">
                                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-{{ $feature['color'] }}-100 text-2xl">
                                        <span class="transform transition-transform duration-300 group-hover:scale-110">{{ $feature['icon'] }}</span>
                                    </div>
                                    <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ $feature['title'] }}</h3>
                                    <p class="text-sm leading-relaxed text-slate-600">{{ $feature['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="border-y border-slate-200/80 bg-white/60 py-20 backdrop-blur-sm">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-y-12 text-center sm:grid-cols-3">
                        <div x-data="{ target: 50, current: 0, start() { let interval = setInterval(() => { if (this.current < this.target) { this.current++ } else { clearInterval(interval) } }, 40); } }" x-intersect.once="start()">
                            <div class="text-5xl font-bold tracking-tighter text-sky-600"><span x-text="current"></span>+</div>
                            <p class="mt-2 text-sm text-slate-500">Guides at Launch</p>
                        </div>
                        <div x-data="{ target: 50, current: 150, start() { let interval = setInterval(() => { if (this.current > this.target) { this.current -= 2 } else { this.current = this.target; clearInterval(interval) } }, 20); } }" x-intersect.once="start()">
                            <div class="text-5xl font-bold tracking-tighter text-indigo-600">&lt;<span x-text="current"></span>ms</div>
                            <p class="mt-2 text-sm text-slate-500">Search Response Time</p>
                        </div>
                        <div x-data="{ target: 100, current: 0, start() { let interval = setInterval(() => { if (this.current < this.target) { this.current++ } else { clearInterval(interval) } }, 20); } }" x-intersect.once="start()">
                            <div class="text-5xl font-bold tracking-tighter text-rose-600"><span x-text="current"></span>%</div>
                            <p class="mt-2 text-sm text-slate-500">Free & Open Source</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-200/80 bg-slate-100/50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="space-y-4">
                        <h4 class="font-semibold text-slate-800">Explore</h4>
                        <ul class="space-y-2 text-slate-600">
                            <li><a href="#" class="hover:text-sky-600">Trending Guides</a></li>
                            <li><a href="#" class="hover:text-sky-600">New Guides</a></li>
                            <li><a href="#" class="hover:text-sky-600">Random Guide</a></li>
                            <li><a href="#" class="hover:text-sky-600">Tag Cloud</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-semibold text-slate-800">Categories</h4>
                        <ul class="space-y-2 text-slate-600">
                            <li><a href="#" class="hover:text-sky-600">Server Admin</a></li>
                            <li><a href="#" class="hover:text-sky-600">Laravel Dev</a></li>
                            <li><a href="#" class="hover:text-sky-600">Git Workflows</a></li>
                            <li><a href="#" class="hover:text-sky-600">Docker</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-semibold text-slate-800">Community</h4>
                        <ul class="space-y-2 text-slate-600">
                            <li><a href="#" class="hover:text-sky-600">Submit a Guide</a></li>
                            <li><a href="#" class="hover:text-sky-600">Leaderboard</a></li>
                            <li><a href="#" class="hover:text-sky-600">Changelog</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-semibold text-slate-800">RTFM.GUIDE</h4>
                        <ul class="space-y-2 text-slate-600">
                            <li><a href="#" class="hover:text-sky-600">About</a></li>
                            <li><a href="#" class="hover:text-sky-600">Contact</a></li>
                            <li><a href="#" class="hover:text-sky-600">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-16 border-t border-slate-200/80 pt-8 text-center text-sm text-slate-500">
                    © {{ date('Y') }} RTFM.GUIDE. All rights reserved, except for the right to complain about bad docs.
                </div>
            </div>
        </footer>

        <style>
            /* Define Tailwind JIT-safe classes for dynamic colors */
            .bg-sky-100 { background-color: #e0f2fe; }
            .bg-indigo-100 { background-color: #e0e7ff; }
            .bg-rose-100 { background-color: #ffe4e6; }
            .bg-amber-100 { background-color: #fef3c7; }
            .bg-emerald-100 { background-color: #d1fae5; }
            .bg-violet-100 { background-color: #ede9fe; }

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
