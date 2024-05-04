<?php
$servername="localhost";
$username="root";
$password="Password1";
$dbname = "IS226DB";
$conn=mysqli_connect($servername,$username,$password,"$dbname");
if(!$conn){
	die('Could not Connect My Sql:' .mysql_error());
}

if(isset($_POST['submit']))
{
	$Name = $_POST['name'];
	$Email = $_POST['email'];
	$Contact = $_POST['contact'];
	$Message = $_POST['message'];
	$sql = "INSERT INTO contact_form_info (name, email, contact, message) VALUES ('$Name','$Email','$Contact','$Message')";
	if (mysqli_query($conn, $sql)) {
		echo "Thanks! We'll be in touch very soon.";
	} else {
		echo "Error: " . $sql . " " . mysqli_error($conn);
	}
	mysqli_close($conn);
}
?>
