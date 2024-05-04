<?php
// Initialize the session
session_start();
$_SESSION["Current_Page"] = "more";


// Include config file
require_once "config.php";
$db_handle = new DBController();


// Define variables and initialize with empty values
$Name = $Email = $Subject = $Message = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $Name = $_POST['name'];
    $Email = $_POST['email'];
    $Subject = $_POST['subject'];
    $Message = $_POST['message'];

//'$Name','$Email','$Subject','$Message'
    $sql = " INSERT INTO tbl_contact_form_info(name, email, subject, message) VALUES (?, ?, ?, ?)";


    if($stmt = mysqli_prepare($db_handle->conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssss", $param_name, $param_email, $param_subject, $param_message);

        // Set parameters
        $param_name = $Name;
        $param_email = $Email;
        $param_subject = $Subject;
        $param_message = $Message;

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            echo '<script>alert("Thanks! We will be in touch very soon.")</script>';
        } else{
            echo '<script>alert( "Something went wrong in creating record. Please try again later.")</script>';
        }

        // Close statement
          mysqli_stmt_close($stmt);

    }

    // Close connection
    mysqli_close($db_handle->conn);
}
?>
<?php include "header.php"?>

  <!-- Page Content -->
  <div class="page-heading about-heading header-text" style="background-image: url(asset/images/topviewed.JPG);">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="text-content">
            <h2>Contact Us</h2>
          </div>
        </div>
      </div>
    </div>
  </div>


    <div class="find-us">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-heading">
              <h2>Our Location on Maps</h2>
            </div>
          </div>
          <div class="col-md-8">
          <div id="map">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3877.621014013566!2d123.16650737477705!3d13.619943400333305!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a18ca7802f4a83%3A0x79d992131dd07369!2sCamaligan%20Municipal%20Hall!5e0!3m2!1sen!2ssg!4v1713583836863!5m2!1sen!2ssg" width="100%" height="330px" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
          </div>
          <div class="col-md-4">
            <div class="left-content">
              <h4>About our office</h4>
              <p>We are located at the Camaligan Municipal Hall, Camaligan Camarines Sur. </p>
              <p>You could directly visit the Camaligan River Park daily.. </p>
              <ul class="social-icons">
                <li><a href="https://www.facebook.com/CamaliganRiverPark/"><img alt="facebook" src="asset\images\facebook.png"></i></a></li>
                <li><a href="#"><img alt="twitter" src="asset/images/twitter.png"></i></a></li>
                <li><a href="mailto:fabilay@yahoo.com"><img alt="email" src="asset/images/email.png"></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="send-message">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-heading">
              <h2>Send us a Message</h2>
            </div>
          </div>
          <div class="col-md-8">
            <div class="contact-form">
              <form id="contact" action="" method="post">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <fieldset>
                      <input name="name" type="text" class="form-control" id="name" placeholder="Full Name" required>
                    </fieldset>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <fieldset>
                      <input name="email" type="text" class="form-control" id="email" placeholder="E-Mail Address" required>
                    </fieldset>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <fieldset>
                      <input name="subject" type="text" class="form-control" id="subject" placeholder="Subject" required>
                    </fieldset>
                  </div>
                  <div class="col-lg-12">
                    <fieldset>
                      <textarea name="message" rows="6" class="form-control" id="message" placeholder="Your Message" required></textarea>
                    </fieldset>
                  </div>
                  <div class="col-lg-12">
                    <fieldset>
                      <button type="submit" id="form-submit" class="filled-button">Send Message</button>
                    </fieldset>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="col-md-4">

            <h5 class="text-center" style="margin-top: 15px;"></h5>
          </div>
        </div>
      </div>
    </div>

  <?php include "footer.php"?>
