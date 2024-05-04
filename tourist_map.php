<?php
// Initialize the session
session_start();
$_SESSION["Current_Page"] = "more";
// Initialize the session
  $fullname = $is_admin ="";

  //set timeout to 10 mins
  $time = $_SERVER['REQUEST_TIME'];
  $timeout= 300;
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

<?php include "header.php"?>

  <!-- Page Content -->
  <div style = "display:none">
  <div class="page-heading contact-heading header-text" style="background-image: url(asset/images/background_img.jpg);">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="text-content">
            <h2>Touris  Map</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

    <!-- Page Content -->
    <div id="topmargin">
    <div style="position: relative; padding-bottom: 56.25%; height: 0; ">
      <iframe width="100%"
              src="https://viewer.mapme.com/3f5c10c9-c529-4250-9920-d58a64589bad?fs=0"
              frameborder="0"
          scrolling="no"
        style="height: calc(100vh - 90px); width: 100%; frameborder= 0" allowfullscreen="allowfullscreen">
      </iframe>
    </div>
  </div>


  <?php include "footer.php"?>
