<div
    x-data="{
        init() {
            // Initialize from localStorage
            const mode = localStorage.getItem('rtfm_mode') || 'sfw';
            const isNsfw = mode === 'nsfw';
            $wire.setInitialMode(isNsfw);
        }
    }"
    x-init="init()"
>
    <button
        wire:click="toggleMode"
        class="group relative inline-flex items-center gap-2 rounded-full p-1 text-sm font-medium text-slate-500 transition-colors focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:text-slate-400 dark:focus:ring-offset-slate-900"
    >
        <span class="pl-2 transition-colors" :class="$wire.isNsfwMode ? 'text-slate-400' : 'text-slate-900 dark:text-white'">SFW</span>
        <div class="relative h-6 w-11 rounded-full bg-slate-200 transition-colors dark:bg-slate-700" :class="$wire.isNsfwMode ? 'bg-sky-500 dark:bg-sky-500' : ''">
            <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow-sm ring-1 ring-black/5 transition-transform duration-300" :class="$wire.isNsfwMode ? 'translate-x-5' : ''"></div>
        </div>
        <span class="pr-2 transition-colors" :class="$wire.isNsfwMode ? 'text-slate-900 dark:text-white' : 'text-slate-400'">NSFW</span>
    </button>
</div>
