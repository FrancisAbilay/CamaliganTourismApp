<?php
  // Initialize the session
  session_start();
  $_SESSION["Current_Page"] = "message";

  require_once("config.php");
  $db_handle = new DBController();
  $id = $name = $email = $contact = $message = "";
  $msg = '';


  // Check if the id exists, for example message_view.php?id=1 will get the message with the id of 1
  if (isset($_GET['id'])) {
    $id =  $_GET['id'];
    $sql = "SELECT name, email, subject, message FROM tbl_contact_form_info WHERE id = ".$id;


    if($stmt = mysqli_prepare($db_handle->conn, $sql)){
    // Attempt to execute the prepared statement
     if(mysqli_stmt_execute($stmt)){
         // Store result
         mysqli_stmt_store_result($stmt);
         // Check if username exists, if yes then verify password
        if(mysqli_stmt_num_rows($stmt) == 1){
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $name, $email, $subject, $message);
            if(mysqli_stmt_fetch($stmt)){
               mysqli_stmt_close($stmt);
             }
          }
          else{
            $msg ='Message doesn\'t exist with that ID!';
          }

      }
    }


  } else {
      $msg ='No ID specified!';
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
  <div class="send-message">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="section-heading">
            <h2>View Message</h2>
          </div>
        </div>
        <div class="col-md-8">
          <div class="contact-form">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <fieldset>
                    <input name="name" type="text" class="form-control" id="name"  value="Name: <?php echo $name; ?>" disabled = "disabled">
                  </fieldset>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <fieldset>
                    <input name="email" type="text" class="form-control" id="email" value="Email: <?php echo $email; ?>" disabled = "disabled">
                  </fieldset>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <fieldset>
                    <input name="subject" type="text" class="form-control" id="subject"  value=" Subject: <?php echo $subject; ?>" disabled = "disabled">
                  </fieldset>
                </div>
                <div class="col-lg-12">
                  <fieldset>
                    <textarea name="message" rows="6" class="form-control" id="message" value="Message: <?php echo $message; ?>"  disabled = "disabled"><?php echo $message; ?></textarea>
                  </fieldset>
                </div>
                <div class="col-lg-12">
                  <fieldset>
                      <button class="filled-button" onclick="location.href='message_list.php'">Back</button>
                      <button  class="filled-button" onclick="location.href='message_delete.php?id=<?=$id?>'" >Delete Message</button>
                  </fieldset>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>




<?php include "footer.php"?>
