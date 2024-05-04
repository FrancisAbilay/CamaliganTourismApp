<?php
session_start();
$server= "localhost";
$user = "root";
$password = "";
$db = "IS226DB";
// Create connection
$conn = new mysqli($server, $user, $password, $db);
// Check connection
if ($conn->connect_error)
{
	die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['save_changes']))
{
	$id = $_POST['id'];
	$name = $_POST['name'];
	$code = $_POST['code'];
	$price = $_POST['price'];
	$Active = $_POST['Active'];
	$sql = "UPDATE tbl_menu_item SET name='$name',code='$code',price='$price',Active='$Active' WHERE id='$id'";

	if ($conn->query($sql) === TRUE)
	{
		$status_message = "Changes saved.";
		header("location: update_package_booking.php");
	}
	else
	{
		$status_message = "Oops! Something went wrong with the update :(";
	}
	$conn->close();
}
else if(isset($_POST['add_menu_item']))
{
	$id = $_POST['id'];
	$name = $_POST['name'];
	$code = $_POST['code'];
	$price = $_POST['price'];
	$Active = $_POST['Active'];
	$sql = "INSERT INTO tbl_menu_item (id, name, code, image, price, Active) VALUES ('$id','$name','$code',' ','$price','$Active')";

	if ($conn->query($sql) === TRUE)
	{
		$status_message = "Changes saved.";
		header("location: update_package_booking.php");
	}
	else
	{
		$status_message = "Oops! Something went wrong in adding new menu_item :(";
	}
	$conn->close();
}
?>

	<?php include "header.php"?>

		<link href="css\menu.css" type="text/css" rel="stylesheet" />
	</HEAD>
	<BODY>
		<table>
			<tr>
				<td>
					<div id="menu_item-grid">
						<div class="txt-heading">menu_items</div>
						<?php
						$menu_item_array = $conn->query("SELECT * FROM tbl_menu_item ORDER BY id ASC");
						if ($menu_item_array->num_rows > 0) {
							while($row = $menu_item_array->fetch_assoc()) {
						?>
							<div class="menu_item-item">
								<form method="post" action="">
									<div>id: <input type="text" name="id" value="<?php echo $row["id"]; ?>" readonly/></div>
									<div>name: <input type="text" name="name" value="<?php echo $row["name"]; ?>"></div>
									<div>code: <input type="text" name="code" value="<?php echo $row["code"]; ?>"></div>
									<div>price: <input type="text" name="price" value="<?php echo $row["price"]; ?>"></div>
									<div>Active: <input type="text" name="Active" value="<?php echo $row["Active"]; ?>"></div>
									<div class="menu_item-image"><img src="<?php echo $row["image"]; ?>"></div>
									<div class="cart-action"><input type="submit" name="save_changes" value="save changes" class="btnAddAction"/></div>
								</form>
							</div>
						<?php
							}
						}
						?>
							<div class="menu_item-item">
								<form method="post" action="">
									<div>id: <input type="text" name="id" value=" "/></div>
									<div>name: <input type="text" name="name" value=" "></div>
									<div>code: <input type="text" name="code" value=" "></div>
									<div>price: <input type="text" name="price" value=" "></div>
									<div>Active: <input type="text" name="Active" value=" "></div>
									<div class="menu_item-image"><img src="images\no image.jpg" width="150"></div>
									<div class="cart-action"><input type="submit" name="add_menu_item" value="add menu_item" class="btnAddAction"/></div>
								</form>
							</div>
							<?php echo $status_message;?>
						<?php echo $status_message;?>
					</div>
				</td>
			</tr>
		</table>
	<?php include "footer.php"?>
