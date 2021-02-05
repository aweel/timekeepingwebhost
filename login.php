<?php
  
  // Initialize the session
  session_start();
  
  // Check if the user is already logged in, if yes then redirect him to welcome page
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: location2");
    exit;
  }
  
  // Include config file
  require_once "connection.php";
  
  // Define variables and initialize with empty values
  $username = $password = "";
  $username_err = $password_err = $errMsg = "";
  
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
    
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
    {
        //Google recaptcha uses jomel.rbgm.medical@gmail.com
      $secret = '6LdEuPwZAAAAAKLnSzptgYDHViKlMwtOe3By2Dv0';
      $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
      $responseData = json_decode($verifyResponse);
      if($responseData->success)
      {
        $succMsg = 'Your contact request have submitted successfully.';
  
        // Validate credentials
        if(empty($username_err) && empty($password_err))
        {
          // Prepare a select statement
          $sql = "SELECT empId, username, position, contactnumber, usertype,lastname, firstname, password FROM users WHERE username = :username";
    
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
                  $usertype = $row["usertype"];
                  $position = $row["position"];
                  $contactnumber = $row["contactnumber"];
            
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
                    $_SESSION["usertype"] = $usertype;
                    $_SESSION["position"] = $position;
                    $_SESSION["contactnumber"] = $contactnumber;
                    
                    //TODO (jomel 20201216) create userpage for admin and create user
                    // Redirect user to welcome page
                    
                    $var1 = $id;
                    $var2 = date("Y/m/d");
                    
                    if ($usertype == 1){
                      header ("location: redirect.php?u=$var1&d=$var2");
                    }else if ( $usertype == 2 ){
                      header ("location: admin.php");
                    }else if ( $usertype == 3 ){
                      header ("location: sysadmin.php");
                    }else
                      header ("location: errpage.php");
                    
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
      else
      {
        //$errMsg = 'Robot verification failed, please try again. huhu';
        
        // Validate credentials
        if(empty($username_err) && empty($password_err))
        {
          // Prepare a select statement
          $sql = "SELECT empId, username, position, contactnumber, usertype,lastname, firstname, password FROM users WHERE username = :username";
    
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
                  $usertype = $row["usertype"];
                  $position = $row["position"];
                  $contactnumber = $row["contactnumber"];
            
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
                    $_SESSION["usertype"] = $usertype;
                    $_SESSION["position"] = $position;
                    $_SESSION["contactnumber"] = $contactnumber;
                    
                    $var1 = $id;
                    $var2 = date("Y/m/d");
                    
                    //TODO (jomel 20201216) create userpage for admin and create user
                    // Redirect user to welcome page
                    //
                    if ($usertype === 1){
                      header ("location: redirect.php?u=$var1&d=$var2");
                    }else if ( $usertype === 2 ){
                      header ("location: admin.php");
                    }else if ( $usertype === 3 ){
                      header ("location: sysadmin.php");
                    }else
                      header ("location: errpage.php");
                    
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
    }
    else
    {
      $errMsg = 'Use recaptcha, please try again.';
    }
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
						<input class="input100" type="text" name="username" >
						<span class="focus-input100"></span>
					</div>
					<span>
                         <?php if($username_err!="") { ?>
                           <span class="errorMsg" id="validation" style="color: darkred; font-size:10px; display: inline"><?php echo $username_err; ?></span>
                         <?php } unset($username_err);?>
                    </span>
					<span class="txt1 p-b-11">
						Password
					</span>
					<div class="wrap-input100 validate-input m-b-12" data-validate = "Password is required">
						<span class="btn-show-pass">
							<i class="fa fa-eye"></i>
						</span>
						<input class="input100" type="password" name="password" >
						<span class="focus-input100"></span>
					</div>
					
					<div class="sb-m w-full p-b-48">
					    <span>
                         <?php if($password_err!="") { ?>
                           <span class="errorMsg" id="validation" style="color: darkred; font-size:10px; display: inline;"><?php echo $password_err; ?></span>
                         <?php } unset($password_err);?>
                        </span>
							<!--<div class="contact100-form-checkbox">
						<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
							
						</div>-->
                        <div>
                            <a href="./resources/app/TimekeepSub_v0.21.apk" class="txt3" style="text-align:left;">
								Download App
							</a>
                        </div>
						<div>
							<a href="#" class="txt3">
								Forgot Password?
							</a>
						</div>
					</div>
          <!-- Google Recaptcha uses jomel.rbgm.medical@gmail.com -->
          <div class="container-login100-form-btn">
            <div name="recaptcha" class="g-recaptcha" data-sitekey="6LdEuPwZAAAAAAasAMAulSDOPxcJZmSVWZQRXGnE"></div>
            <span>
             <?php if($errMsg!="") { ?>
               <span class="errorMsg" id="validation">Use recaptcha before logging in. <?php echo $errMsg; ?></span>
             <?php } unset($errMsg);?>
            </span>
          </div>
          
          <div class="container-login100-form-btn">
              <button type="submit" formmethod="post" formaction="" class="login100-form-btn">Login</button>
          </div>
          
				</form>
			</div>
		</div>
	</div>
	
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
	<script src='https://www.google.com/recaptcha/api.js' async defer ></script>

</body>
</html>