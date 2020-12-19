<?php
  // Include config file
  require_once "connection.php";
  
  // Define variables and initialize with empty values
  $username = $password = $confirm_password = "";
  $username_err = $password_err = $confirm_password_err = "";
  $firstname = "";
  $lastname = "";
  $position = $position_err = "";
  $contactnumber = $contactnumber_err = "";
  $usertype = "";
  $firstname_err = $firstname_err = "";
  $lastname_err = $lastname_err = "";
  
  // Processing form data when form is submitted
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate username
    if(empty(trim($_POST["username"]))){
      $username_err = "Please enter a username.";
    } else{
      // Prepare a select statement
      $sql = "SELECT empId FROM users WHERE username = :username";
      
      if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        
        // Set parameters
        $param_username = trim($_POST["username"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
          if($stmt->rowCount() == 1){
            $username_err = "This username is already taken.";
          } else{
            $username = trim($_POST["username"]);
          }
        } else{
          echo "Oops! Something went wrong. Please try again later.";
        }
        
        // Close statement
        unset($stmt);
      }
    }
  
    // Validate first name
    if(empty(trim($_POST["firstname"]))){
      $firstname_err = "Please enter your first name.";
    } else{
      $firstname = trim($_POST["firstname"]);
    }
  
    // Validate last name
    if(empty(trim($_POST["lastname"]))){
      $lastname_err = "Please enter your last name.";
    } else{
      $lastname = trim($_POST["lastname"]);
    }
  
    // Validate contact number
    if(empty(trim($_POST["contactnumber"]))){
      $contactnumber_err = "Please enter numeric value.";
    }else if(strlen(trim($_POST["contactnumber"])) >= 8 || strlen(trim($_POST["contactnumber"])) <= 11){
      if(strlen(trim($_POST["contactnumber"])) != 8 || strlen(trim($_POST["contactnumber"])) != 11){
        $contactnumber_err = "Contact number must be 8.";
        unset($_POST["contactnumber"]);
        header('location: userAccount.php?errmsg=Contact number must be 8 numbers for telephone and 13 numbers for mobile.');
          exit();
      }
    } elseif(is_numeric($_POST["contactnumber"]) === false){
      $contactnumber_err = "Please enter numeric value.";
      header('location: userAccount.php?errmsg=Please enter numeric value.');
      exit();
    } else{
      $contactnumber = trim($_POST["contactnumber"]);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
      $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
      $password_err = "Password must have atleast 6 characters.";
    } else{
      $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
      $confirm_password_err = "Please confirm password.";
    } else{
      $confirm_password = trim($_POST["confirm_password"]);
      if(empty($password_err) && ($password != $confirm_password)){
        $confirm_password_err = "Password did not match.";
      }
    }
    
    $usertype = $_POST["usertype"];
    $position = $_POST["position"];
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($firstname_err) && empty($lastname_err) && empty($confirm_password_err)){
      
      // Prepare an insert statement
      $sql = "INSERT INTO users (firstname, lastname, usertype, position, contactnumber, username, password) VALUES (:firstname, :lastname, :usertype, :position, :contactnumber, :username, :password)";
      
      if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":firstname", $param_firstname, PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $param_lastname, PDO::PARAM_STR);
        $stmt->bindParam(":usertype", $param_usertype, PDO::PARAM_INT);
        $stmt->bindParam(":position", $param_position, PDO::PARAM_STR);
        $stmt->bindParam(":contactnumber", $param_contactnumber, PDO::PARAM_STR);
        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
        
        // Set parameters
        $param_firstname = $firstname;
        $param_lastname = $lastname;
        $param_usertype = $usertype;
        $param_position = $position;
        $param_contactnumber = $contactnumber;
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
          // Redirect to login page
          header("location: login.php");
        } else{
          echo "Something went wrong. Please try again later.";
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
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <style type="text/css">
    body{ font: 14px sans-serif; }
    .wrapper{ width: 350px; padding: 20px; }
  </style>
</head>
<body>
<div class="wrapper">
  <h2>Register Account</h2>
  <p>Please fill this form to create an account.</p>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="form-group <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
      <label>First Name</label>
      <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>">
      <span class="help-block"><?php echo $firstname_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
      <label>Last Name</label>
      <input type="text" name="lastname" class="form-control" value="<?php echo $lastname; ?>">
      <span class="help-block"><?php echo $lastname_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($position_err)) ? 'has-error' : ''; ?>">
      <label>Position</label>
      <input type="text" name="position" class="form-control" value="<?php echo $position; ?>">
      <span class="help-block"><?php echo $position_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($contactnumber_err)) ? 'has-error' : ''; ?>">
      <label>Contact Number</label>
      <input type="number" name="contactnumber" class="form-control" value="<?php echo $contactnumber; ?>">
      <span class="help-block"><?php echo $contactnumber_err; ?><?php if(isset($_GET['errmsg'])) {echo $_GET['errmsg']; unset($_GET['errmsg']);} ?></span>
    </div>
    <div class="form-group">
      <label>User Type</label>
      <select class="form-control" id="usertype" name="usertype">
        <option value="<?php echo $usertype=1; ?>">USER</option>
        <option value="<?php echo $usertype=2; ?>">HR</option>
        <option value="<?php echo $usertype=3; ?>">SYSADMIN</option>
      </select>
      <span class="help-block"></span>
    </div>
    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
      <label>Username</label>
      <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
      <span class="help-block"><?php echo $username_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
      <label>Password</label>
      <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
      <span class="help-block"><?php echo $password_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
      <label>Confirm Password</label>
      <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
      <span class="help-block"><?php echo $confirm_password_err; ?></span>
    </div>
    <div class="form-group">
      <input type="submit" class="btn btn-primary" value="Submit">
      <input type="reset" class="btn btn-default" value="Reset">
    </div>
    <!--<p>Already have an account? <a href="login.php">Login here</a>.</p>-->
  </form>
</div>
</body>
</html>