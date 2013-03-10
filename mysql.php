<?php
  	// Database info and connection
  	$DBhost = "localhost";
	$DBuser = "root";
	$DBpass = "root";
	$DBName = "root_trips";
	$link = mysqli_connect($DBhost, $DBuser, $DBpass, $DBName);

	if (!$link) {
		die('Connect Error (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
	}
	echo 'Success... ' . mysqli_get_host_info($link) . "<br />";
?>