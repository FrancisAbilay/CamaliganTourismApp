<?php
  // Initialize the session
  session_start();
  $_SESSION["Current_Page"] = "register";

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: home.php");
  exit;
}

// Include config file
require_once "config.php";
$db_handle = new DBController();


// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$fullname = $contactno ="";
$login_err = "";
$fullname_err = $username_err = $password_err = $contactno_err = $confirm_password_err= "";
$password_err .=  "*Password must have at least 8 characters(alphabet and numeric).<br>";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $fullname_err = $username_err = $password_err = $contactno_err = $confirm_password_err= "";
    // Validate username
    $username =  trim($_POST["username"]);
    $fullname = trim($_POST["fullname"]);
    $contactno = trim($_POST["contactno"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);


    if(empty($username)){
        $username_err = "Please enter email as username.";
    } else if (!filter_var(trim($username), FILTER_VALIDATE_EMAIL)) {
        $username_err = "Invalid email format for username. \n";


    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM tbl_users WHERE username = ?";

        if($stmt = mysqli_prepare($db_handle->conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($username);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err .=  "This username is already taken. \n";
                } else{
                    $username = trim($username);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    $login_err .=$username_err;

    // Validate password
    if(empty($fullname)){
      $fullname_err .=  "Please enter user's Full Name. <br>";
    } else{
        // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z-' ]*$/",$fullname)) {
        $fullname_err .= "Only letters and white space allowed. <br>";
      }
    }
    $login_err .=$fullname_err;

    // Validate contact no
    if(empty($contactno)){
      $contactno_err .=  "Please enter user's contact number. <br>";
    } else{
        $contactno = trim($_POST["contactno"]);
    }
    $login_err .=$contactno_err;

    // Validate password
    if(empty($password)){
          $password_err .=  "Please enter a password. <br>";
    } elseif ( !preg_match( "/^[A-Za-z0-9]*$/", $password) || strlen($password) < 8 ){
        $password_err .=  "Password must have at least 8 characters(alphabet and numeric).<br>";
    } else{
        $password = trim($_POST["password"]);
    }
    $login_err .=$password_err;

    // Validate confirm password
    if(empty($confirm_password)){
        $confirm_password_err = "Please confirm password. <br>";
    } else{
        if(empty($login_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.<br>";
        }
    }
    $login_err .=$confirm_password_err;


    // Check input errors before inserting in database
    if(empty($login_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO tbl_users(username, password, fullname, contact_no) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($db_handle->conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_fullname,$param_contactno);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_fullname = $fullname;
            $param_contactno = $contactno;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");


              /*  $verificationCode = md5($username);
                $verificationLink = "http://localhost/CamaliganTourism/verify.php?code=$verificationCode";
                $subject = "Camaligan Tourism Website: Verify Your Email Address";
                $message = "Clink the link below to verify your email address:\n $verificationLink";

                mail($username,$subject,$message);

                echo "Registration successful. Please check your email for verification.";
          */
            } else{
                echo "Something went wrong in creating record. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($db_handle->conn);
}
?>

<?php include "header.php"?>

    <!-- Page Content -->
    <div style = "display:none">
    <div class="page-heading contact-heading header-text" style="background-image: url(asset/images/background_img.jpg);">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="text-content">
              <h2>Register</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="topmargin">
    <div class="wrapper">
        <h2>Registration</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="input-box">
            <input type="text" name="fullname"  placeholder="Enter your name" id="fullname"  value="<?php echo $fullname; ?>" required>
            <small> <small><span class="help-block"><?php echo $fullname_err; ?></span></small></small>
        </div>
        <div class="input-box">
          <input type="text" name="username"  placeholder="Enter your email( username)" id="username"  value="<?php echo $username; ?>" required>
          <small><small><span class="help-block"><?php echo $username_err; ?></span></small></small>
        </div>
        <div class="input-box">
          <input type="password" name="password" placeholder="Create password" id="password" required>
          <small><small> <span class="help-block"><?php echo $password_err; ?></span></small></small>
        </div>
        <div class="input-box">
          <input type="password" name="confirm_password" placeholder="Confirm password" id="confirm_password" required>
          <small><small> <span class="help-block"><?php echo $confirm_password_err; ?></span></small></small>
        </div>
        <div class="input-box">
          <input type="text" name="contactno"  placeholder="Enter mobile number" id="contactno"  value="<?php echo $contactno; ?>" required>
          <small><small><span class="help-block"><?php echo $contactno_err; ?></span></small></small>
        </div>
          <div class="input-box button">
          <input type="Submit" value="Register Now">
        </div>
        <div class="text">
              <h3>Already have an account? <a href="login.php">Login now</a></h3>
                <h3>Please read the <a href="https://www.privacypolicies.com/live/36cc6934-289b-4e87-a79c-eb75f510bde2">Privacy Policy.</a></h3>

          </div>
        </form>
     </div>
   </div>

<?php include "footer.php"?>
