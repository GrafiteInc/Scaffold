import Vue from 'vue/dist/vue.js';
import VueClipboards from 'vue-clipboards';

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
 * Let's create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(VueClipboards);

Vue.component('Notifications', require('./vue-components/notifications.vue').default);
Vue.component('ContentModal', require('./vue-components/content-modal.vue').default);
Vue.component('ConfirmationModal', require('./vue-components/confirmation-modal.vue').default);
Vue.component('PendingOverlay', require('./vue-components/pending-overlay.vue').default);
Vue.component('Cookielaw', require('./vue-components/cookie-law.vue').default);
Vue.component('CopyButton', require('./vue-components/copy-button.vue').default);
Vue.component('ApiTokens', require('./vue-components/api-tokens.vue').default);
Vue.component('ApiTokenCreate', require('./vue-components/api-token-create.vue').default);
Vue.component('NotificationBadge', require('./vue-components/notification-badge.vue').default);

const app = new Vue({
    el: '#app',
});

window.app = app;
