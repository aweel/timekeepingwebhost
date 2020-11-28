<?php
  // Initialize the session
  session_start();
  
  // Include config file
  require_once "connection.php";
  
  // Check if the user is already logged in, if yes then redirect him to welcome page
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: location2");
    exit;
  }
  
  define('__ROOT__', dirname(dirname(__FILE__)));
  echo (__ROOT__.'/login.php');
  
  // Define variables and initialize with empty values
  $username = $password = "";
  $username_err = $password_err = "";
  
  // Processing form data when form is submitted
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
      $username_err = "Please enter username.";
    } else{
      $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
      $password_err = "Please enter your password.";
    } else{
      $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
      // Prepare a select statement
      $sql = "SELECT empId, username, lastname, firstname, password FROM users WHERE username = :username";
      
      if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        
        // Set parameters
        $param_username = trim($_POST["username"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
          // Check if username exists, if yes then verify password
          if($stmt->rowCount() == 1){
            if($row = $stmt->fetch()){
              $id = $row["empId"];
              $username = $row["username"];
              $hashed_password = $row["password"];
              $lastname = $row["lastname"];
              $firstname = $row["firstname"];
              
              //on creating an account, a user enters a password!
              //$password="pwtester";//user keyed in password
              
              $newhash = password_hash($password, PASSWORD_DEFAULT);
              //#newhash now has the only value that you need to store in the db
              //you do not need any more than this value, that you retrieve when you
              //want to verify your password!
              //$2y$10$TI8m/l8VFU5jjacZQrmWI.zXZDrN/WMP/4Rb1zLMUytkO9pGHo34u
              
              
              if(password_verify($password, $hashed_password)){
                // Password is correct, so start a new session
                //session_start();
                
                //check session status
                function is_session_started()
                {
                  if ( php_sapi_name() !== 'cli' ) {
                    if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                      return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
                    } else {
                      return session_id() === '' ? FALSE : TRUE;
                    }
                  }
                  return FALSE;
                }

// Example
                if ( is_session_started() === FALSE ) session_start();
                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["empId"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["lastname"] = $lastname;
                $_SESSION["firstname"] = $firstname;
                
                // Redirect user to welcome page
                header("location: location2.php");
              } else{
                // Display an error message if password is not valid
                $password_err = "The password you entered was not valid.";
              }
            }
          } else{
            // Display an error message if username doesn't exist
            $username_err = "No account found with that username.";
          }
        } else{
          echo "Oops! Something went wrong. Please try again later.";
        }
        
        // Close statement
        unset($stmt);
      }
    }
    
    // Close connection
    unset($pdo);
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>RBGM-Timekeeping</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="resources/login_ui/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="resources/login_ui/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="resources/login_ui/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="resources/login_ui/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="resources/login_ui/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="resources/login_ui/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="resources/login_ui/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="resources/login_ui/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="resources/login_ui/css/util.css">
	<link rel="stylesheet" type="text/css" href="resources/login_ui/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">
				<form class="login100-form validate-form flex-sb flex-w">
					<span class="login100-form-title p-b-32">
						Account Login
					</span>

					<span class="txt1 p-b-11">
						Username
					</span>
          
          <form action="" method="post">
					<div class="wrap-input100 validate-input m-b-36" data-validate = "Username is required">
            <label>
              <input class="input100" type="text" name="username" >
            </label>
            <span class="focus-input100"></span>
					</div>
					
					<span class="txt1 p-b-11">
						Password
					</span>
					<div class="wrap-input100 validate-input m-b-12" data-validate = "Password is required">
						<span class="btn-show-pass">
							<i class="fa fa-eye"></i>
						</span>
            <label>
              <input class="input100" type="password" name="password" >
            </label>
            <span class="focus-input100"></span>
					</div>
					
					<div class="flex-sb-m w-full p-b-48">
						<div class="contact100-form-checkbox">
							<!--<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>-->
						</div>

						<div>
							<a href="#" class="txt3">
								Forgot Password?
							</a>
						</div>
					</div>

					<div class="container-login100-form-btn">
						<button type="submit" formmethod="post" formaction="" class="login100-form-btn">
							Login
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="resources/login_ui/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="resources/login_ui/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="resources/login_ui/vendor/bootstrap/js/popper.js"></script>
	<script src="resources/login_ui/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="resources/login_ui/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="resources/login_ui/vendor/daterangepicker/moment.min.js"></script>
	<script src="resources/login_ui/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="resources/login_ui/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="resources/login_ui/js/main.js"></script>

</body>
</html>