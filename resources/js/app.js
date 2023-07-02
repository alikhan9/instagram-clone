import './bootstrap';
import '../css/app.css';
import 'vue3-emoji-picker/css'

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import AuthenticatedLayout from './Layouts/AuthenticatedLayout.vue'
import GuestLayout from './Layouts/GuestLayout.vue'
import Unicon from 'vue-unicons'
import { MotionPlugin } from '@vueuse/motion'
import { autoAnimatePlugin } from '@formkit/auto-animate/vue'
import { uniEllipsisH, uniHeart, uniComment, uniTelegramAlt, uniBookmark } from 'vue-unicons/dist/icons'
import VueLazyload from 'vue-lazyload'
import { createPinia } from 'pinia';
import VScrollLock from 'v-scroll-lock'

const pinia = createPinia()
Unicon.add([uniEllipsisH, uniHeart, uniComment, uniTelegramAlt, uniBookmark])

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        let page = pages[`./Pages/${name}.vue`]
        page.default.layout = (name == 'Auth/Login' || name == 'Auth/Register') ? GuestLayout : AuthenticatedLayout
        return page
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VScrollLock)
            .use(Unicon)
            .use(pinia)
            .use(VueLazyload, {
                preLoad: 1.3,
                // error: errorimage,
                // loading: loadimage,
                attempt: 1
            })
            .use(autoAnimatePlugin)
            .use(MotionPlugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});