var CACHE_NAME = 'my-site-cache-v1';
var urlsToCache = [
    'layout.html',
    '/resources/css/bottomnav.css',
    '/resources/css/style.css',
    '/resources/scripts/app.js',
    '/resources/scripts/attendance.js',
    '/resources/scripts/datatables.min.js',
    '/resources/scripts/location2.js',
    '/resources/scripts/maps.js',
    'login.php'
    
];

self.addEventListener('install', function(event) {
  // Perform install steps
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

/*
self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        // Cache hit - return response
        if (response) {
          return response;
        }
        return fetch(event.request);
      }
    )
  );
});
*/