<?php
	// Initialize the session
	session_start();
	$_SESSION["Current_Page"] = "menu";
	require_once("config.php");
	$db_handle = new DBController();
	$bookdate = "" ;
	$msg="";
	$curDateTime = date('Y-m-d', strtotime(date("Y-m-d H:i:s"))) ;
	$curMonth = date('Y-m-d', strtotime(date("Y-m-d H:i:s"). ' + 90 days'))  ;



	if (!isset($_SESSION["menu_item_array"])){
			$_SESSION["menu_item_array"] = $db_handle->runQuery("SELECT * FROM tbl_menu_item WHERE ACTIVE = 1 ORDER BY id ASC");
	}


	if(!empty($_GET["action"])) {
	switch($_GET["action"]) {
		case "checkdate" :
			if(empty(trim($_POST["bookdate"]))){
				$msg .=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please enter Booking Date.<br>";
				$_SESSION["menu_item_array"] = $db_handle->runQuery("SELECT * FROM tbl_menu_item WHERE ACTIVE = 1 ORDER BY id ASC");
			}else{
					$bookdate = $_POST["bookdate"];
					$bookdateformat =  date("m/d/Y",strtotime($bookdate));

					$test_arr  = explode('/', $bookdateformat);
					if (count($test_arr) == 3) {
						if (checkdate($test_arr[0], $test_arr[1], $test_arr[2])) {
								$myDate = date("Y-m-d H:i:s",strtotime($bookdate));

									if($myDate < $curDateTime || $myDate > $curMonth ){
										$msg .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date is not within the reservation date period: ".strval($curDateTime) ." to ".strval($curMonth) ." only. <br>";
									}
							}	else { $msg .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please enter a valid date. <br>";}
						}	else { $msg .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please enter a valid date. <br>";	}

						if ($msg===""){
							if(!isset($_SESSION["Book_Date"])){
								$_SESSION["Book_Date"] =$bookdate;
							} else {
									if ($_SESSION["Book_Date"]!=$bookdate){
											unset($_SESSION["cart_item"]);
									}
									$_SESSION["Book_Date"] = $bookdate;
							}
				
							// --to remove check records
							$_SESSION["menu_item_array"] = $db_handle->runQuery("SELECT * FROM tbl_menu_item WHERE ACTIVE = 1 ORDER BY id ASC");

							$sql = "SELECT TI.menu_code, SUM(TI.quantity)  as QTY_BOOKED FROM tbl_order_item TI INNER JOIN tbl_order TBO ON TI.order_id = TBO.id
												WHERE TBO.booking_date ='".$bookdate."' GROUP BY TI.menu_code";

							$result= $db_handle->runQuery($sql);

							if(!empty($result)) {
									foreach($result as $k =>$v){
										$item_code_booked = $v["menu_code"];

										foreach($_SESSION["menu_item_array"] as $key =>$value) {
											$item_code_menu  = $value["code"];

											if ($item_code_booked ==="CRPBoatRent" && ($item_code_menu==="CRPBoatRent" || $item_code_menu==="CRPCruise")){
												unset($_SESSION["menu_item_array"][$key]);

											}

											if ($item_code_booked ==="CRPHallRent" && ($item_code_menu==="CRPHallRent" )) {
													unset( $_SESSION["menu_item_array"][$key]);


											}

											if ($item_code_booked ==="CRPCruise" && ($item_code_menu==="CRPBoatRent")) {
												unset($_SESSION["menu_item_array"][$key]);


											}

										}

									}
								}
							// --to remove check records


						} else{ $_SESSION["Book_Date"] ="";	}

				}
		case "add":
		  if(!isset($_SESSION["Book_Date"])){
				$msg = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*Please select for Booking Date and click on check availability button for available packages.";
			}	else{
				if(!empty($_POST["quantity"])) {
					$menu_itemByCode = $db_handle->runQuery("SELECT * FROM tbl_menu_item WHERE code='" . $_GET["code"] . "'");
					$itemArray = array($menu_itemByCode[0]["code"]=>array('name'=>$menu_itemByCode[0]["name"], 'code'=>$menu_itemByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$menu_itemByCode[0]["price"], 'image'=>$menu_itemByCode[0]["image"]));

					if(!empty($_SESSION["cart_item"])) {
						if(in_array($menu_itemByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
							foreach($_SESSION["cart_item"] as $k => $v) {
									if($menu_itemByCode[0]["code"] == $k) {
										if(empty($_SESSION["cart_item"][$k]["quantity"])) {
											$_SESSION["cart_item"][$k]["quantity"] = 0;
										}
										if ($_SESSION["cart_item"][$k]["name"]=="Open Hall Rental" || $_SESSION["cart_item"][$k]["name"]=="Boat Venue Rental" ) {
											$_SESSION["cart_item"][$k]["quantity"]  = 1;
										} else{
											$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
										}

									}
							}
						} else {
							$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
						}
					} else {
						$_SESSION["cart_item"] = $itemArray;
					}
				}
			}
		break;
		case "remove":
			if(!empty($_SESSION["cart_item"])) {
				foreach($_SESSION["cart_item"] as $k => $v) {
						if($_GET["code"] == $k)
							unset($_SESSION["cart_item"][$k]);
						if(empty($_SESSION["cart_item"]))
							unset($_SESSION["cart_item"]);
				}
			}
		break;
		case "empty":
			unset($_SESSION["cart_item"]);
		break;
		}
	}
	else {
			if($_SERVER["REQUEST_METHOD"] == "POST"){

				if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

					if(empty(trim($_SESSION["Book_Date"]))){
							$msg .=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please enter Booking Date.<br>";
						}else{
							$bookdate = $_SESSION["Book_Date"];
							$bookdateformat =  date("m/d/Y",strtotime($bookdate));

							$test_arr  = explode('/', $bookdateformat);
							if (count($test_arr) == 3) {
								if (checkdate($test_arr[0], $test_arr[1], $test_arr[2])) {
										$myDate = date("Y-m-d H:i:s",strtotime($bookdate));

											if($myDate < $curDateTime || $myDate > $curMonth ){
												$msg .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date is not within the reservation date period: ".strval($curDateTime) ." to ".strval($curMonth) ." only. <br>";
											}
									}	else { $msg .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please enter a valid date. <br>";}
								}	else { $msg .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please enter a valid date. <br>";	}
							}
							if ($msg===""){
									header("location: package_checkout.php");
							}
					}
					else{
									header("location: login.php");

				}
				exit;
			}
		}

?>
<?php include "header.php"?>

 <!-- Page Content -->
 <div style = "display:none">
 <div class="page-heading contact-heading header-text" style="background-image: url(asset/images/topviewed.jpg);">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
         <div class="text-content">
           <h2>Book Now</h2>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>

		<div id="topmargin"></div><div style="height:10px"></div>



		<form method="post" action="package_booking.php?action=checkdate">
				<a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Booking Date: <input type="date" name="bookdate"  value="<?php if(isset($_SESSION["Book_Date"])){ echo $_SESSION["Book_Date"]; } ?>" required>
						<input type="submit" value="Check Availability" class="btnsubmit" />
						<?php if ($msg): ?>
			      <p style="color:red;"><strong><?=$msg?></strong></p>
			      <?php endif; ?>
				</a>
		</form>

			<table>
					<tr>
						<td>
					<div id="menu_item-grid">
						<div class="txt-heading_menu"><a>Packages</a></div>
						<?php
						if (!empty($_SESSION["menu_item_array"])) {
							foreach($_SESSION["menu_item_array"] as $key=>$value){
						?>
							<div class="menu_item-item">
								<form method="post" action="package_booking.php?action=add&code=<?php echo $_SESSION["menu_item_array"][$key]["code"]; ?>">
								<div class="menu_item-image"  style="display: block;  margin-left: auto;  margin-right: auto;"><img src="<?php echo $_SESSION["menu_item_array"][$key]["image"]; ?>"></div>
								<div class="menu_item-tile-footer">
								<div class="menu_item-title"><strong><?php echo $_SESSION["menu_item_array"][$key]["name"]; ?></strong></div>
								<div class="menu_item-desc"><a><?php echo $_SESSION["menu_item_array"][$key]["description"]; ?></a></div>
								<div class="menu_item-price"><?php echo "Php ".$_SESSION["menu_item_array"][$key]["price"]; ?></div>
								<div class="cart-action">
									&nbsp;<input type="text" class="menu_item-quantity" name="quantity" value="1" size="2"/>
									&nbsp;<input type="submit" value="Add" class="btnAddAction" /></div>
								</div>
								<div>&nbsp;</div>
								</form>
							</div>
						<?php
							}
						}
						?>
					</div>
				</td>
			</tr>
		</table>

		</div>

		<div id="shopping-cart">
			<div class="txt-heading_menu">Booking Cart</div>

				<a id="btnEmpty" href="package_booking.php?action=empty">Empty Cart</a>
				<?php
				if(isset($_SESSION["cart_item"])){
				    $total_quantity = 0;
				    $total_price = 0;
				?>
				<table class="tbl-cart" cellpadding="10" cellspacing="1">
					<tbody>
						<tr>
						<th style="text-align:left;" width="30%" >Name</th>
						<th style="text-align:left;" width="20%" >Code</th>
						<th style="text-align:right;" width="5%">Quantity</th>
						<th style="text-align:right;" width="20%">Unit Price</th>
						<th style="text-align:right;" width="20%">Price</th>
						<th style="text-align:center;" width="5%">Remove</th>
						</tr>
						<?php
						    foreach ($_SESSION["cart_item"] as $item){
						        $item_price = $item["quantity"]*$item["price"];
								?>
										<tr>
											<td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
											<td><?php echo $item["code"]; ?></td>
											<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
											<td style="text-align:right;"><?php echo "Php ".$item["price"]; ?></td>
											<td  style="text-align:right;"><?php echo "Php ". number_format($item_price,2); ?></td>
											<td style="text-align:center;"><a href="package_booking.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="asset\images\icon-delete.png" alt="Remove Item" /></a></td>
										</tr>
										<?php
										$total_quantity += $item["quantity"];
										$total_price += ($item["price"]*$item["quantity"]);
								}
								?>

						<tr>
							<td colspan="4" align="right"><strong>Total:</strong></td>
								<td align="right" colspan="1"><strong><?php echo "Php ".number_format($total_price, 2); ?></strong></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="6" align="right">
							</td>
						</tr>
					</tbody>
				</table>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<input type="submit" name="add_to_cart"  value="Submit Order" class="btnsubmit" />
				</form>

				  <?php
				} else {
				?>
				<div class="no-records">Your Cart is Empty</div>
				<?php
				}
				?>


	<?php include "footer.php"?>
