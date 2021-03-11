<?php
// Initialize the session
  session_start();

// Unset all of the session variables
  $_SESSION = array();

// Destroy the session.
  session_unset();
  session_destroy();
  session_write_close();
  setcookie(session_name(),'',0,'/');
  session_regenerate_id(true);
  
// Redirect to login page
  header("location: login");
  exit();
?>