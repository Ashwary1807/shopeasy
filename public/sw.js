/**
 * ShopEasy PWA Service Worker
 * Version: 1.0.0
 *
 * Strict Caching & Security Boundaries:
 * - NEVER intercepts or caches non-GET requests (POST, PUT, PATCH, DELETE).
 * - NEVER caches sensitive or personalized routes (admin, login, register, cart, checkout, orders, account, wishlist).
 * - Provides safe network-first navigation for public storefront pages with graceful offline fallback.
 */

const CACHE_NAME = 'shopeasy-pwa-v1';

const STATIC_PRECACHE_URLS = [
    '/offline.html',
    '/manifest.webmanifest',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/icons/apple-touch-icon.png',
    '/icons/icon.svg',
    '/favicon.ico'
];

// Sensitive paths that MUST ALWAYS bypass service worker caching completely
const SENSITIVE_PATH_PATTERNS = [
    /^\/admin(\/.*)?$/i,
    /^\/login$/i,
    /^\/register$/i,
    /^\/logout$/i,
    /^\/cart(\/.*)?$/i,
    /^\/checkout(\/.*)?$/i,
    /^\/account(\/.*)?$/i,
    /^\/orders(\/.*)?$/i,
    /^\/wishlist(\/.*)?$/i,
    /^\/reviews(\/.*)?$/i,
    /^\/api(\/.*)?$/i
];

/**
 * Installation Event: Pre-cache static core assets
 */
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_PRECACHE_URLS);
        }).then(() => self.skipWaiting())
    );
});

/**
 * Activation Event: Clean up outdated caches
 */
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((name) => {
                    if (name.startsWith('shopeasy-') && name !== CACHE_NAME) {
                        return caches.delete(name);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

/**
 * Determine if a request URL is a sensitive route that must bypass the cache
 */
function isSensitiveUrl(pathname) {
    return SENSITIVE_PATH_PATTERNS.some((pattern) => pattern.test(pathname));
}

/**
 * Fetch Event Handler
 */
self.addEventListener('fetch', (event) => {
    const request = event.request;

    // Rule 1: Non-GET requests (POST, PUT, PATCH, DELETE) MUST ALWAYS go straight to network
    // This guarantees checkout submissions, coupon redemptions, stock decrement, and order cancellations are never cached.
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    // Only process same-origin requests
    if (url.origin !== self.location.origin) {
        return;
    }

    // Rule 2: Explicitly bypass sensitive routes (admin, auth, cart, checkout, account, orders)
    if (isSensitiveUrl(url.pathname)) {
        return;
    }

    // Rule 3: HTML Navigation (Public pages: home, shop, product details)
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((networkResponse) => {
                    // If network succeeds and returns 200, optionally cache page clone for public routes
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return networkResponse;
                })
                .catch(async () => {
                    // Network failed (offline)
                    const cachedResponse = await caches.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // If not in cache, return the standalone offline page
                    return caches.match('/offline.html');
                })
        );
        return;
    }

    // Rule 4: Static Assets (Images, Icons, CSS, JS, Fonts)
    // Stale-While-Revalidate: Serve from cache if available, while fetching update in background
    if (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font' ||
        url.pathname.startsWith('/icons/') ||
        url.pathname.endsWith('.svg') ||
        url.pathname.endsWith('.png') ||
        url.pathname.endsWith('.jpg') ||
        url.pathname.endsWith('.ico')
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                const fetchPromise = fetch(request)
                    .then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            const responseClone = networkResponse.clone();
                            caches.open(CACHE_NAME).then((cache) => {
                                cache.put(request, responseClone);
                            });
                        }
                        return networkResponse;
                    })
                    .catch(() => cachedResponse);

                return cachedResponse || fetchPromise;
            })
        );
    }
});
