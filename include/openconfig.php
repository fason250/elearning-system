<?php 
	

if(preg_match("/config.php/",$_SERVER['SCRIPT_FILENAME'])){
	die("Access denied: Please away from here.");
}

	$connection = mysqli_connect('localhost','root','','intellistudy') or die("Database Not connected".mysqli_connect_error());
	
 ?>