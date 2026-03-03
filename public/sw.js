// RevenueForge Service Worker v1
const CACHE_NAME = "revenueforge-v1";
const SHELL_ASSETS = ["/build/manifest.json"];

// Install: pre-cache shell assets
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(SHELL_ASSETS).catch(() => {
                // Some assets may not be available; that's okay
            });
        }),
    );
    self.skipWaiting();
});

// Activate: clean old caches
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((names) =>
                Promise.all(
                    names
                        .filter((name) => name !== CACHE_NAME)
                        .map((name) => caches.delete(name)),
                ),
            ),
    );
    self.clients.claim();
});

// Fetch: network-first with cache fallback for navigation; cache-first for static assets
self.addEventListener("fetch", (event) => {
    const { request } = event;

    // Skip non-GET requests
    if (request.method !== "GET") return;

    // Skip API, webhook, and external requests
    if (
        request.url.includes("/api/") ||
        request.url.includes("/webhook") ||
        !request.url.startsWith(self.location.origin)
    ) {
        return;
    }

    // Static assets (JS/CSS/images): cache-first
    if (
        request.url.match(/\.(js|css|png|jpg|jpeg|svg|woff2?|ttf|ico)$/) ||
        request.url.includes("/build/")
    ) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;
                return fetch(request).then((response) => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches
                            .open(CACHE_NAME)
                            .then((cache) => cache.put(request, clone));
                    }
                    return response;
                });
            }),
        );
        return;
    }

    // Navigation: network-first
    if (request.mode === "navigate") {
        event.respondWith(fetch(request).catch(() => caches.match(request)));
        return;
    }
});
