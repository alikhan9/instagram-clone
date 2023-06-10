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
import { uniEllipsisH,uniHeart, uniComment, uniTelegramAlt, uniBookmark } from 'vue-unicons/dist/icons'

Unicon.add([uniEllipsisH,uniHeart, uniComment, uniTelegramAlt, uniBookmark])

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        let page = pages[`./Pages/${name}.vue`]
        page.default.layout = (name =='Auth/Login' || name == 'Auth/Register') ? GuestLayout : AuthenticatedLayout
        return page
      },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(Unicon)
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
