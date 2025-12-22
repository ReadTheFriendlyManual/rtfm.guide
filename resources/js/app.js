import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress';

createInertiaApp({
    title: (title) => (title ? `${title} - RTFM.guide` : 'RTFM.guide'),
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});

router.on('navigate', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

InertiaProgress.init({
    color: '#38bdf8',
    showSpinner: false,
});
