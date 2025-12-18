<footer class="border-t border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-800 mt-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <h4 class="font-semibold text-slate-900 dark:text-white">Explore</h4>
                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                    <li><a href="{{ route('guides.index') }}" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">All Guides</a></li>
                    <li><a href="{{ route('search.index') }}" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">Search</a></li>
                    <li><a href="{{ route('categories.index') }}" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">Categories</a></li>
                </ul>
            </div>
            <div class="space-y-4">
                <h4 class="font-semibold text-slate-900 dark:text-white">Community</h4>
                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                    <li><a href="#" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">Contribute</a></li>
                    <li><a href="#" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">Leaderboard</a></li>
                    <li><a href="#" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">GitHub</a></li>
                </ul>
            </div>
            <div class="space-y-4">
                <h4 class="font-semibold text-slate-900 dark:text-white">Company</h4>
                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                    <li><a href="#" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">About</a></li>
                    <li><a href="#" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">Blog</a></li>
                    <li><a href="#" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">Contact</a></li>
                </ul>
            </div>
            <div class="space-y-4">
                <h4 class="font-semibold text-slate-900 dark:text-white">RTFM.guide</h4>
                <ul class="space-y-2 text-slate-600 dark:text-slate-400">
                    <li><a href="#" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">Privacy</a></li>
                    <li><a href="#" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">Terms</a></li>
                    <li><a href="#" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">Status</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-12 border-t border-slate-200 pt-8 text-center text-sm text-slate-500 dark:border-slate-800 dark:text-slate-400">
            © {{ date('Y') }} RTFM.guide. All rights reserved, except for the right to complain about bad docs.
        </div>
    </div>
</footer>