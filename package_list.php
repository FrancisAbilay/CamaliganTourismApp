<?php
  // Initialize the session
  session_start();
  $_SESSION["Current_Page"] = "menu";

  require_once("config.php");
  $db_handle = new DBController();

  $page  = $records_per_page = $num_contacts = 0;


  $page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int)$_GET["page"] : 1;
  // Number of records to show on each page
  $records_per_page = 5;


  $sql = "SELECT * FROM tbl_menu_item";
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
           <h2>Package</h2>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>

<div id="topmargin"></div><div style="height:10px"></div>


<div class="content read">
<h4>Package List</h4>
<a href="package_create.php" class="create-record">Create Package Item</a>

<table>
      <thead>
          <tr>
              <td>#</td>
              <td>Name</td>
              <td>Code</td>
              <td>Description</td>
              <td>Image</td>
              <td>Price (Php)</td>
              <td>Active?</td>
              <td></td>
          </tr>
      </thead>
      <tbody>
          <?php
            // Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
              $sql ="SELECT * FROM tbl_menu_item ORDER BY id LIMIT ";
              $sql.=strval(($page-1)*$records_per_page) ." , " .strval($records_per_page);

              if(	$stmt = mysqli_query($db_handle->conn,$sql)){
                  // Attempt to execute the prepared statement
                    $menu_item[] = array();
                    // Fetch the records so we can display them in our template.
                  while ($menu_item = mysqli_fetch_assoc($stmt)) {
                    ?>
                        <tr>
                            <td><?=$menu_item["id"]?></td>
                            <td><?=$menu_item["name"]?></td>
                            <td><?=$menu_item["code"]?></td>
                            <td><?=$menu_item["description"]?></td>
                            <td><img src="<?php echo $menu_item["image"]; ?>" class="cart-item-image" /></td>
      										  <td><?=number_format($menu_item["price"],2)?></td>
                            <td><?=$menu_item["Active"] ==0? "No":"Yes";?></td>
                            <td class="actions">
                                <a href="package_update.php?id=<?=$menu_item["id"]?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
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
  <a href="package_list.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
<?php
  } ?>
  <?php if ($page*$records_per_page < $num_contacts): ?>
  <a href="package_list.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
  <?php endif; ?>
</div>
</div>

<?php include "footer.php"?>
