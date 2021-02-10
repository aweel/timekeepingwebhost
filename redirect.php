<?php
  
  // Initialize the session
  session_start();
  
  sleep(2);

  // Check if the user is already logged in, if yes then redirect him to welcome page
//TODO jomel 02102021 Dito ilagay un check kung android or iphone laptop o desktop
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      $usertype = (int)$_GET["u"];
      if ($usertype === 1){
          header ("location: location2.php");
          exit;
        }else if ( $usertype === 2 ){
          header ("location: admin.php");
          exit;
        }else if ( $usertype === 3 ){
          header ("location: sysadmin.php");
          exit;
        }else
          header ("location: location2.php");
          exit;
  }else{
    header("location: login")  ;
    exit;
  }
?>