<?php
	$mysqli = mysqli_connect("localhost", "elysia_pics", "ElysiaDatabase4Me!", "elysia_pics") or die("Die Datenbank existiert nicht.");
	if (mysqli_connect_errno($mysqli)) {
		echo "Failed to connect to database: " . mysqli_connect_error();
	}
?>