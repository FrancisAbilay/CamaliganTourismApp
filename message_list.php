<?php
  // Initialize the session
  session_start();
  $_SESSION["Current_Page"] = "message";

  require_once("config.php");
  $db_handle = new DBController();

  $page  = $records_per_page = $num_messages = 0;


  $page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int)$_GET["page"] : 1;
  // Number of records to show on each page
  $records_per_page = 10;


  $sql = "SELECT * FROM tbl_contact_form_info";
  if($stmt = mysqli_prepare($db_handle->conn, $sql)){
      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          // Store result
          mysqli_stmt_store_result($stmt);
          $num_messages =   mysqli_stmt_num_rows($stmt);
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

<div id="topmargin">
</div>

  <div class="content read">
  <h4>Message List</h4>
  <table>
        <thead>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Email</td>
                <td>Subject</td>
                <td>Message</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
              // Prepare the SQL statement and get records from our messages table, LIMIT will determine the page
                $sql ="SELECT * FROM tbl_contact_form_info ORDER BY id desc LIMIT ";
                $sql.=strval(($page-1)*$records_per_page) ." , " .strval($records_per_page);

                if(	$stmt = mysqli_query($db_handle->conn,$sql)){
                    // Attempt to execute the prepared statement
                      $message_item[] = array();
                      // Fetch the records so we can display them in our template.
                    while ($message_item = mysqli_fetch_assoc($stmt)) {
                        $ordernum = "CRPARK0000".$message_item["id"];
                      ?>
                          <tr>
                              <td><?=$message_item["id"]?></td>
                              <td><?=$message_item["name"]?></td>
                              <td><?=$message_item["email"]?></td>
                              <td><?=$message_item["subject"]?></td>
                              <td><?=$message_item["message"]?></td>
                              <td class="actions">
                                  <a href="message_view.php?id=<?=$message_item["id"]?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                                  <a href="message_delete.php?id=<?=$message_item['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
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
    <a href="message_list.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
  <?php
    } ?>
    <?php if ($page*$records_per_page < $num_messages): ?>
    <a href="message_list.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
    <?php endif; ?>
  </div>
</div>

<?php include "footer.php"?>
