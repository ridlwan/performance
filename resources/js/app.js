import './bootstrap';
import "jquery";
import { createApp, h } from 'vue'
import '@fortawesome/fontawesome-free/js/all.min.js';
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createPinia } from "pinia";
import '../assets/css/nucleo-icons.css';
import '../assets/css/nucleo-svg.css';
import '../assets/css/argon-dashboard.css';
import "../assets/js/core/popper.min.js";
import "../assets/js/core/bootstrap.min.js";
import 'floating-vue/dist/style.css'
import moment from "moment";
import FloatingVue from 'floating-vue'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const options = {
    position: "top-right",
    timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: true,
    closeButton: "button",
    icon: "fas fa-rocket",
    rtl: false,
    newestOnTop: true
};

createInertiaApp({
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia())
            .use(FloatingVue)
            .use(Toast, options);

            app.config.globalProperties.$filters = {
                formatDate(value) {
                    if (value) {
                        return moment(String(value)).format("ddd DD MMM YY");
                    } else {
                        return '-';
                    }
                },
                formatTime(value) {
                    if (value) {
                        return moment(String(value)).format("HH:mm");
                    } else {
                        return '-';
                    }
                },
            }

            app.mount(el)
    },
})