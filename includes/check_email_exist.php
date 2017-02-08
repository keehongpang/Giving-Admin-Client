<?php
include_once 'db_connect.php';

if (isset($_GET['email'])) {
	$email = $_GET['email'];
	
    // Connect to the EE DB 
    $mysqli = new mysqli (EE_DB_HOST, EE_DB_USER, EE_DB_PASS, EE_DB_NAME);

    // Check connection 
    if (mysqli_connect_errno()) {
		die('Error - Could not connect: ' . mysqli_connect_error());
    }


    // Build a query to retrieve an user account by email address
	$sql = "SELECT member_id FROM exp_members WHERE email='" . $email . "'";

	// Error handling for query executing
    if (($result=$mysqli->query($sql)) === FALSE) {
		die('Error - Query: ' . $mysqli->error);
	}
	
	$number	= $result->num_rows;
	// Found account by searching for email address
	if ($number > 0)
	{
		echo "Exist";
	}
	// Email not found
	else
	{
		echo "NotExist";
	}
	
	// Free result set
	$result->free();
	$mysqli->close();
	die();
}
else {
	echo "Error - Invalid input. E-mail address must be set.";
	die();
}



?>