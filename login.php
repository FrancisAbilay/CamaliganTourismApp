<?php
// Initialize the session
session_start();
$_SESSION["Current_Page"] = "login";

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

  if ($_SESSION["is_admin"]=== 1){
    header("location: home.php");
  }
  else{
    header("location: home.php");
  }
  exit;
}
// Include config file
require_once "config.php";
$db_handle = new DBController();

// Define variables and initialize with empty values
$username = $password = $fullname = $contactno = $is_admin ="";
 $username_err = $password_err =  "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
      if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
          $username_err = "Invalid email format";
        }

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
        $sql = "SELECT id, username, password, fullname, contact_no, is_admin FROM tbl_users WHERE username = ?";

        if($stmt = mysqli_prepare($db_handle->conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $fullname, $contactno, $is_admin);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["fullname"] = $fullname;
                            $_SESSION["contactno"] = $contactno;
                            $_SESSION["is_admin"] = $is_admin;

                            // Redirect user to welcome page
                            header("location: home.php");
                            //if ($is_admin=== 1){
                            //  header("location: reservation_list.php");
                           //  }
                           //  else{
                            //  header("location: package_booking.php");
                          //  }
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
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
   mysqli_close($db_handle->conn);
}
?>

<?php include "header.php"?>

<div id="topmargin">
  <div class="wrapper">
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="input-box">
           <input type="text" name="username"  placeholder="Enter your email( username)" id="username"  value="<?php echo $username; ?>" required>
          <span class="help-block"><?php echo $username_err; ?></span>
       </div>
       <div class="input-box">
         <input type="password" name="password" placeholder="Enter password" id="password" required>
         <span class="help-block"><?php echo $password_err; ?></span>
       </div>

       <div class="input-box button">
         <input type="Submit" value="Login">
       </div>
       <div class="text">
         <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
      </div>
  	</form>
  </div>
</div>


  <?php include "footer.php"?>
