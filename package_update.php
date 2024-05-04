<?php
// Initialize the session
session_start();
$_SESSION["Current_Page"] = "menu";

  require_once("config.php");
  $db_handle = new DBController();
  $image = $msg = $id ="";
  $file_name =$name = $code = $description = $price = $active = "";

  // Check if the id exists, for example message_view.php?id=1 will get the message with the id of 1
  if (isset($_GET['id'])) {
    $id =  $_GET['id'];
    $sql = "SELECT name, code, description, price, active, image FROM tbl_menu_item WHERE id = ".$id;

    if($stmt = mysqli_prepare($db_handle->conn, $sql)){
    // Attempt to execute the prepared statement
     if(mysqli_stmt_execute($stmt)){
         // Store result
         mysqli_stmt_store_result($stmt);
         // Check if username exists, if yes then verify password
        if(mysqli_stmt_num_rows($stmt) == 1){
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $name, $code, $description, $price, $active, $file_name);
            if(mysqli_stmt_fetch($stmt)){
               mysqli_stmt_close($stmt);
              $image = $file_name;

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
      //$code= isset($_POST['code']) ? $_POST['code'] : '';
      $price= isset($_POST['price']) ? $_POST['price'] : '0';
      $active= isset($_POST['active']) ? $_POST['active'] : '0';


      if(isset($_FILES['image'])&& $_FILES['image']['name'] !=""){
            $errors= array();
            $file_name = $_FILES['image']['name'];
            $file_size =$_FILES['image']['size'];
            $file_tmp =$_FILES['image']['tmp_name'];
            $file_type=$_FILES['image']['type'];
            $file_ext=substr($_FILES['image']['name'],-3);

            $extensions= array("jpeg","jpg","png");

            if(in_array($file_ext,$extensions)=== false){
              $msg.="Extension not allowed, please choose a JPEG or PNG file.\n";
            }

            if($file_size > 2097152){
              $msg.='File size must be excately 2 MB ';
            }

            if(empty($errors)==true){
               move_uploaded_file($file_tmp,"asset/images/".$file_name);
               $image = "asset/images/".$file_name;
               $file_name =  $image;
            }else{
               print_r($errors);
                $msg .= "Error uploading file";
            }
         }

      if ($msg =="")
      {
        // Insert new record into the contacts table
        // Prepare an insert statement
        $sql = "UPDATE tbl_menu_item set name = '" .strval($name) ."',  active = '" .strval($active) ."',";
        $sql.=" price ='" .strval($price) ."',  image = '" .strval($image) ."'  description = '" .strval($description) ."";
        $sql.="  WHERE ID = '" .strval($id) ."'";

          //$msg .=$sql;
        if($stmt = mysqli_prepare($db_handle->conn, $sql)){
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
               mysqli_stmt_close($stmt);
             }
        }
        $msg .= 'Updated Successfully!';
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

<div id="topmargin"></div>
<div style="height:10px"></div>

<div class="content update">
  	<h4>Update Package Item</h4>
    <form action="package_update.php?id=<?=$id?>" method="post" enctype = "multipart/form-data">
        <label for="name">Name</label>
        <label for="active">Active</label>
        <input type="text" name="name" placeholder="MB Camaligan" id="name"  value="<?php echo $name; ?>" required>
        <select id="active" name="active" style="width:100px; height: 40px" >
          <option value="1" <?php echo $active==1?'Selected = "selected"':''; ?>>Yes</option>
          <option value="0" <?php echo $active==0?'Selected = "selected"':'';  ?>>No</option>
        </select>
        <label for="id"></label>

        <label for="id">Code</label>
        <label for="image"></label>
        <input type="text" name="code" placeholder="Lech01" id="code"  value="<?php echo $code; ?>" disabled ="disabled" required>
        <label for="image"></label>

        <label for="description">Description</label>
        <label for="image"></label>
        <input type="text" name="description" placeholder="Lech01" id="code"  value="<?php echo $description; ?>" required>
        <label for="image"></label>

        <label for="price">Price (Php)</label>
        <label for="image"></label>
        <input type="number" name="price" placeholder="0.00" id="price"  min="10" max="150000"  value="<?php echo $price; ?>" required>
        <label for="image"></label>

        <label for="image">Image (Please use 255x155 pixels)</label>
        <label for="image"></label>

        <input type="file" name="image" id="image"   value="<?php echo $file_name; ?>">
        <?php if ($file_name!=""){ ?>
            <img src="<?php echo $file_name; ?>" class="cart-item-image" />
        <?php } ?>
        <label for="image"></label>
        <input style="width:180px; text-align:center;" type="submit" value="Submit">
        <a  style="width:180px; text-align:center;" href="package_list.php" class="create-record">Menu List</a>

    </form>
    <?php if ($msg): ?>
    <p style="color:red;"><strong><?=$msg?></strong></p>
    <?php endif; ?>
</div>


<?php include "footer.php"?>
