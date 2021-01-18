import Vue from 'vue'
import VueClipboards from 'vue-clipboards';
import Snotify, { SnotifyPosition } from 'vue-snotify';

require('./bootstrap');

let _options = {
    toast: {
        timeout: 4000,
        showProgressBar: false,
        position: SnotifyPosition.rightTop
    }
};

Vue.use(Snotify, _options);
Vue.use(VueClipboards);

Vue.component('cookielaw', require('./components/cookie-law.vue').default);
Vue.component('session', require('./components/session.vue').default);
Vue.component('content-modal', require('./components/content-modal.vue').default);
Vue.component('confirmation-modal', require('./components/confirmation-modal.vue').default);
Vue.component('pending-modal', require('./components/pending-modal.vue').default);
Vue.component('copy-button', require('./components/copy-button.vue').default);
Vue.component('api-tokens', require('./components/api-tokens.vue').default);
Vue.component('api-token-create', require('./components/api-token-create.vue').default);
Vue.component('notification-badge', require('./components/notification-badge.vue').default);

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
require('./theme');
