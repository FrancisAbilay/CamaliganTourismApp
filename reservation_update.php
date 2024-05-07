<?php
// Initialize the session
session_start();
$_SESSION["Current_Page"] = "order";

  require_once("config.php");
  $db_handle = new DBController();
  $image = $msg = $id ="";
  $userid =$name = $booking_date = $address = $city = "";
  $province =$contact_no = $zipcode = $total = $is_paid= $is_completed ="";
  $is_admin = isset($_SESSION["is_admin"])? $_SESSION["is_admin"]:0;


  // Check if the id exists, for example message_view.php?id=1 will get the message with the id of 1
  if (isset($_GET['id'])) {
    $id =  $_GET['id'];

    $sql = "SELECT user_id, name, booking_date, address, city, province, contact_no, zipcode, total, is_paid, is_completed FROM tbl_order WHERE id = " .strval($id);

    if($stmt = mysqli_prepare($db_handle->conn, $sql)){
    // Attempt to execute the prepared statement
     if(mysqli_stmt_execute($stmt)){
         // Store result
         mysqli_stmt_store_result($stmt);
         // Check if username exists, if yes then verify password
        if(mysqli_stmt_num_rows($stmt) == 1){
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $userid, $name, $booking_date, $address, $city, $province, $contact_no, $zipcode, $total, $is_paid, $is_completed);
            if(mysqli_stmt_fetch($stmt)){
               mysqli_stmt_close($stmt);

             }

          }
          else{
            $msg ='Menu doesn\'t exist with that ID!';
          }

      }
    }


  } else {
      $msg ='No ID specified!';
  }

  if (!empty($_POST)) {
      // Post data not empty insert a new record
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
      $name = isset($_POST['name']) ? $_POST['name'] : '';
      $address = isset($_POST['address']) ? $_POST['address'] : '';
      $city = isset($_POST['city']) ? $_POST['city'] : '';
      $province = isset($_POST['province']) ? $_POST['province'] : '';
      $contact_no = isset($_POST['contact_no']) ? $_POST['contact_no'] : '';
      $is_paid = isset($_POST['is_paid']) ? $_POST['is_paid'] : '0';
      $is_completed = isset($_POST['is_completed']) ? $_POST['is_completed'] : '0';
      $zipcode= isset($_POST['zipcode']) ? $_POST['zipcode'] : '';
      if ($msg =="")
      {
        // Insert new record into the contacts table
        // Prepare an insert statement
        $sql = "UPDATE tbl_order set is_paid  = " .strval($is_paid) .",  is_completed = " .strval($is_completed) .", zipcode ='" .$zipcode ."', name ='" .$name ."', address ='" .$address ."', city ='" .$city ."', province ='" .$province ."', contact_no ='" .$contact_no ."'";
        $sql.=" WHERE ID = '" .strval($id) ."'";

        $msg = $sql;

        if($stmt = mysqli_prepare($db_handle->conn, $sql)){
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
               mysqli_stmt_close($stmt);
             }
        }
        $msg = 'Updated Successfully!';
      }
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

<div class="content update">
	<h4>Update Reservation Item</h4>
    <form action="reservation_update.php?id=<?=$id?>" method="post">
        <label for="ordernum">Booking Number</label>
        <label for="is_paid">Paid?</label>
        <input type="text" name="ordernum" id="ordernum" value="<?php echo Substr("CRPARK00000",0, -1*strlen(strval($id))) .strval($id);?>" disabled="disabled">
        <select id="is_paid" name="is_paid" style="width:100px ; height: 40px" <?php echo $is_admin==1?'':'disabled="disabled"';?>>
          <option value="1" <?php echo $is_paid==1?'Selected = "selected"':''; ?>>Yes</option>
          <option value="0" <?php echo $is_paid==0?'Selected = "selected"':'';  ?>>No</option>
        </select>
        <label for="image"></label>

        <label for="booking_date">Booking Date</label>
        <label for="is_completed">Completed?</label>
        <input type="text" name="booking_date" id="booking_date" value="<?php echo strval(date("Y-m-d",strtotime($booking_date))); ?>"  <?php echo $is_admin==1?'':'disabled="disabled"';?>>
        <select id="is_completed" name="is_completed" style="width:100px; height: 40px" <?php echo $is_admin==1?'':'disabled="disabled"';?>>
          <option value="1" <?php echo $is_completed==1?'Selected = "selected"':''; ?>>Yes</option>
          <option value="0" <?php echo $is_completed==0?'Selected = "selected"':'';  ?>>No</option>
          <option value="2" <?php echo $is_completed==2?'Selected = "selected"':'';  ?>>Cancelled</option>
        </select>   <label for="image"></label>


      <label for="name">Name</label>
      <label for="zipcode">Booking Status</label>
      <input type="text" name="name" id="name" value="<?php echo $name; ?>"  <?php echo $is_admin==1? 'disabled="disabled"' : '';?> >
      <input type="text" name="zipcode" id="zipcode" value="<?php echo $zipcode; ?>" <?php echo $is_admin==1?'':'disabled="disabled"';?>>

        <label for="address">Address</label>
        <label for="city">City</label>
        <input type="text" name="address" id="address" value="<?php echo $address; ?>" <?php echo $is_admin==1? 'disabled="disabled"' : '';?> >
        <input type="text" name="city" id="city" value="<?php echo $city; ?>" <?php echo $is_admin==1? 'disabled="disabled"' : '';?> >

        <label for="province">Province</label>
        <label for="contact_no">Contact No.</label>
        <input type="text" name="province" id="province" value="<?php echo $province; ?>" <?php echo $is_admin==1? 'disabled="disabled"' : '';?> >
        <input type="text" name="contact_no" id="contact_no" value="<?php echo $contact_no; ?>" <?php echo $is_admin==1? 'disabled="disabled"' : '';?> >


        <?php if ($is_admin ==1){?>
            <input  style="width:180px; text-align:center;" type="submit" value="Submit">
            <a style="width:180px; text-align:center;"  href="reservation_list.php" class="create-record">Reservation List</a>
        <?php }else{?>
            <input  style="width:180px; text-align:center;" type="submit" value="Submit">
            <a style="width:180px; text-align:center;" href="reservation_list.php" class="create-record">Reservation List</a>
        <?php }?>
      </form>
    <?php if ($msg): ?>
    <p style="color:red;"><?=$msg?></p>
    <?php endif; ?>
  </div>

  <div class="content read">
        <table>
          <thead>
              <tr>
                  <td>Menu Name</td>
                  <td>Code</td>
                  <td>Quantity</td>
                  <td>Unit Price (Php)</td>
                  <td>Price (Php)</td>
              </tr>
          </thead>
          <tbody>
        <?php
          // Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
            $sql ="SELECT a.*, b.name FROM tbl_order_item a LEFT JOIN tbl_menu_item b on a.menu_code = b.code WHERE a.order_id = " .$id;
            if(	$stmt = mysqli_query($db_handle->conn,$sql)){
                // Attempt to execute the prepared statement
                  $order_item[] = array();
                  // Fetch the records so we can display them in our template.
                while ($order_item = mysqli_fetch_assoc($stmt)) {
                  $item_price = $order_item["quantity"]*$order_item["unit_price"];

                  ?>
                      <tr>
                          <td><?=$order_item["name"]?></td>
                          <td><?=$order_item["menu_code"]?></td>
                          <td><?=$order_item["quantity"]?></td>
                          <td><?=number_format($order_item["unit_price"],2)?></td>
                          <td><?=number_format($item_price,2)?></td>
                      </tr>
                      <?php
                    }
                }
            ?>
            <tr>
              <td colspan ="3"> </td>
              <td style ="align:right"> Total:</td>
              <td style ="align:center"><?php echo "Php " .number_format($total,2); ?></td>
          </tbody>
        </table>
      </div>


<?php include "footer.php"?>
