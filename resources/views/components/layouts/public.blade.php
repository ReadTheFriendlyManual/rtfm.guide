@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')

        @if($title)
            <title>{{ $title }} - RTFM.guide</title>
        @else
            <title>RTFM.guide - Read The F***ing Manual (but we did it for you)</title>
        @endif
    </head>
    <body
        x-data="{
            theme: localStorage.getItem('rtfm_theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
            toggleTheme() {
                this.theme = this.theme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('rtfm_theme', this.theme);
                document.documentElement.classList.toggle('dark', this.theme === 'dark');
            }
        }"
        x-init="
            // Apply theme immediately on load
            document.documentElement.classList.toggle('dark', theme === 'dark');

            // Watch for theme changes
            $watch('theme', value => {
                document.documentElement.classList.toggle('dark', value === 'dark');
                localStorage.setItem('rtfm_theme', value);
            });

            // Listen for localStorage updates from Livewire
            Livewire.on('update-localStorage', (data) => {
                localStorage.setItem(data.key, data.value);
            });
        "
        class="min-h-screen antialiased bg-slate-50 dark:bg-slate-800"
        :class="theme === 'dark' ? 'text-white' : 'text-slate-900'"
    >
        <div class="font-sans">
            <!-- Navigation Header -->
            <x-layout.header />

            <!-- Main Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <x-layout.footer />
        </div>

        @fluxScripts
    </body>
</html>
