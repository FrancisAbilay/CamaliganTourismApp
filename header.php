<?php
// Initialize the session
  $fullname = $is_admin ="";

  //set timeout to 10 mins
  $time = $_SERVER['REQUEST_TIME'];
  $timeout= 600;


  $useragent=$_SERVER['HTTP_USER_AGENT'];
  $_SESSION['BROWSER'] = "web";

  if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent))
  {
      $_SESSION['BROWSER'] = "mobile";
  }
 if(preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)-/i',substr($useragent,0,4)))
 {
     $_SESSION['BROWSER'] = "mobile";
 }

 if(preg_match('/phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
 {
     $_SESSION['BROWSER'] = "mobile";
 }

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


    $useragent=$_SERVER['HTTP_USER_AGENT'];


  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Camaligan Tourism">
    <meta name="author" content="Francis">
    <link rel="icon" href="assets/images/favicon.ico">

    <title>Camaligan Tourism Website</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="asset/css/fontawesome.css">
  	<link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">



  </head>

  <body>

        <!-- ***** Preloader Start ***** -->
        <div id="preloader">
            <div class="jumper">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <!-- ***** Preloader End ***** -->
    <!-- Header -->
    <header class="">
    <nav class="navbar navbar-expand-lg">
      <div class="container">
        <?php
        if($_SESSION['BROWSER']==="web"){ ?>
          <img src="asset\images\CamaliganLogo.jpg" width="55px" height="55px" alt="" class="web-logo"/>
          <a class="navbar-brand" href="home.php"><h2>&nbspCamaligan Tourism <em>Website</em></h2></a>
        <?php
        }else{?>
          <a class="navbar-brand" href="home.php"><h5 style="color: #f33f3f; font-size: 20px;font-weight: 700;">Camaligan Tourism <em>WEBSITE&nbsp;</em></h5></a>
      <?php }?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <?php
            if(!isset($_SESSION["loggedin"])){
             ?>
              <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="home"? "  active":"";?>"><a href="home.php" aria-current="page" class="nav-link">Home</a></li>
              <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="login"? "  active":"";?>"><a href="login.php" class="nav-link">Login</a></li>
              <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="register"? "  active":"";?>"><a href="register.php" class="nav-link">Register</a></li>
              <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="menu"? "  active":"";?>"><a href="package_booking.php" class="nav-link">Book Now</a></li>
              <li class="nav-item dropdown <?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="more"? "  active":"";?>">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">More</a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="virtual_tour.php">Virtual Tour</a>
                    <a class="dropdown-item" href="tourist_map.php">Tourism Map</a>
                    <a class="dropdown-item" href="contact_us.php">Contact Us</a>
                  </div>
              </li>
          <?php
          }else{
             if($is_admin=== 1){ ?>
                  <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="home"? " active":"";?>"><a href="home.php" class="nav-link">Home</a></li>
                  <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="menu"? " active":"";?>"><a href="package_list.php" class="nav-link">Package</a></li>
                  <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="order"? " active":"";?>"><a href="reservation_list.php" class="nav-link">Reservations</a></li>
                  <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="message"? " active":"";?>"><a href="message_list.php" class="nav-link">Messages</a></li>
                  <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="report"? " active":"";?>"><a href="report_list.php" class="nav-link">Report</a></li>
                  <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>

            <?php }else {  ?>
                  <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="home"? " active":"";?>"><a href="home.php" class="nav-link">Home</a></li>
                  <?php
                    if(!isset($_SESSION["loggedin"])){
                      ?>
                      <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="login"? " active":"";?>"><a href="login.php" class="nav-link">Login</a></li>
                    <?php }?>
                  <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="menu"? " active":"";?>"><a href="package_booking.php" class="nav-link">Book Now</a></li>
                  <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="checkout"? " active":"";?>"><a href="package_checkout.php" class="nav-link">Checkout</a></li>
                  <li class="nav-item<?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="order"? " active":"";?>"><a href="reservation_list.php" class="nav-link">Reservations</a></li>

              <?php
              if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
              ?>
                    <li class="nav-item dropdown <?php echo isset($_SESSION["Current_Page"]) && $_SESSION["Current_Page"] =="more"? "  active":"";?>">
                      <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">More</a>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="virtual_tour.php">Virtual Tour</a>
                        <a class="dropdown-item" href="tourist_map.php">Tourism Map</a>
                        <a class="dropdown-item" href="contact_us.php">Contact Us</a>
                        <a class="dropdown-item" href="userprofile.php">Profile</a>
                  </div>
                  </li>
                  <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>

            <?php }?>
          <?php }?>
        <?php }?>
          </ul>
        </div>
      </div>
    </nav>
  </header>
