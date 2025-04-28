const cacheName = "pwa-cache-v1";
const assetsToCache = [
    "/",
    "/manifest.json",
    "/icon-192.png",
    "/icon-512.png",
    "/css/app.css",
    "/js/app.js",
];

// Install event - caching static assets
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(cacheName).then((cache) => {
            return cache.addAll(assetsToCache);
        })
    );
    self.skipWaiting(); // langsung aktif
});

// Activate event - clear old caches
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== cacheName) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    self.clients.claim(); // langsung ambil kontrol
});

// Fetch event - serve from cache first, update cache from network
self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }
            return fetch(event.request)
                .then((networkResponse) => {
                    return caches.open(cacheName).then((cache) => {
                        cache.put(event.request, networkResponse.clone());
                        return networkResponse;
                    });
                })
                .catch(() => {
                    // Optionally: return offline.html page if offline
                });
        })
    );
});
