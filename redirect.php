<?php
  
  // Initialize the session
  session_start();
  
  sleep(2);
  
  // Check if the user is already logged in, if yes then redirect him to welcome page
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if ($usertype == 1){
          header ("location: location2.php");
          exit;
        }else if ( $usertype == 2 ){
          header ("location: admin.php");
          exit;
        }else if ( $usertype == 3 ){
          header ("location: sysadmin.php");
          exit;
        }else
          header ("location: errpage.php");
          exit;
  }else{
    header("location: login")  ;
    exit;
  }
?>