<?php
// Initialize the session
session_start();

// Include config file
require_once "connection.php";

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/cp401025/PHPMailer/src/Exception.php';
require '/home/cp401025/PHPMailer/src/PHPMailer.php';
require '/home/cp401025/PHPMailer/src/SMTP.php';

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $errMsg = "";


$randnum = mt_rand(100000, 999999);
$empId = $_SESSION["forgotpassId"];
$vcode = "";
$sessionvcode = $_SESSION["vcode"];
$null = null;

if(isset($_POST['username'])){
    try
    {
        //Save to db
        $stmt = $pdo->prepare("SELECT vcode FROM users WHERE empId=:empId");
        $stmt->execute(['empId' => $empId]);
        $user = $stmt->fetch();
        if(!empty($user)){
            $vcode = $user["vcode"];
            if($vcode == $_POST['username'] ){
                    if ($pdo->beginTransaction())
                    {
                        try
                        {
                            //Save to db
                            $stmt = $pdo->prepare("UPDATE users SET vcode = ? WHERE empId = ?");
                            $stmt->bindParam(1, $null);
                            $stmt->bindParam(2,$empId);
                            $res = $stmt->execute();
                            $pdo->commit();
                            $_SESSION["vcode"] = $vcode;
                            $_SESSION["changepassid"] = $empId;
                            header("location: forgotpassv3.php");
                            exit();
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
            else
                echo "Verification code mismatch!";
        }
        else
            echo "Verification code mismatch!";
    }
    catch (PDOException $e)
    {

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
						VERIFY USER
					</span>

                <span class="txt1 p-b-11">
						Enter your verification code
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

</body>
</html>