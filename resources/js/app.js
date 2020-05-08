/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// Our sidebar handling for varying screen sizes
require('./sidebar');

window.Vue = require('vue');

import Snotify from 'vue-snotify';
window.Vue.use(Snotify);

import VueClipboards from 'vue-clipboards';
Vue.use(VueClipboards);

window.disableAndNotify = function (e, _message) {
    e.disabled = true;
    window.Snotify.info(_message)
}

window.ajax = (_event) => {
    _event.preventDefault();

    let _form = _event.target.parentNode.parentNode.parentNode;
    let _method = _form.method.toLowerCase();
    let _payloadArray = $(_form).serializeArray();
    let _payload = {};

    $.map(_payloadArray, function(n, i){
        _payload[n['name']] = n['value'];
    });

    window.axios[_method](_form.action, _payload)
        .then((response) => {
            window.Snotify.success(response.data.message);
        })
        .catch((error) => {
            window.Snotify.warning(error.response.data.message);

            for (var key in error.response.data.errors) {
                $('input[name="'+key+'"]').addClass('border-danger');
                window.Snotify.error(error.response.data.errors[key][0]);
            }
        });
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('cookielaw', require('./components/cookie-law.vue').default);
Vue.component('session', require('./components/session.vue').default);
Vue.component('modal', require('./components/modal.vue').default);
Vue.component('copy-button', require('./components/copy-button.vue').default);
Vue.component('api-token', require('./components/api-token.vue').default);
Vue.component('notification-badge', require('./components/notification-badge.vue').default);
Vue.component('notification-marker', require('./components/notification-marker.vue').default);

import Calendar from 'v-calendar/lib/components/calendar.umd'
import DatePicker from 'v-calendar/lib/components/date-picker.umd'

// Register components in your 'main.js'
Vue.component('calendar', Calendar)
Vue.component('v-date-picker', DatePicker)

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
