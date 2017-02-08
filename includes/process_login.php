<?php
include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); 					// Our custom secure way of starting a PHP session.
 
if (isset($_POST['emaillogin'], $_POST['p'])) {
	$email 		= $_POST['emaillogin'];
	$password 	= $_POST['p']; 					// The hashed password.

	if (login($email, $password, $mysqli) == true) {
		// Login success 
		header('Location: ../recurring.php');
	} else {
		// Login failed 
		header('Location: ../index.php?error=Login failed');
	}
} else {
	// The correct POST variables were not sent to this page. 
	echo 'Invalid Request - POST is empty!';
}

