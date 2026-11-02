var staticCacheName = "{{ $version }}"

// Third-party files live in their own cache. It doesn't start with "pwa-", so
// a deploy doesn't throw away CDN files that haven't changed.
var cdnCacheName = "cdn-v1";
var cdnCacheLimit = 200;

// The offline page is a standalone document with inlined CSS precisely so it
// can be cached and served with no network at all. It's the one file that
// must precache for the worker to install.
var offlineUrl = "/offline";
var filesToCache = [
    offlineUrl,
    '/images/icons/icon-192x192.png'
];

// The Vite build. Filenames are content-hashed, so these never change in place.
var buildAssets = @json($buildAssets);

// CDN files for the heavier form fields (laravelpwa.warm_fields), fetched on a
// "warm" message so an editor opens from cache the first time.
var warmAssets = @json($warmAssets);

var cdnHosts = @json($cdnHosts);

// Cache on install
self.addEventListener("install", event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                // A build file that fails to fetch shouldn't block the install;
                // it'll be cached on first use instead.
                return cache.addAll(filesToCache).then(() => {
                    return Promise.allSettled(buildAssets.map(url => cache.add(url)));
                });
            })
    )
});

// Clear old caches on activate, then take over any open pages. Navigation
// preload starts the page request while the worker boots, so intercepting
// navigations doesn't add worker start-up time to every page load.
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        }).then(() => {
            if (self.registration.navigationPreload) {
                return self.registration.navigationPreload.enable();
            }
        }).then(() => self.clients.claim())
    );
});

// Web Push. Always show something: Safari (and iOS) revoke the subscription
// if a push arrives and no notification is displayed.
self.addEventListener("push", event => {
    var payload = {};

    try {
        payload = event.data ? event.data.json() : {};
    } catch (error) {
        payload = { body: event.data ? event.data.text() : "" };
    }

    var title = payload.title || @json(config('app.name'));

    event.waitUntil(self.registration.showNotification(title, payload));
});

// Open (or focus) the app at the notification's url when it's clicked.
self.addEventListener("notificationclick", event => {
    event.notification.close();

    var target = new URL(
        (event.notification.data && event.notification.data.url) || "/",
        self.location.origin
    );

    // Never let a payload send us off-site.
    if (target.origin !== self.location.origin) {
        target = new URL("/", self.location.origin);
    }

    event.waitUntil(
        clients.matchAll({ type: "window", includeUncontrolled: true }).then(windowClients => {
            var existing = windowClients.find(client => new URL(client.url).origin === self.location.origin);

            if (existing) {
                // navigate() rejects for tabs this worker doesn't control yet.
                return existing.focus()
                    .then(client => (client || existing).navigate(target.href))
                    .catch(() => clients.openWindow(target.href));
            }

            return clients.openWindow(target.href);
        })
    );
});

// Fetch a CDN file without credentials and in CORS mode, so the stored copy
// isn't opaque (Chrome counts each opaque entry as ~7MB of quota). The CDNs we
// allow all send Access-Control-Allow-Origin: *.
var fetchCdn = url => fetch(new Request(url, { mode: "cors", credentials: "omit" }));

// Keep the CDN cache bounded; the oldest entries go first.
var trimCdnCache = () => {
    return caches.open(cdnCacheName).then(cache => {
        return cache.keys().then(keys => {
            return Promise.all(
                keys.slice(0, Math.max(0, keys.length - cdnCacheLimit)).map(key => cache.delete(key))
            );
        });
    });
};

var putInCache = (cacheName, request, response) => {
    if (!response || !response.ok) {
        return Promise.resolve();
    }

    return caches.open(cacheName)
        .then(cache => cache.put(request, response))
        .then(() => (cacheName === cdnCacheName ? trimCdnCache() : null));
};

// CDN responses vary on headers a worker's own requests don't carry, so
// match on the URL alone.
var matchOptions = { ignoreVary: true };

// Serve from cache, and only go to the network (then cache) on a miss.
var cacheFirst = (event, cacheName, fetcher) => {
    return caches.match(event.request, matchOptions).then(cached => {
        if (cached) {
            return cached;
        }

        return fetcher().then(response => {
            event.waitUntil(putInCache(cacheName, event.request, response.clone()));

            return response;
        });
    });
};

// Serve from cache straight away, and refresh the cached copy in the background.
var staleWhileRevalidate = (event, cacheName, fetcher) => {
    return caches.match(event.request, matchOptions).then(cached => {
        var refresh = fetcher().then(response => {
            return putInCache(cacheName, event.request, response.clone()).then(() => response);
        });

        if (cached) {
            event.waitUntil(refresh.catch(() => null));

            return cached;
        }

        return refresh;
    });
};

// A CDN file is only immutable when its URL pins an exact version
// (quill@2.0.3). Ranges, @latest and unversioned paths can change underneath us.
var isPinned = url => /@\d+\.\d+\.\d+/.test(url.pathname);

// The page asks for the editor files once the browser is idle.
self.addEventListener("message", event => {
    if (!event.data || event.data.type !== "warm") {
        return;
    }

    event.waitUntil(
        caches.open(cdnCacheName).then(cache => {
            return Promise.allSettled(warmAssets.map(url => {
                return cache.match(url, matchOptions).then(cached => {
                    if (cached) {
                        return null;
                    }

                    return fetchCdn(url).then(response => putInCache(cdnCacheName, url, response));
                });
            }));
        })
    );
});

self.addEventListener("fetch", event => {
    // Never intercept anything that changes server state. Form posts, Livewire
    // updates and API writes go straight to the network.
    if (event.request.method !== "GET") {
        return;
    }

    var url = new URL(event.request.url);

    // Third-party asset CDNs only. Analytics, captcha and APIs go straight
    // to the network.
    if (url.origin !== self.location.origin) {
        if (url.protocol !== "https:" || cdnHosts.indexOf(url.hostname) === -1) {
            return;
        }

        // If the CORS fetch fails, hand back the page's own request uncached
        // rather than breaking the asset.
        var fetcher = () => fetchCdn(event.request.url).catch(() => fetch(event.request));
        var strategy = isPinned(url) ? cacheFirst : staleWhileRevalidate;

        event.respondWith(strategy(event, cdnCacheName, fetcher));

        return;
    }

    // Page loads: prefer the network (via the preloaded response when there is
    // one), fall back to the offline page so a dropped connection lands
    // somewhere that explains itself. Pages are never cached: they carry CSRF
    // tokens, session state and Livewire snapshots.
    if (event.request.mode === "navigate") {
        event.respondWith(
            Promise.resolve(event.preloadResponse)
                .then(preloaded => preloaded || fetch(event.request))
                .catch(() => caches.match(offlineUrl))
        );

        return;
    }

    var network = () => fetch(event.request);

    // Precached assets, the hashed Vite build, and Livewire's script (served
    // from a per-install /livewire-{hash}/ prefix; its ?id= query changes with
    // each release): cache-first.
    if (
        filesToCache.indexOf(url.pathname) !== -1
        || url.pathname.startsWith("/build/assets/")
        || (/\/livewire(\.csp)?(\.min)?\.js$/.test(url.pathname) && url.searchParams.has("id"))
    ) {
        event.respondWith(cacheFirst(event, staticCacheName, network));

        return;
    }

    // Our own fonts and images can change in place: serve the cached copy and
    // refresh it behind the scenes.
    if (url.pathname.startsWith("/fonts/") || url.pathname.startsWith("/images/")) {
        event.respondWith(staleWhileRevalidate(event, staticCacheName, network));
    }

    // Everything else (user uploads, Livewire updates, APIs) falls through to
    // the network untouched.
});
