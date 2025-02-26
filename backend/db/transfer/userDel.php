<?php 
	include "./db_connection.php";
	$uname=$_POST["uname"];
$role = $_POST["role"];
	$sql="delete from users where role= '$role' and uname = '$uname' ";
	if($con->query($sql)){
		echo 0;
} else {
    echo 1;
}
?>