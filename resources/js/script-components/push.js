/**
 * Web Push (VAPID) subscriptions for this browser.
 *
 * The server key comes from <meta name="vapid-public-key">, which the layout
 * only renders for signed-in users. Subscribing has to start from a click:
 * Safari and iOS refuse permission prompts that aren't user gestures.
 */
const SYNC_KEY = "push-subscription-synced";

const vapidKey = () => {
    const meta = document.head.querySelector("meta[name=\"vapid-public-key\"]");

    return meta ? meta.content : null;
};

const urlBase64ToUint8Array = (value) => {
    const padding = "=".repeat((4 - (value.length % 4)) % 4);
    const base64 = (value + padding).replace(/-/g, "+").replace(/_/g, "/");
    const raw = window.atob(base64);

    return Uint8Array.from([...raw].map((character) => character.charCodeAt(0)));
};

const isIos = () => {
    return /iphone|ipad|ipod/i.test(navigator.userAgent)
        || (navigator.platform === "MacIntel" && navigator.maxTouchPoints > 1);
};

const isStandalone = () => {
    return window.navigator.standalone === true
        || window.matchMedia("(display-mode: standalone)").matches;
};

const sameKey = (subscription, key) => {
    const current = subscription.options && subscription.options.applicationServerKey;

    if (!current) {
        return true;
    }

    const bytes = new Uint8Array(current);

    return bytes.length === key.length && bytes.every((byte, index) => byte === key[index]);
};

// "AbortError: Registration failed - push service error" means the browser
// couldn't reach its push service (FCM for Chromium browsers), not our server.
const subscribeErrorMessage = (error) => {
    if (error && error.name === "AbortError") {
        return "This browser couldn't reach its push service. In Brave, turn on "
            + "\"Use Google services for push messaging\" in Privacy settings and restart. "
            + "Otherwise check that an ad blocker, VPN or firewall isn't blocking Google's push servers.";
    }

    if (error && error.name === "NotAllowedError") {
        return "Notifications were not allowed for this site.";
    }

    return (error && error.message) || "Couldn't subscribe this device.";
};

// Resolves to undefined when no worker is registered (e.g. local dev without
// PWA_SERVICE_WORKER=true), unlike serviceWorker.ready which never settles.
const workerRegistration = () => navigator.serviceWorker.getRegistration();

const currentSubscription = async () => {
    const registration = await workerRegistration();

    return registration ? registration.pushManager.getSubscription() : null;
};

window.app.push = {
    supported () {
        return Boolean(vapidKey())
            && "serviceWorker" in navigator
            && "PushManager" in window
            && "Notification" in window;
    },

    needsInstall () {
        return isIos() && !isStandalone();
    },

    async status () {
        if (this.needsInstall()) {
            return "install-first";
        }

        if (!this.supported()) {
            return "unsupported";
        }

        if (Notification.permission === "denied") {
            return "denied";
        }

        if (!await workerRegistration()) {
            return "no-worker";
        }

        const subscription = await currentSubscription();

        return subscription && Notification.permission === "granted" ? "on" : "off";
    },

    async enable () {
        const permission = await Notification.requestPermission();

        if (permission !== "granted") {
            throw new Error("Notifications were not allowed for this site.");
        }

        const registration = await workerRegistration();

        if (!registration) {
            throw new Error("The service worker isn't running, so push can't be enabled.");
        }

        const applicationServerKey = urlBase64ToUint8Array(vapidKey());
        let subscription = await registration.pushManager.getSubscription();

        // A subscription made with a different key (an older attempt, or
        // rotated VAPID keys) can't receive our pushes; replace it.
        if (subscription && !sameKey(subscription, applicationServerKey)) {
            await subscription.unsubscribe();
            subscription = null;
        }

        if (!subscription) {
            try {
                subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey,
                });
            } catch (error) {
                throw new Error(subscribeErrorMessage(error));
            }
        }

        await this.save(subscription);
    },

    async disable () {
        const subscription = await currentSubscription();

        if (!subscription) {
            return;
        }

        await window.axios.delete(route("user.push-subscriptions.destroy"), {
            data: { endpoint: subscription.endpoint },
        });

        await subscription.unsubscribe();
    },

    async test () {
        return window.axios.post(route("user.push-subscriptions.test"));
    },

    async save (subscription) {
        const payload = subscription.toJSON();

        // Chrome and Firefox use aes128gcm; older engines only speak aesgcm.
        const encodings = window.PushManager.supportedContentEncodings || ["aesgcm"];
        payload.contentEncoding = encodings.includes("aes128gcm") ? "aes128gcm" : "aesgcm";

        await window.axios.post(route("user.push-subscriptions.store"), payload);
    },

    /**
     * Re-send an existing subscription once per session, so the server stays
     * right after a user switch on a shared browser or a subscription refresh.
     */
    async sync () {
        try {
            if (window.sessionStorage.getItem(SYNC_KEY)) {
                return;
            }
        } catch (error) {
            // Storage blocked (private mode); sync every page instead.
        }

        if (!this.supported() || Notification.permission !== "granted") {
            return;
        }

        const subscription = await currentSubscription();

        if (!subscription) {
            return;
        }

        await this.save(subscription);

        try {
            window.sessionStorage.setItem(SYNC_KEY, "1");
        } catch (error) {
            // Ignore, see above.
        }
    },
};

/**
 * Settings panel: any element with [data-push-panel] gets wired up.
 */
const appName = () => {
    const meta = document.head.querySelector("meta[name=\"application-name\"]");

    return (meta && meta.content) || "this app";
};

const panelMessages = {
    "on": "Push notifications are on for this device.",
    "off": "Push notifications are off for this device.",
    "denied": "Notifications are blocked for this site. Allow them in your browser settings, then reload.",
    "unsupported": "This browser doesn't support push notifications.",
    "no-worker": "The app's service worker isn't running, so push notifications can't be enabled. Reload and try again.",
    "install-first": `Add ${appName()} to your Home Screen (Share, then Add to Home Screen), open it from there, and enable notifications here.`,
};

const renderPanel = async (panel) => {
    const state = await window.app.push.status();

    panel.querySelector("[data-push-status]").textContent = panelMessages[state];
    panel.querySelector("[data-push-enable]").classList.toggle("d-none", state !== "off");
    panel.querySelector("[data-push-disable]").classList.toggle("d-none", state !== "on");
    panel.querySelector("[data-push-test]").classList.toggle("d-none", state !== "on");
};

const errorMessage = (error) => {
    return (error.response && error.response.data && error.response.data.message)
        || error.message
        || "Something went wrong.";
};

const bindPanel = (panel) => {
    const run = (selector, action, success) => {
        const button = panel.querySelector(selector);

        button.addEventListener("click", async (event) => {
            event.preventDefault();
            button.disabled = true;

            try {
                await action();
                window.app.notify.success(success);
            } catch (error) {
                window.app.notify.warning(errorMessage(error));
            } finally {
                button.disabled = false;
                renderPanel(panel);
            }
        });
    };

    run("[data-push-enable]", () => window.app.push.enable(), "Push notifications enabled.");
    run("[data-push-disable]", () => window.app.push.disable(), "Push notifications disabled.");
    run("[data-push-test]", () => window.app.push.test(), "Test notification sent.");

    renderPanel(panel);
};

if (vapidKey()) {
    document.querySelectorAll("[data-push-panel]").forEach(bindPanel);

    window.app.push.sync().catch(() => {
        // Non-critical; the settings panel surfaces real problems.
    });
}
