<?php
$mysqli = mysqli_connect("localhost", "root", "");

if (!$mysqli)
	echo "database not connected!";

	mysqli_select_db($mysqli, "rk");
	mysqli_query($mysqli, "SET NAMES utf8");
?>