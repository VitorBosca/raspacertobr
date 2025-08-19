import './bootstrap';

import { createApp, markRaw } from 'vue/dist/vue.esm-bundler';
import { createRouter, createWebHistory } from 'vue-router';
import { createPinia } from 'pinia';
import { i18nVue } from 'laravel-vue-i18n';
import { vMaska } from "maska";

/**
 * LIBS
 */
import moment from 'moment';
import "vue-toastification/dist/index.css";
import '@/index.css';

import App from './App.vue';
import {useAuthStore} from "@/Stores/Auth.js";


/**
 * APP
 */
const app = createApp(App);

app.config.globalProperties.state = {
    platformName() {
        return 'FC';
    },
    dateFormatServer(date) {
        const currentDate = new Date(date);
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`; // Remove hífens do final
    },
    formatDate(dateTimeString) {
        const date = new Date(dateTimeString);
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        const dayAfterTomorrow = new Date(today);
        dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 2);

        const options = { hour: '2-digit', minute: '2-digit' };

        if (date.toDateString() === today.toDateString()) {
            return `Hoje às ${date.toLocaleTimeString(document.documentElement.getAttribute("lang"), options)}`;
        } else if (date.toDateString() === tomorrow.toDateString()) {
            return `Amanhã às ${date.toLocaleTimeString(document.documentElement.getAttribute("lang"), options)}`;
        } else if (date.toDateString() === dayAfterTomorrow.toDateString()) {
            const dayOfWeek = new Intl.DateTimeFormat(document.documentElement.getAttribute("lang"), { weekday: 'long' }).format(date);
            return `Na ${dayOfWeek} às ${date.toLocaleTimeString(document.documentElement.getAttribute("lang"), options)}`;
        } else {
            return `${date.toLocaleDateString(document.documentElement.getAttribute("lang"))} às ${date.toLocaleTimeString(document.documentElement.getAttribute("lang"), options)}`;
        }
    },
    generateSlug(text) {
        return text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-') // Substitui espaços por hífen
            .replace(/[^\w\-]+/g, '') // Remove caracteres especiais
            .replace(/\-\-+/g, '-') // Substitui múltiplos hífens por um único hífen
            .replace(/^-+/, '') // Remove hífens do início
            .replace(/-+$/, ''); // Remove hífens do final
    },
    timeAgo(value) {
        return moment(value).fromNow();
    },
    currencyFormat(value, currency) {
        if (value === undefined || currency === undefined) {
            currency = 'USD';
        }

        const options = {
            style: 'currency',
            currency: currency
        };

        return value.toLocaleString(document.documentElement.getAttribute("lang"), options);
    },
    currencyUSD(value) {
        if (typeof value === 'number') {
            return new Intl.NumberFormat('en-US',{
                currency: 'USD',
                minimumFractionDigits: 2,
            }).format(value);
        }else{
            return new Intl.NumberFormat('en-US',{
                currency: 'USD',
                minimumFractionDigits: 2,
            }).format(parseFloat(value));
        }
    },
    limitCharacters(value, limite) {
        if (!value) return '';
        if (value.length <= limite) return value;
        return value.slice(0, limite) + '...';
    },
};

/**
 * Axios
 */
import axios from 'axios'
import VueAxios from 'vue-axios'


app.use(VueAxios, axios)
app.provide('axios', app.config.globalProperties.axios)  // provide 'axios'

import VueFullscreen from 'vue-fullscreen'
app.use(VueFullscreen)

/**
 * Toast
 */
import Toast, { TYPE } from "vue-toastification";
import { ToastHelper } from '@/Utils/ToastHelper.js';
import { ToastMixin } from '@/Mixins/ToastMixin.js';

const optionsToast = {
    // Design e aparência
    position: "top-right",
    timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false,
    
    transition: "Vue-Toastification__bounce",
    maxToasts: 5,
    newestOnTop: true,
    
    toastClassName: "custom-toast",
    bodyClassName: ["custom-toast-body"],
    
    toastDefaults: {
        [TYPE.SUCCESS]: {
            timeout: 4000,
            icon: "🎉",
            className: "toast-success"
        },
        [TYPE.ERROR]: {
            timeout: 6000,
            icon: "❌",
            className: "toast-error"
        },
        [TYPE.WARNING]: {
            timeout: 5000,
            icon: "⚠️",
            className: "toast-warning"
        },
        [TYPE.INFO]: {
            timeout: 4000,
            icon: "ℹ️",
            className: "toast-info"
        }
    }
}
app.use(Toast, optionsToast)

app.mixin(ToastMixin);

/**
 * Router
 */
import router from './Router';
import {useSettingStore} from "@/Stores/SettingStore.js";
app.use(router);

/// Directive
app.directive("maska", vMaska)

/**
 * PINIA
 * @type {Pinia}
 */
const pinia = createPinia()
pinia.use(({ store }) => { store.router = markRaw(router)})
app.use(pinia);

app.use(i18nVue, {
    resolve: async lang => {
        const langs = import.meta.glob('../../lang/*.json');
        return await langs[`../../lang/${lang}.json`]();
    }
});

(async () => {
    const setting = useSettingStore();
    try {
        const settingData = await setting.getSetting();
        setting.setSetting(settingData);
    } catch (e) {

    }
})()

if(localStorage.getItem('token')) {
    (async () => {
        const auth = useAuthStore();
        try {
            auth.setIsAuth(true);
            const user = await auth.checkToken();
            if(user !== undefined) {
                auth.setUser(user);
            }

            //auth.initializingEcho();
        } catch (e) {
            auth.setIsAuth(false);
        }
    })()
}

app.mount('#root');
