/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/** Notification for loss of connection. */
window.addEventListener('offline', (event) => {
    window.Snotify.info("The network connection has been lost.")
});

/**
 * The following is where we load any Vue components we need.
 */
import Snotify, { SnotifyPosition } from 'vue-snotify'
let _options = {
    toast: {
        timeout: 300000,
        showProgressBar: false,
        position: SnotifyPosition.rightTop
    }
};
window.Vue.use(Snotify, _options);

import VueClipboards from 'vue-clipboards';
Vue.use(VueClipboards);

Vue.component('cookielaw', require('./components/cookie-law.vue').default);
Vue.component('session', require('./components/session.vue').default);
Vue.component('modal', require('./components/modal.vue').default);
Vue.component('pending-modal', require('./components/pending-modal.vue').default);
Vue.component('copy-button', require('./components/copy-button.vue').default);
Vue.component('api-tokens', require('./components/api-tokens.vue').default);
Vue.component('api-token-create', require('./components/api-token-create.vue').default);
Vue.component('notification-badge', require('./components/notification-badge.vue').default);
Vue.component('notification-marker', require('./components/notification-marker.vue').default);

/**
 * The following component is a shared event system
 * this means you can trigger events in one component
 * and listen to them in another.
 */

Vue.prototype.$event = new Vue({
    methods: {
        fire (event, data = null) {
            this.$emit(event, data);
        },
        listen (event, callback) {
            this.$on(event, callback);
        }
    }
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

require("./subscription-create");
require("./subscription-payment-method");
require("./ajax-forms");
require('./sidebar');
