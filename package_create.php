<?php
  // Initialize the session
  session_start();
  $_SESSION["Current_Page"] = "menu";

  require_once("config.php");
  $db_handle = new DBController();
  $image = $msg = "";
  $file_name =$name = $code = $description = $price = $active = "";


  if (!empty($_POST)) {
      // Post data not empty insert a new record
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
      $name = isset($_POST['name']) ? $_POST['name'] : '';
      $code= isset($_POST['code']) ? $_POST['code'] : '';
      $description= isset($_POST['description']) ? $_POST['description'] : '';
      $price= isset($_POST['price']) ? $_POST['price'] : '';
      $active= isset($_POST['active']) ? $_POST['active'] : '0';


      if(isset($_FILES['image'])){
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
            }else{
               print_r($errors);

               $msg .= "Error uploading file";
            }
         }
        else {
          // code...
          $msg .= "Please select file to upload.";
        }

      if ($msg =="")
      {
          // Insert new record into the contacts table
        // Prepare an insert statement
        $sql = "INSERT INTO tbl_menu_item(name, code, description, image, price, active) VALUES ";
        $sql.="('" .strval($name) ."','" .strval($code) ."','" .strval($description)  ."','" .strval($image) ."','" .strval($price) ."','" .strval($active)."')";

        //echo "test1".$sql;

        if($stmt = mysqli_prepare($db_handle->conn, $sql)){
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
               mysqli_stmt_close($stmt);
               }
        }
        $msg = 'Created Successfully!';
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

<div class="content update">

	<h4>Create Package Item</h4>
    <p style="color:green;"><b>**All entries are mandatory.</b></p>
    <?php if ($msg){ ?>
      <p style="color:red;"><b><?=$msg?></b></p>
    <?php } ?>
    <form action="package_create.php" method="post" enctype = "multipart/form-data">
        <label for="name">Name</label>
        <label for="active">Active</label>
        <input type="text" name="name" placeholder="MB Camaligan" id="name"  value="<?php echo $name; ?>" required>
        <input type="checkbox" name="active" value="1" id="active" style="align:left; width:100px;" value="<?php echo $active; ?>" checked>
        <label for="image"></label>

        <label for="id">Code</label>
        <label for="image"></label>
        <input type="text" name="code" placeholder="Lech01" id="code"  value="<?php echo $code; ?>" required>
        <label for="image"></label>

        <label for="description">Short Description</label>
        <label for="image"></label>
        <input type="textarea" name="code" placeholder="Description" id="code"  value="<?php echo $description; ?>" required>
        <label for="image"></label>

        <label for="price">Price</label>
        <label for="image"></label>
        <input type="number" name="price" placeholder="0.00" id="price"  min="10" max="15000"  value="<?php echo $price; ?>" required>
        <label for="image"></label>

        <label for="image">Image (Please use 255x155 pixels)</label>
        <label for="image"></label>
        <input type="file" name="image" id="image"   value="<?php echo $file_name; ?>" required>
        <?php if ($image!=""){ ?>
            <img src="<?php echo $image; ?>" class="cart-item-image" />
        <?php }?>
        <label for="image"></label>

        <input type="submit" value="Create">
        <a href="package_list.php" class="create-record">Package List</a>

    </form>
      <?php if ($msg): ?>
      <p style="color:red;"><strong><?=$msg?></strong></p>
      <?php endif; ?>

</div>


<?php include "footer.php"?>
