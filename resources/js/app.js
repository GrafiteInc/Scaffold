import Vue from 'vue';
import VueClipboards from 'vue-clipboards';
import {
    ModalPlugin,
    ButtonPlugin,
    ToastPlugin,
    SidebarPlugin,
    OverlayPlugin
} from 'bootstrap-vue';

require('./bootstrap');

Vue.use(VueClipboards);
Vue.use(ModalPlugin);
Vue.use(ButtonPlugin);
Vue.use(ToastPlugin);
Vue.use(SidebarPlugin);
Vue.use(OverlayPlugin);

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

Vue.component('Cookielaw', require('./components/cookie-law.vue').default);
Vue.component('Session', require('./components/session.vue').default);
Vue.component('Notifications', require('./components/notifications.vue').default);
Vue.component('ContentModal', require('./components/content-modal.vue').default);
Vue.component('ConfirmationModal', require('./components/confirmation-modal.vue').default);
Vue.component('PendingOverlay', require('./components/pending-overlay.vue').default);
Vue.component('CopyButton', require('./components/copy-button.vue').default);
Vue.component('ApiTokens', require('./components/api-tokens.vue').default);
Vue.component('ApiTokenCreate', require('./components/api-token-create.vue').default);
Vue.component('NotificationBadge', require('./components/notification-badge.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

window.app = app;

require('./subscription-create');
require('./subscription-payment-method');
require('./ajax-forms');
require('./app-events');
require('./sidebar');
require('./theme');
require('./echo-events');
require('./online');
require('./mobile');
