<?php
// Initialize the session
session_start();
$_SESSION["Current_Page"] = "checkout";

// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"])){
  header("location: login.php");
  exit;
}

if (!isset($_SESSION["order_id"])){
  header("location: package_booking.php");
  exit;
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

  <div id="topmargin"></div><div style="height:10px"></div>

		<div class="order_confirm">
			<h1>Order Confirmation</h1>
      <form action="home.php" method="post">
        <div style="text-align: center " >
        <p>Thank you for submitting your booking. We will process it once the payment has been done.<br></p>

        <p>Your Booking number is <b> <?php echo $_SESSION["order_id"]."."?> </b></p>
        <p>The reservation amount is <b> Php  <?php echo number_format($_SESSION["Total"],2) ."."?>  </b><br></p>
        <p> For Payments please deposit or send to: <br><br></p>
              <b>BPI account: <br></b>
                Camaligan Tourism<br>
            BPI Naga City<br>
            SA: 11111-111-11<br><br></b>

            <b>Palawan Express/Cebuana/Mluilluer/LBC<br></b>
            John Doe <br>
            Sto Domingo, Camaligan Camarines Sur <br><br>


            <b>You may send thru GCash to:<br></b>
            John Doe<br>
            GCash:+639669157590<br><br>

            Send the proof of Payment together <br>
             with your <b> Booking number, Name and Email address </b> to:<br><br>
            <b> Whatsapp/Viber: +639669157590 <br>

        </p>
      </div>
        <input type="submit"  value="Home"  class="btnsubmit">

        <p>Order Completed? <a href="logout.php">Logout here</a>.</p>

    	</form>
		</div>

<?php

include "footer.php"?>
