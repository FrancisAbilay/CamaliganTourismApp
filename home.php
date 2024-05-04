<?php
// Initialize the session
session_start();
$_SESSION["Current_Page"] = "home";
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
    <div class="page-heading about-heading header-text" style="background-image: url(asset/images/topviewed.JPG);">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="text-content">
              <h2>DAGOS PO SA CAMALIGAN</h2>.
                <h4>Home of the Camaligan River Park and Cruise</h4>
            </div>
            <div>
              <a href="virtual_tour.php"> <h6><u> Virtual Tour</u></h2></a>
              <a href="tourist_map.php"> <h6><u>Tourism Map</u></h2></a>
              <a href="package_booking.php"> <h6><u>Book Now</u></h2></a>
           </div>
          </div>
        </div>
      </div>
    </div>

    <div class="products">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="product-item">
              <a href="#"><img src="asset/images/Camaligan_River_Park.jpg" alt=""></a>
              <div class="down-content">
                <a href="#"><h4>Camaligan River Park</h4></a>

                <p><b>Environmental Fee Entrance: P50 per person</b></p>

                <p> The park is a joint project of Department of Tourism Region V and the Local Government Unit of Camaligan. It is consist of a mile long wharf/walkway, a hanging bridge, and a docking site for the Camaligan River Cruise. </p>


                <p>  <small>- Wikipedia </small></p>
                <p> <small> Photo by MharAnthony16</small></p>

              </div>
            </div>
          </div>


          <div class="col-md-4">
            <div class="product-item">
              <img src="asset/images/Camaligan_River_Cruise.jpg" alt="">
              <div class="down-content">
                <a href="#"><h4>Camaligan River Cruise</h4></a>

                <p><b>Package 1: P500 per person<br>Package 2: P5000 for 4hrs venue rental</b></p>

                <p>Camaligan River Cruise is a three hour cruise along the Bicol River. Enjoy the sceneries, rich history of each of the baranggay and its residents. The highlight of the tour is the sunset view along the river. Package includes dinner buffet, and live band entertainment. </p>

                <p>  <small>Photo by Doods M. Santos</small></p>

              </div>
            </div>
          </div>

          <div class="col-md-4">
          <div class="product-item">
            <img src="asset/images/Hanging_Bridge2.jpg" alt="">
            <div class="down-content">
              <a href="#"><h4>Hanging Bridge</h4></a>

              <p> Camaligan Hanging Footbridge is part of the Camaligan River Park Phase V project. Known as Camaligan Hanging Bridge, it connects the isolated barangays of San Francisco and Tarosanan to the town proper of Camaligan. It is best viewed at night with its vibrant lights and the cool river breeze.</p>


              <p>  <small>- Wikipedia </small></p>
              <p>  <small>Photo by CVR Photograpy </small></p>

            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="product-item">
            <img src="asset/images/camaligan_walkway.jpg" alt="">
            <div class="down-content">
              <a href="#"><h4>Camaligan River Park Walkway</h4></a>

              <p>Camaligan River Walkway is a kilometer stretch walkway of the park. Stroll together with your family and loved ones, or just sit to one of the bench while enjoying a cold breeze of the river while watching to a relaxing sunset. Food and refreshment booths are available after a tiring walk. </p>

              <p> <br> </p>

              <p> <small> Photo by Doods M. Santos</small></p>

            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="product-item">
            <img src="asset/images/OpenHall.jpg" alt="">
            <div class="down-content">
              <a href="#"><h4>River Park Function Hall</h4></a>

                <p><b>Package: P5000 for 6hrs venue rental</b></p>

                <p>The Camaligan River Park Open Hall is a perfect venue for your special celebration be it birthday or wedding celebration. With a capacity to accommodate up to 200 guests, it is an ideal setting for both intimate gatherings and grand festivities. Book now to enjoy the venue overlooking the Bicol River. </p>

              <p> <small> Photo by camsurstaystray.blogspot.com</small></p>

            </div>
          </div>
        </div>
          <div class="col-md-4">
            <div class="product-item">
              <img src="asset/images/Camaligan_Church_facade.jpg" alt="">
              <div class="down-content">
                <a href="#"><h4>St. Anthony de Padua Church</h4></a>

                <p>The St. Anthony of Padua Parish Church, is a Roman Catholic church in Camaligan. The parish was established in 1795 and the church was completed in 1857. The church is one of the oldest churches in Camarines Sur that is rich in religious and cultural history, and a popular spot for Visita Iglesia.</p>

                <p>  <small>- Wikipedia </small></p>
                <p> <small> Photo by John Pasa </small></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php
  if(!($is_admin=== 1)){ ?>

        <div class="call-to-action">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <div class="inner-content">
                  <div class="row">
                    <div class="col-md-8">
                      <h4>For more information on the latest packages and bookings, feel free to contact us.</h4>
                      <p>You may also visit us at our office located at the Camaligan Municipal Hall, Camaligan Camarines Sur.</p>
                    </div>
                    <div class="col-lg-4 col-md-6 text-right">
                      <a href="contact_us.php" class="filled-button">Contact Us</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
  <?php }?>


  <?php include "footer.php"?>
