import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
// import { ZiggyVue } from "ziggy-js";
import { ZiggyVue } from "../../vendor/tightenco/ziggy"; // Change this path

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin, page }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
});
