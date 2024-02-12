const CACHE_NAME = 'my-cache';
const DYNAMIC_CACHE_NAME = 'my-dynamic-cache';

const STATIC_FILES = [
    '/',
    '/css/style.css',
    '/js/app.js',
    // ... autres fichiers statiques à mettre en cache
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_FILES);
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keyList) => {
            return Promise.all(
                keyList.map((key) => {
                    if (key !== CACHE_NAME && key !== DYNAMIC_CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request).then((fetchResponse) => {
                return caches.open(DYNAMIC_CACHE_NAME).then((cache) => {
                    cache.put(event.request, fetchResponse.clone());
                    return fetchResponse;
                });
            });
        })
    );
});
