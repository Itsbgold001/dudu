<?php
$db_conx = mysqli_connect("localhost","db_user","db_password","db_name");
if(mysqli_connect_errno()){
	echo mysqli_connect_error();
	exit();
	}
?>
