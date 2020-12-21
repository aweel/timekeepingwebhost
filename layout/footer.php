<!-- Footer section-->
<footer>
  <nav class="navbar fixed-bottom navbar-light bg-light">
    <a href="<?php echo ($_SESSION["usertype"] == 2) ? "./admin.php" : "./location2.php"; ?>" class="nav__link" style="text-decoration: none" >
      <i class="material-icons nav__icon">home</i>
      <span class="nav__text">Home</span>
    </a>
    <a href="<?php echo ($_SESSION["usertype"] == 2) ? "./admin.php" : "./history.php"; ?>" class="nav__link" style="text-decoration: none" >
      <i class="material-icons nav__icon">history</i>
      <span class="nav__text">History</span>
    </a>
    <a href="./setttings.php" class="nav__link" style="text-decoration: none" >
      <i class="material-icons nav__icon">settings</i>
      <span class="nav__text">Settings</span>
    </a>
  </nav>
</footer>
</body>

<!-- Service worker -->
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('sw.js').then(function(registration) {
          // Registration was successful
          console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, function(err) {
          // registration failed :(
          console.log('ServiceWorker registration failed: ', err);
        });
      });
    }
    
    function unregisterSw() {
      navigator.serviceWorker.getRegistrations().then( function(registrations) { for(let registration of registrations) { registration.unregister(); } });
      alert('Service worker refreshed!');
    }
  </script>
</html>