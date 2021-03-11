<?php
// Initialize the session
session_start();

// Include config file
require_once "connection.php";

$phpmailercredentials = parse_ini_file("/home/username/ini_folder/credentials.ini", true);
//TODO jomel 02222021 if there is something wrong with connection please check path.ini
//
$path = parse_ini_file("path.ini", true);
$pathstr = $path['PATH']['path'];
$phpmailercredentials = parse_ini_file($pathstr, true);
$mailerhost = $phpmailercredentials['PHPMAILER_SERVER']['host'];
$maileruser = $phpmailercredentials['PHPMAILER_SERVER']['username'];
$mailerpass = $phpmailercredentials['PHPMAILER_SERVER']['password'];

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Change this based on web server path /home/cp401025/
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
  
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $errMsg = ""; 


$randnum = mt_rand(100000, 999999);
$empId = "";
$lastname = "";
$firstname = "";
$username = "";

    if(isset($_POST['username'])){
        $email = $_POST["username"];
        try
        {
          //Save to db
          $stmt = $pdo->prepare("SELECT empId, username, firstname, lastname FROM users WHERE email=:email");
          $stmt->execute(['email' => $email]); 
          $user = $stmt->fetch();
          if(!empty($user)){
              $empId = $user["empId"];
              $firstname = $user["firstname"];
              $lastname = $user["lastname"];
              $fullname = implode(' ', array($firstname, $lastname));
              $username = $user["username"];
              if ($pdo->beginTransaction())
              {
                  try
                  {
                      $_SESSION["vcode"] = $randnum;
                      $_SESSION["forgotpassId"] = $empId;

                      //Save verification code to db
                      $stmt = $pdo->prepare("UPDATE users SET vcode = ? WHERE empId = ?");
                      $stmt->bindParam(1,$randnum);
                      $stmt->bindParam(2,$empId);
                      $res = $stmt->execute();
                      $pdo->commit();
                  }
                  catch (PDOException $e)
                  {
                      if ($pdo->inTransaction())
                      {
                          $pdo->rollBack();
                      }
                      if ($e->getCode() == 1062) {
                          // Take some action if there is a key constraint violation, i.e. duplicate name
                          printf("awtsuu ".$e);
                      } else {
                          printf("huhu ".$e);
                          throw $e;
                      }
                  }
              }
          }

        }
        catch (PDOException $e)
        {
          
        }
  
        if(!empty($empId)){
        $mail = new PHPMailer(true);
        //Passing `true` enables exceptions

        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $mailerhost;                              // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $maileruser;                          // SMTP username
            $mail->Password = $mailerpass;                           // SMTP password
            $mail->SMTPSecure = 'SSL';                            // Enable SSL encryption, TLS also accepted with port 465
            $mail->Port = 587;                                    // TCP port to connect to
        
            //Recipients
            $mail->setFrom($maileruser, 'Timekeep Admin');          //This is the email your form sends From
            $mail->addAddress($email, $fullname); // Add a recipient address
            //$mail->addAddress('contact@example.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
        
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Reset Password';
            $mail->Body    = "Hello {$username}! Here is your verification code {$randnum}.";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            echo 'Message has been sent';
            header("location: forgotpassv2.php");
            exit();
        }catch (Exception $e){
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
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
						Forgot Password
					</span>

					<span class="txt1 p-b-11">
						Enter your email address
					</span>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<div class="wrap-input100 validate-input m-b-36" data-validate = "Email is required">
						<input class="input100" type="text" name="username" >
						<span class="focus-input100"></span>
					</div>
					<span>
                         <?php if($username_err!="") { ?>
                           <span class="errorMsg" id="validation" style="color: darkred; font-size:10px; display: inline"><?php echo $username_err; ?></span>
                         <?php } unset($username_err);?>
                    </span>
          <!-- Google Recaptcha uses jomel.rbgm.medical@gmail.com -->
          <div class="container-login100-form-btn">
            <div name="recaptcha" class="g-recaptcha" data-sitekey="6LdEuPwZAAAAAAasAMAulSDOPxcJZmSVWZQRXGnE"></div>
            <span>
             <?php if($errMsg!="") { ?>
               <span class="errorMsg" id="validation">Use recaptcha before submitting. <?php echo $errMsg; ?></span>
             <?php } unset($errMsg);?>
            </span>
          </div>
          
          <div class="container-login100-form-btn">
              <button type="submit" formmethod="post" formaction="" class="login100-form-btn">Submit</button>
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