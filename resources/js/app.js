import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';

// Try to import ZiggyVue if available, otherwise continue without it
let ZiggyVue = null;
try {
    ZiggyVue = require('../../vendor/tightenco/ziggy').ZiggyVue;
} catch (e) {
    console.warn('ZiggyVue not available, routes will be handled differently');
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin);
        
        if (ZiggyVue) {
            app.use(ZiggyVue);
        }
        
        return app.mount(el);
    },
    progress: {
        color: '#8b7355', // Updated to match ink-earth color
    },
});
