<?php
  // Initialize the session
  session_start();
  $_SESSION["Current_Page"] = "order";

  require_once("config.php");
  $db_handle = new DBController();
  // Check if the user is already logged in, if yes then redirect him to welcome page
  $is_admin = "";
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $is_admin =  $_SESSION["is_admin"];
  }

  $page  = $records_per_page = $num_contacts = 0;


  $page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int)$_GET["page"] : 1;
  // Number of records to show on each page
  $records_per_page = 10;

  if($is_admin=== 1){
      $sql = "SELECT * FROM tbl_order WHERE is_completed = 0";
  }
  else{
      $tmpid = isset($_SESSION["id"])? strval($_SESSION["id"]):"0";
      $sql =" SELECT * FROM tbl_order WHERE user_id = "  .$tmpid;
  }

  if($stmt = mysqli_prepare($db_handle->conn, $sql)){
      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          // Store result
          mysqli_stmt_store_result($stmt);
          $num_contacts =   mysqli_stmt_num_rows($stmt);
          mysqli_stmt_close($stmt);
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
<div class="content read">
  <h4>Reservation List</h4>
  <table>
        <thead>
            <tr>
                <td>#</td>
                <td>Order Number</td>
                <td>Booking Date</td>
                <td>Address</td>
                <td>Total (Php)</td>
                <td>Paid?</td>
                <td>Completed?</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $is_admin = isset($_SESSION["is_admin"])? $_SESSION["is_admin"]:0;

            if($is_admin=== 1){
              $sql ="SELECT * FROM tbl_order WHERE is_completed = 0 ORDER BY booking_date LIMIT ";
              $sql.=strval(($page-1)*$records_per_page) ." , " .strval($records_per_page);

            }
            else{
                $tmpid = isset($_SESSION["id"])? strval($_SESSION["id"]):"0";
                $sql =" SELECT * FROM tbl_order WHERE user_id = "  .$tmpid;
                $sql.=" ORDER BY id DESC LIMIT ";
                $sql.=strval(($page-1)*$records_per_page) ." , " .strval($records_per_page);

            }

            // Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
            //  $sql ="SELECT * FROM tbl_order WHERE is_completed = 0 ORDER BY id LIMIT ";
            //  $sql.=strval(($page-1)*$records_per_page) ." , " .strval($records_per_page);

                if(	$stmt = mysqli_query($db_handle->conn,$sql)){
                    // Attempt to execute the prepared statement
                      $order_item[] = array();
                      // Fetch the records so we can display them in our template.
                    while ($order_item = mysqli_fetch_assoc($stmt)) {
                        $ordernum = Substr("CRPARK00000", 0, -1*strlen($order_item["id"])) .$order_item["id"];
                      ?>
                          <tr>
                              <td><?=$order_item["id"]?></td>
                              <td><?=$ordernum?></td>
                              <td><?=strval(date("Y-m-d",strtotime($order_item["booking_date"])))?></td>
                              <td><?=$order_item["address"]?></td>
                              <td><?=number_format($order_item["total"],2)?></td>
                              <td><?=$order_item["is_paid"] ==0? "No":"Yes";?></td>
                              <td><?=$order_item["is_completed"] ==0? "No":"Yes";?></td>
                              <td class="actions">
                                  <a href="reservation_update.php?id=<?=$order_item["id"]?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                              </td>
                          </tr>
                          <?php
                        }
                    }
                ?>
        </tbody>
    </table>
    <div class="pagination">
      <?php if ($page > 1){ ?>
      <a href="reservation_list.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
    <?php
      } ?>
      <?php if ($page*$records_per_page < $num_contacts): ?>
      <a href="reservation_list.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
      <?php endif; ?>
    </div>
</div>

<?php include "footer.php"?>
