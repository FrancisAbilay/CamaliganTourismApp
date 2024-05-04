<?php
// Initialize the session
  $fullname = $is_admin ="";

  //set timeout to 10 mins
  $time = $_SERVER['REQUEST_TIME'];
  $timeout= 600;
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $fullname =  $_SESSION["fullname"];
    $is_admin =  $_SESSION["is_admin"];

    if(isset($_SESSION['LAST_TIME']) && ($time- $_SESSION['LAST_TIME']) >$timeout)
    {
      $_SESSION = array();
      session_destroy();
      // Redirect to login page
      header("location: home.php");
      exit;
    }
    $_SESSION['LAST_TIME'] = $time;
}

?>

<!-- begin footer -->
<div>
<center style="font-size:15px ; border-top: 1px solid green ;width:100%; margin-top:50px" >Camaligan Tourism Web Project</center>
<center  style="font-size:15px; height:75px;"> Last modified: 27 Apr 2024</center>

</div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Additional Scripts -->
    <script src="asset/js/custom.js"></script>
    <script src="asset/js/owl.js"></script>


  </body>
</html>
