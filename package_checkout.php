<?php
session_start();
$_SESSION["Current_Page"] = "checkout";

  $total_quantity = 0;
  $total_price = 0;
  $fname = $bookdate = $cphone = $user_id = "";
  $address = $city = $province = $zip="";
  $order_id = $order_id ="";
  $login_err = "";

  $curDateTime = date('Y-m-d', strtotime(date("Y-m-d H:i:s"))) ;
	$curMonth = date('Y-m-d', strtotime(date("Y-m-d H:i:s"). ' + 90 days'))  ;
  $dateMsg = "*All entries are mandatory.";

// Include config file

require_once "config.php";
$db_handle = new DBController();





  if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(!isset($_SESSION["cart_item"])){
      $login_err .=  " Please select item from the packages to reserve. <br>";

  }

    // Validate Delivery Date
     if(empty(trim($_SESSION["Book_Date"]))){
       $login_err .=  "Please enter Booking Date.<br>";
     } else{

         $bookdate = $_SESSION["Book_Date"];
         $bookdateformat =  date("m/d/Y",strtotime($bookdate));

         $test_arr  = explode('/', $bookdateformat);
         if (count($test_arr) == 3) {
           if (checkdate($test_arr[0], $test_arr[1], $test_arr[2])) {
               $myDate = date("Y-m-d H:i:s",strtotime($bookdate));
             if($myDate < $curDateTime || $myDate > $curMonth ){
                 $login_err .= "Date is not within the delivery date period: ".strval($curDateTime) ." to ".strval($curMonth) ." only. <br>";
             }
           } else {
             $login_err .=" Please enter a valid date. <br>";
           }
         } else {
           $login_err .= " Please enter a valid date. <br>";
         }

     }


    // Validate Full Name.
  if(empty(trim($_POST["fname"]))){
    $login_err .=  "Please enter Full Name.<br>";
  } else{
      $fname = trim($_POST["fname"]);
  }

  // Validate  booking Address
  if(empty(trim($_POST["address"]))){
    $login_err .=  "Please enter Address.<br>";
  } else{
        $address = trim($_POST["address"]);
  }

  // Validate booking City
  if(empty(trim($_POST["city"]))){
    $login_err .=  "Please enter  City/Town.<br>";
  } else{
      $city= trim($_POST["city"]);
  }

  // Validate province
  if(empty(trim($_POST["province"]))){
    $login_err .=  "Please enter Province.<br>";
  } else{
        $province = trim($_POST["province"]);
  }


  // Validate booking Phone num.
  if(empty(trim($_POST["cphone"]))){
    $login_err .=  "Please enter Contact Number.<br>";
  } else{
      $cphone= trim($_POST["cphone"]);
  }

  // Check input errors before inserting in database
  if(empty($login_err)){
      $user_id = $_SESSION["id"];
      // Prepare an insert statement
      $sql = "INSERT INTO tbl_order(user_id, name, booking_date, delivery_date, address, city, province, zipcode, contact_no, total) VALUES ";
      $sql.="('" .strval($user_id) ."','" .strval($fname)  ."','" .strval(date("Y-m-d",strtotime($bookdate)))."','" .strval(date("Y-m-d",strtotime($bookdate))) ."','" .strval($address) ."','" .strval($city)."','" .strval($province) ."','Pending','" .strval($cphone) ."','" .strval($_SESSION["Total"]) ."')";
        $login_err .=$sql;

      if($stmt = mysqli_prepare($db_handle->conn, $sql)){
          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
             mysqli_stmt_close($stmt);

             $user_id = $_SESSION["id"];

             $sql2 = "SELECT id FROM tbl_order WHERE user_id = " .$user_id ." ORDER BY created_date DESC LIMIT 1";
             if($stmt2 = mysqli_prepare($db_handle->conn, $sql2)){

              // mysqli_stmt_bind_param($stmt2, "s", $param_user_id2);
               // Set parameters
               //$param_user_id2 = $user_id;

               // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt2)){
                    // Store result
                    mysqli_stmt_store_result($stmt2);
                    // Check if username exists, if yes then verify password
                   if(mysqli_stmt_num_rows($stmt2) == 1){
                         // Bind result variables
                         mysqli_stmt_bind_result($stmt2, $order_id);

                         if(mysqli_stmt_fetch($stmt2)){
                            mysqli_stmt_close($stmt2);

                            //loop
                             $sql3 = "INSERT INTO tbl_order_item (order_id, menu_code, quantity, unit_price) VALUES ";

                            //inser items
                            foreach ($_SESSION["cart_item"] as $items){
                                $user_id = $_SESSION["id"];
                                $item_qty = $items["quantity"];
                                $item_code = $items["code"];
                                $item_price = $items["price"];

                                $sql3.="('" .strval($order_id) ."','" .strval($item_code) ."','" .strval($item_qty) ."','" .strval($item_price) ."')";

                                $sql3 .="," ;
                              }

                              $sql4 = substr($sql3, 0, -1);

                             if($stmt3 = mysqli_prepare($db_handle->conn, $sql4)){
                                 if(mysqli_stmt_execute($stmt3)){
                                    mysqli_stmt_close($stmt3);
                                    unset($_SESSION['cart_item']);
                                    $_SESSION["Book_Date"] = "";

                                      // Store data in session variables
                                      $_SESSION["order_id"] = Substr("CRPARK00000",0, -1*strlen($order_id)) .$order_id;
                                      $_SESSION["Book_Date"] ="";
                                      $_SESSION["DelName"] = "";
                                      $_SESSION["DelAddress"] = "";
                                      $_SESSION["Province"] = "";
                                      $_SESSION["DelContact"] = "";
                                      header("location: reservation_confirm.php");
                                      exit;
                                 } else{
                                       $login_err .= "Something went wrong in creating record. Please try again later.";
                                 }

                            }
                        }

                     }

                  }
             }

                $login_err .=  "Success!";
            } else{
                 $login_err .=  "Something went wrong in creating record. Please try again later.";
            }
          }
      // Close connection
      mysqli_close($db_handle->conn);
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

  <div id="topmargin"> </div> <div style="height:10px"></div>

    <div class="content read">
       <h4>Booking Cart</h4>
          <table>
           <thead>
               <tr>
                   <td>Package</td>
                   <td>Code</td>
                   <td>Quantity</td>
                   <td>Unit Price (Php)</td>
                   <td>Price (Php)</td>
               </tr>
           </thead>
           <tbody>
         <?php
         if(isset($_SESSION["cart_item"])){
             $total_quantity = 0;
             $total_price = 0;
           foreach ($_SESSION["cart_item"] as $item){
               $item_price = $item["quantity"]*$item["price"];
          ?>
               <tr>
                   <td><?=$item["name"]?></td>
                   <td><?=$item["code"]?></td>
                   <td><?=$item["quantity"]?></td>
                   <td><?="Php ".number_format($item["price"],2)?></td>
                   <td><?="Php ".number_format($item_price,2)?></td>
               </tr>
           <?php
           $total_quantity += $item["quantity"];
           $total_price += ($item["price"]*$item["quantity"]);
           $_SESSION["Total"] = $total_price;

         }?>
           <tr>
             <td colspan ="3"> </td>
             <td style ="align:right"> Total:</td>
             <td style ="align:center"><?php echo "Php " .number_format($total_price,2); ?></td>
           </tr>
         <?php
            }
             else {
             ?>
             <tr>
               <td colspan = "5" style="text-align:center;" > Shopping Cart is Empty </td>
            </tr>
             <?php
             }
           ?>
                 </tbody>
         </table>
  </div>

<div style="height:20px"></div>
  <div class="content update">
      <h4>Reservation Details</h4>
      <p style="color:green;"><b><?=$dateMsg?></b></p>
    <?php if ($login_err){ ?>
      <p style="color:red;"><b><?=$login_err?></b></p>
  <?php } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="fname">Name</label>
        <label for="bookdate">Booking Date</label>
        <input type="text" name="fname" id="fname" placeholder="Juan Dela Cruz" value="<?php echo $fname; ?>" required>
        <input type="date" name="bookdate" id="bookdate" placeholder="11/01/2020" value="<?php if(isset($_SESSION["Book_Date"])){ echo $_SESSION["Book_Date"]; } ?>" disabled="disabled">

        <label for="address">Address</label>
        <label for="city">City/Town</label>
        <input type="text" name="address" id="address" placeholder="111 Barlin St." value="<?php echo $address; ?>" required>
        <input type="text" name="city" id="city" placeholder="Naga City" value="<?php echo $city; ?>"required>

        <label for="province">Province</label>
        <label for="cphone">Contact No.</label>
        <input type="text" name="province" id="province"  placeholder="Cam Sur" value="<?php echo $province; ?>" required>
        <input type="text" name="cphone" id="cphone" placeholder="09109121111" value="<?php echo $cphone; ?>"required>


        <input type="submit" value="Submit Reservation" class="btnsubmit">

    </form>
  </div>


<?php include "footer.php"?>
