/**
 * Ask the service worker to fetch the heavier form-field files (Quill and
 * friends) while the browser is idle, so the first editor opens from cache.
 * The worker skips anything it already has; once per session is plenty.
 */
const WARM_KEY = "service-worker-warmed";

const alreadyWarmed = () => {
    try {
        return Boolean(window.sessionStorage.getItem(WARM_KEY));
    } catch (error) {
        // Storage blocked (private mode); the worker skips cached files anyway.
        return false;
    }
};

const markWarmed = () => {
    try {
        window.sessionStorage.setItem(WARM_KEY, "1");
    } catch (error) {
        // Ignore, see above.
    }
};

const warm = () => {
    const worker = navigator.serviceWorker && navigator.serviceWorker.controller;

    if (!worker || alreadyWarmed()) {
        return;
    }

    worker.postMessage({ type: "warm" });
    markWarmed();
};

const whenIdle = (callback) => {
    if ("requestIdleCallback" in window) {
        window.requestIdleCallback(callback, { timeout: 10000 });
    } else {
        window.setTimeout(callback, 3000);
    }
};

const saveData = navigator.connection && navigator.connection.saveData;

if ("serviceWorker" in navigator && !saveData) {
    window.addEventListener("load", () => whenIdle(warm));
}
