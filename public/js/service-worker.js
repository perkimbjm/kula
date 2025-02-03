const CACHE_NAME = 'cache-v1';  
const urlsToCache = [  
  '/',
  '/css/app.css',  
  '/js/app.js',  
  '/img/favicon/icon-192x192.png',  
  '/img/favicon/icon-512x512.png'  
];  

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache.map(url => new Request(url, {
          credentials: 'same-origin',
          redirect: 'follow'
        })));
      })
      .catch(error => {
        console.error('Cache addition failed:', error);
      })
  );
}); 

self.addEventListener('fetch', event => {  
  event.respondWith(  
    caches.match(event.request)  
      .then(response => {  
        if (response) {  
          return response;  
        }  
        return fetch(event.request);  
      })  
  );  
});  

self.addEventListener('activate', event => {  
  const cacheWhitelist = [CACHE_NAME];  

  event.waitUntil(  
    caches.keys().then(cacheNames => {  
      return Promise.all(  
        cacheNames.map(cacheName => {  
          if (cacheWhitelist.indexOf(cacheName) === -1) {  
            return caches.delete(cacheName);  
          }  
        })  
      );  
    })  
  );  
});  
