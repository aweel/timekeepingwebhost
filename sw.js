var CACHE_NAME = 'my-site-cache-v1';
var urlsToCache = [
    '/favicon.ico',
    '/layout.html',
    '/location2',
    '/location2.php',
    '/login',
    '/login.php',
    '/manifest.json',
    '/resources/css/bottomnav.css',
    '/resources/css/style.css',
    '/resources/images/icon/32x32.png',
    '/resources/images/icon/xxhdpi.png',
    '/resources/login_ui/css/main.css',
    '/resources/login_ui/css/util.css',
    '/resources/login_ui/fonts/font-awesome-4.7.0/css/font-awesome.min.css',
    '/resources/login_ui/fonts/font-awesome-4.7.0/fonts/fontawesome-webfont.woff2?v=4.7.0',
    '/resources/login_ui/fonts/Linearicons-Free-v1.0.0/icon-font.min.css',
    '/resources/login_ui/fonts/raleway/Raleway-Bold.ttf',
    '/resources/login_ui/fonts/raleway/Raleway-Medium.ttf',
    '/resources/login_ui/fonts/raleway/Raleway-Regular.ttf',
    '/resources/login_ui/fonts/raleway/Raleway-SemiBold.ttf',
    '/resources/login_ui/js/main.js',
    '/resources/login_ui/vendor/animate/animate.css',
    '/resources/login_ui/vendor/animsition/css/animsition.min.css',
    '/resources/login_ui/vendor/animsition/js/animsition.min.js',
    '/resources/login_ui/vendor/bootstrap/css/bootstrap.min.css',   
    '/resources/scripts/datatables.min.js',
    '/resources/css/datatables.min.css',
    '/resources/login_ui/vendor/bootstrap/js/popper.js',
    '/resources/login_ui/vendor/countdowntime/countdowntime.js',
    '/resources/login_ui/vendor/css-hamburgers/hamburgers.min.css',
    '/resources/login_ui/vendor/daterangepicker/daterangepicker.css',
    '/resources/login_ui/vendor/daterangepicker/daterangepicker.js',
    '/resources/login_ui/vendor/daterangepicker/moment.min.js',
    '/resources/login_ui/vendor/jquery/jquery-3.2.1.min.js',
    '/resources/login_ui/vendor/select2/select2.min.css',
    '/resources/login_ui/vendor/select2/select2.min.js',
    '/resources/scripts/app.js',
    '/resources/scripts/attendance.js',
    '/resources/scripts/feather.min.js.map',
    '/resources/scripts/location2.js',
    '/resources/scripts/location.js',
    '/resources/scripts/maps.js',
    '/setttings.php'
	
    ];

/*self.addEventListener('install', function(event) {
  // Perform install steps
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});*/


      
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

        return fetch(event.request).then(
          function(response) {
            // Check if we received a valid response
            if(!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

											 
									
														  
																						 
									
				   
				  
            // IMPORTANT: Clone the response. A response is a stream
            // and because we want the browser to consume the response
            // as well as the cache consuming the response, we need
            // to clone it so we have two streams.
            var responseToCache = response.clone();

            caches.open(CACHE_NAME)
              .then(function(cache) {
                cache.put(event.request, responseToCache);
              });

            return response;
          }
        );
      })
    );
});
*/


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
