<?php 
	include "./db_connection.php";
	$ad=$_POST["admin"];
	$sql="delete from passedOut where sno= {$ad}";
	if($con->query($sql)){
		echo 0;
	}else{
		echo 1;
	}
?>