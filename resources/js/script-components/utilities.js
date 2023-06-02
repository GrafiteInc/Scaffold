/*
|--------------------------------------------------------------------------
| For debouncing needs
|--------------------------------------------------------------------------
*/
window.app.debounce = function (func, timeout = 300) {
    let timer;

    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
};
