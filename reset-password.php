
<?php

// Initialize the session
session_start();
$_SESSION["Current_Page"] = "more";

// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"])){
  header("location: login.php");
  exit;
}
if($_SESSION["loggedin"] === false){
  header("location: login.php");
  exit;
}


// Include config file
require_once "config.php";
$db_handle = new DBController();

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$login_err = "";

$username = $_SESSION["username"];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate password
    if(empty(trim($_POST["password"]))){
          $login_err .=  "Please enter a password.\n";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $login_err .=  "Password must have atleast 6 characters.\n";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $login_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($login_err) && ($password != $confirm_password)){
            $login_err = "Password did not match.";
        }
    }


    // Check input errors before inserting in database
    if(empty($login_err)){

        // Prepare an insert statement
        $sql = "Update tbl_users SET password = ? WHERE username = ?";

        if($stmt = mysqli_prepare($db_handle->conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: userprofile.php");
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
      <h2>Reset Password</h2>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="input-box">
          <input type="text" name="username" disabled = "disabled" placeholder="Username (email address)" id="username"  value="<?php echo $username; ?>" required>
        </div>
        <div class="input-box">
          <input type="password" name="password"placeholder="Password" id="password" required>
        </div>
        <div class="input-box">
          <input type="password" name="confirm_password"placeholder="Confirm Password" id="confirm_password" required>
          <span class="help-block"><?php echo $login_err; ?></span>
        </div>
        <div class="input-box button">
            <input type="submit"  value="Home">
        </div>
        <div class="text">
            <p>Cancel Reset Password? <a href="userprofile.php">Click here</a>.</p>
        </div>
      </form>
		</div>
  </div>

<?php include "footer.php"?>
