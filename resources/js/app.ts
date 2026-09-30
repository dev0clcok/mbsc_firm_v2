import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent, Plugin } from 'vue';
import { createApp, createSSRApp, h } from 'vue';

const appName = import.meta.env.VITE_APP_NAME || 'MBSC Firm';

// The public site needs neither translations nor the admin theme switcher.
// Those are loaded only when the first page is an admin, auth or settings
// page, which keeps them out of what visitors download. Public pages link
// to the admin with plain <a> tags, so this choice holds for the session.
const PUBLIC_PAGES = ['Welcome', 'Services', 'Service', 'About', 'Contact', 'Error'];

const initialPage = JSON.parse(document.getElementById('app')?.dataset.page ?? '{}');
const isPublic = PUBLIC_PAGES.includes(initialPage.component);

const plugins: Plugin[] = [];

if (!isPublic) {
    const [{ i18n, setAppLocale }, { initializeTheme }] = await Promise.all([
        import('./i18n'),
        import('./composables/useAppearance'),
    ]);

    plugins.push(i18n);

    // This will set light / dark mode on page load...
    initializeTheme();

    // Ensure <html lang=".."> matches the active locale.
    setAppLocale((i18n.global as any).locale.value ?? 'en');
}

createInertiaApp({
    title: (title) => (title ? `${title} | ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        // Hydrate server-rendered markup when it is there; otherwise mount fresh.
        const create = el.hasChildNodes() ? createSSRApp : createApp;
        const app = create({ render: () => h(App, props) }).use(plugin);

        plugins.forEach((p) => app.use(p));
        app.mount(el);
    },
    progress: {
        color: '#c2154f',
    },
});
