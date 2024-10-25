import NProgress from 'nprogress'
import { createInertiaApp } from '@inertiajs/svelte'
import {InertiaProgress} from '@inertiajs/progress';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';

const route = window.route;
import { setupI18n } from './i18n';

import './bootstrap';
import '../scss/app.scss';

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
import 'bootstrap-icons/font/bootstrap-icons.css';

///import './color-mode';
//import './sidebar';
import { addSpinner, removeSpinner } from './loading';
import { showToast, handleCallbackMessages } from './Scripts/toasts';

// document.querySelectorAll('[data-bs-toggle="collapse"]').forEach((toggle) => {
//     toggle.addEventListener('click', function () {
//         const icons = this.querySelectorAll('i');
//         icons.forEach((icon) => icon.classList.toggle('d-none'));
//     });
// });



// Import the route function from ziggy-js
// import route from 'ziggy-js';

// // Make the route function globally available
// window.route = route;

const appName = import.meta.env.VITE_APP_NAME || 'Yatodo';

setupI18n().then(() => {
    createInertiaApp({
        resolve: name => {
            const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true })
            return pages[`./Pages/${name}.svelte`]
        },
        setup({ el, App, props }) {
            new App({ target: el, props })
        },
        progress:false,
        // progress: {
        //     // The delay after which the progress bar will appear, in milliseconds...
        //     delay: 250,
        
        //     // The color of the progress bar...
        //     color: '#29d',
        
        //     // Whether to include the default NProgress styles...
        //     includeCSS: true,
        
        //     // Whether the NProgress spinner will be shown...
        //     showSpinner: true,
        // },
    })
});