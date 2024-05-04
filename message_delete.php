<?php
  // Initialize the session
  session_start();
  $_SESSION["Current_Page"] = "message";

  require_once("config.php");
  $db_handle = new DBController();
  $num_messages = 0;
  $id = "";
  $msg = "";

// Check that the message ID exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $sql = "SELECT * FROM tbl_contact_form_info WHERE id = ".$_GET['id'];
    if($stmt = mysqli_prepare($db_handle->conn, $sql)){
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Store result
            mysqli_stmt_store_result($stmt);
            $num_messages =   mysqli_stmt_num_rows($stmt);
            mysqli_stmt_close($stmt);
        }
      }
      $id = $_GET['id'];
    if ($num_messages =0) {
      exit('Message doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
          $sql = "DELETE FROM tbl_contact_form_info WHERE id = ".$_GET['id'];
          if($stmt = mysqli_prepare($db_handle->conn, $sql)){
              // Attempt to execute the prepared statement
              if(mysqli_stmt_execute($stmt)){
                  // Store result
                //  $msg.= "You have deleted the message!";
                  mysqli_stmt_close($stmt);
                  header('Location: message_list.php');

              }
            }
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: message_list.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
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
    <div class="content delete">
    	<h3>Delete Message #<?=$id?></h3>
        <?php if ($msg==""){ ?>
            <p>Are you sure you want to delete message #<?=$id?>?</p>
          <div class="yesno">
              <a href="message_delete.php?id=<?=$id?>&confirm=yes">Yes</a>
              <a href="message_delete.php?id=<?=$id?>&confirm=no">No</a>
          </div>
        <?php}
        else{ ?>
          <div class="yesno">
          </div>

        <?php }?>

        <div class="content update">
          <form action="message_list.php" method="post">
            <input type="submit" value="Back">
          </form>
        </div>
    </div>
</div>

<?php include "footer.php"?>
