window._ = require('lodash');
window.clipboard = require('clipboard');
window.$ = window.jQuery = require('jquery');
window.bootstrap = require('bootstrap');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

/**
 * The following component is a shared event system
 * this means you can trigger events anywhere
 * and listen to them anywhere.
 */
window.app = {
    $events: {
        _events: {},

        fire (name, data = null) {
            if (!this._events[name]) {
                throw new Error(`Can't emit an event. Event "${name}" doesn't exits.`);
            }

            const fireCallbacks = (callback) => {
                callback(data);
            };

            this._events[name].forEach(fireCallbacks);
        },

        listen (name, listener) {
            if (!this._events[name]) {
                this._events[name] = [];
            }

            this._events[name].push(listener);
        },

        removeListener(name, listenerToRemove) {
            if (!this._events[name]) {
                throw new Error(`Can't remove a listener. Event "${name}" doesn't exits.`);
            }

            const filterListeners = (listener) => listener !== listenerToRemove;

            this._events[name] = this._events[name].filter(filterListeners);
        }
    },
};
