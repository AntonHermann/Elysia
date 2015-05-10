<?php
	session_start();
	include 'settings.php';
	
	$ajax = false;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		//Ajax-Request
		$ajax = true;
	}
	
	unset($_SESSION['name']);
	unset($_SESSION['password']);
	unset($_SESSION['error']);
	$_SESSION = array();
	session_destroy();
	if ($ajax) {
		echo "t"; //true
	} else {
		header("Location: ../index.php");
		exit();
	}
?>