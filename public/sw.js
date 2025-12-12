const CACHE_NAME = 'pkbmridho-cache-v1';

self.addEventListener('install', (event) => {
  event.waitUntil(self.skipWaiting());
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    fetch(event.request).catch(() => {
      return new Response('Anda sedang offline', {
        headers: { 'Content-Type': 'text/plain; charset=utf-8' }
      });
    })
  );
});
