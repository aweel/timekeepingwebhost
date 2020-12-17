<?php
  // Initialize the session
  session_start();
  
  //TODO Create login redirect for HR department
  // Check if the user is logged in, if not then redirect to login page
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login");
    exit;
  }
  
  // Include config file
  require_once "connection.php";
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html; charset=UTF-8"/>
    <meta name="theme-color" content="#4285f4">
    <link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" href="https://static.xx.fbcdn.net/rsrc.php/v3/ys/r/EdXnHjzXA5W.png">
    <link rel="apple-touch-icon" href="./resources/images/icon/xxhdpi.png">
    <link rel="icon" href="./resources/images/icon/32x32.png">

    <title>RBGM-Timekeeping</title>
  
    <!-- Manifest -->
    <link rel='manifest' href='./manifest.json'>
  
    <!-- CSS only -->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons" >
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Quicksand">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/> WORKING-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.2/fc-3.3.1/fh-3.1.7/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.0/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="./resources/css/style.css">
    <link rel="stylesheet" type="text/css" href="./resources/css/bottomnav.css">

    <!-- JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.2/fh-3.1.7/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.0/datatables.min.js"></script>

    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 50vh;/*7rem*/
            width: auto;
        }
        /* Optional: Makes the sample page fill the window. */
        html {
            height: 100%;
            padding: 0;
            font-family: "Quicksand";
        }
        body {
            padding-top: 65px;
            padding-bottom: 65px;
            margin: 0 0 0 0;
            min-height: 100%;
            position: relative;
            overflow: hidden;
            font-family: "Quicksand";
        }
        .bd-example-modal-lg .modal-dialog{
            display: table;
            position: relative;
            margin: 0 auto;
            top: calc(50% - 24px);
        }
        .bd-example-modal-lg .modal-dialog .modal-content{
            background-color: transparent;
            border: none;
        }
    </style>
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
    
    function unregisterSwAll() {
        navigator.serviceWorker.register('sw2.js').then(function(registration) {
        alert('Service worker 2 refreshed!');
          // Registration was successful
          console.log('ServiceWorker 2  registration successful with scope: ', registration.scope);
        }, function(err) {
          // registration failed :(
          console.log('ServiceWorker 2 registration failed: ', err);
        });
    }
  </script>			
</head>
<body>
<header>
  <div class="pos-f-t mb-3">
    <nav class="navbar fixed-top navbar-expand-md bg-dark navbar-dark p-b-3">
      <!-- Brand -->
      <a class="navbar-brand" href="./location2.php">Timekeeping</a>
      
      <!-- Toggler/collapsible Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <!-- Navbar links -->
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="./history.php">HISTORY</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ACCOUNT</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="#">Profile</a>
              <a class="dropdown-item" href="./changepass.php">Change Password</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./logout.php">LOGOUT</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://timekeep.000webhostapp.com/location2.php">Open App</a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</header>


<div class="alert alert-success alert-dismissible" id="timeInSuccess" role="alert" style="display: none">Time in saved!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button></div>
<div class="alert alert-error alert-dismissible" id="timeError" role="alert" style="display: none">Oops, There is something wrong. Please try again.<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button></div>
<div class="alert alert-success alert-dismissible" id="timeOutSuccess" role="alert" style="display: none">Time out saved!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button></div>

<div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="width: 48px">
            <span class="fa fa-spinner fa-spin fa-3x"></span>
        </div>
    </div>
</div>