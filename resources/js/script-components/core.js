/**
 * The following component is a shared event system
 * this means you can trigger events anywhere
 * and listen to them anywhere.
 */
window.app.$events = {
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

    removeListener (name, listenerToRemove) {
        if (!this._events[name]) {
            throw new Error(`Can't remove a listener. Event "${name}" doesn't exits.`);
        }

        const filterListeners = (listener) => { return listener !== listenerToRemove; };

        this._events[name] = this._events[name].filter(filterListeners);
    }
};
