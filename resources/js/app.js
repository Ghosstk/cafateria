require('./bootstrap');

import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faSave, faExclamationTriangle, faFileCsv, faCheckCircle, faTimes} from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(
    faSave,
    faExclamationTriangle,
    faFileCsv,
    faCheckCircle,
    faTimes
);

const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .component('font-awesome-icon', FontAwesomeIcon)
    .mount(el);

InertiaProgress.init({ color: '#5797ec' });
