/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('./sidebar');

require('@dashboardcode/bsmultiselect');

window.Vue = require('vue');

import Snotify from 'vue-snotify';
window.Vue.use(Snotify);

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
        .catch((response) => {
            window.Snotify.warning(response.data.message);
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

Vue.component('cookielaw', require('./components/cookieLaw.vue').default);
Vue.component('session', require('./components/session.vue').default);
Vue.component('modal', require('./components/modal.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

$("select[multiple='multiple']").bsMultiSelect();
