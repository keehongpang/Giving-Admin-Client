<?php
include_once 'db_connect.php';
require_once '/var/www/html/giving/mail/swiftmailer/swiftmailer-5.x/lib/swift_required.php';
require_once '/var/www/html/giving/mail/config.php';

if (isset($_GET['email'])) 
{
	$email = $_GET['email'];
	
    // Connect to the EE DB 
    $mysqli = new mysqli(EE_DB_HOST, EE_DB_USER, EE_DB_PASS, EE_DB_NAME);

    // Check connection 
    if (mysqli_connect_errno()) {
		die('Could not connect: ' . mysqli_connect_error());
    }

	// Generate random strings 
	function get_random_string($valid_chars, $length) {
		$random_string = "";		
		$num_valid_chars = strlen($valid_chars);
	
		for ($i = 0; $i < $length; $i++) {
			$random_pick = mt_rand(1, $num_valid_chars);
			$random_char = $valid_chars[$random_pick-1];
			$random_string .= $random_char;
		}
	
		return $random_string;
	}

	// Reset password
	$temp_password = get_random_string("abcdefghijklmnopqrstuvwxyz0123456789", 6);
	// Hash password to store in the database
	$sha1_password = sha1($temp_password);
	

    // Build a query to retrieve an user account by email address
	$sql = "SELECT member_id FROM exp_members WHERE email='" . $email . "'";

	// Error handling for query executing
    if (($result=$mysqli->query($sql)) === FALSE) {
		die('Error in Query: ' . $mysqli->error);
	}
	
	$number	= $result->num_rows;
	// Found account by searching for email address
	if ($number > 0)
	{
		if ($result->num_rows < 10) 
		{
			// Iterate to update the reset password 
			// Fetch associative array
			while ($post = $result->fetch_assoc()) 
			{
				// Build a query to update the reset password in the user account
				$sql = "UPDATE exp_members SET password='" . $sha1_password. "' WHERE member_id = " . $post['member_id'];
				
				// Error handling for query executing
				if ($mysqli->query($sql) === FALSE) 
				{
					echo 'Error in Query: ' . $mysql->error;
					$mysqli->close();
					die();
				}
			}
		}
		// Precaution to make sure entire database isn't somehow reset
		else if ($result->num_rows > 9)
		{
			echo $result->num_rows . " entries found containing this e-mail. Stopping as a precaution, please contact the administrator.";
			// Free result set
			$result->free();
			$mysqli->close();
			die();
		}
	}
	// Email not found
	else
	{
		echo $email . " was not found in the system. Please register your email. ";
		// Free result set
		$result->free();
		$mysqli->close();
		die();
	}

	// Send email to inform the reset password
	SendPasswordResetEmail($email, $temp_password);
}
else {
	echo "Invalid input. E-mail address must be set.";
	die();
}


//////////////////////////////////////////////////////////////////////////
//			Send password reset email via Swiftmailer					//
//////////////////////////////////////////////////////////////////////////
function SendPasswordResetEmail($mailto, $resetpw) 
{
	// Create the Transport
	$transport = Swift_SmtpTransport::newInstance(EMAILSERVER, EMAILPORT, 'tls')
		->setUsername(EMAILUSER)
		->setPassword(EMAILPASSWORD);
		
	// Create the Mailer using your created Transport
	$mailer = Swift_Mailer::newInstance($transport);

	try {
		// Create a message
		$message = Swift_Message::newInstance(PWRESETSUBJECT)
			->setFrom(GIVINGEMAIL)
			->setTo($mailto);
	} catch (Swift_RfcComplianceException $SRe) {
		echo $SRe->getMessage() . PHP_EOL;
		return false;
	}

	$message->setBody(returnHtmlBody($mailto, $resetpw), 'text/html');

	// Add alternative parts with addPart()
	$message->addPart(returnTextBody($mailto, $resetpw), 'text/plain');

	try {
		// Send the message
		$result = $mailer->send($message);
		 echo "The account for " . $mailto . " has had their password successfully reset.<BR />" 
			. "Please check your e-mail for login instructions." ;
	} catch	(Swift_TransportException $STe) {
		echo 'The password was successfully reset for the account with e-mail address ' . $mailto 
			. ', however there seems to be an issue sending out an e-mail.<BR />' 
			. 'Error message: ' . $STe->getMessage() . ' <BR />'
			. 'Please contact <a href="mailto:giving@northlandchurch.net">Administrator</a> with the error message.';
		return false;
	} catch (Exception $e) {
		echo 'The password was successfully reset for the account with e-mail address ' . $mailto 
			. ', however there seems to be an issue sending out an e-mail.<BR />' 
			. 'Error message: ' . $e->getMessage() . ' <BR />'
			. 'Please contact <a href="mailto:giving@northlandchurch.net">Administrator</a> with the error message.';
		return false;
	}
	
}

//		http://www.northlandchurch.net/reset/" . $email . "/" . $password . "

function returnHtmlBody($email, $password)
{
	$htmlBody = "						
		<p style='font-family:Calibri;'>
		We just had a request to reset your password at northlandchurch.net.
		<br><br>
		
		To keep your information secure, we don't email passwords. Instead we give you a one-time link that allows you to set a new password.
		So, to set your new password, click:
		<a href='http://www.northlandchurch.net/reset/" . $email . "/" . $password . "'>http://www.northlandchurch.net/reset/" . $email . "/" . $password . "</a>
		<br><br>

		Thanks for being a part of Northland! Don't forget your account makes it easy to share prayer requests, take notes on recent sermons and participate in online worship. 
		Once you reset your password, why not check out one of the following links:
		<br><br>

		<a href='http://pray.northlandchurch.net'>http://pray.northlandchurch.net</a> - Pray	<br>
		<a href='http://northlandchurch.net/resources'>http://northlandchurch.net/resources</a> - Previous Sermons	<br>
		<a href='http://northlandchurch.net/worship'>http://northlandchurch.net/worship</a> - Join Live Worship	
		<br><br>

		Nathan Clark	<br>
		Online Minister	<br>
		Director of Digital Innovation	<br>
		Northland, a Church Distributed	<br>
		<a href='http://northlandchurch.net'>http://northlandchurch.net</a>	<br>
		407-949-4016		<br>
		</p>
	";

	return $htmlBody;
}

function returnTextBody($email, $password)
{
	$textBody = "
		We just had a request to reset your password at northlandchurch.net.

		To keep your information secure, we don't email passwords. Instead we give you a one-time link that allows you to set a new password.
		So, to set your new password, click:
		http://www.northlandchurch.net/reset/" . $email . "/" . $password . "


		Thanks for being a part of Northland! Don't forget your account makes it easy to share prayer requests, take notes on recent sermons and participate in online worship. 
		Once you reset your password, why not check out one of the following links:

		http://pray.northlandchurch.net - Pray
		http://northlandchurch.net/resources - Previous Sermons
		http://northlandchurch.net/worship - Join Live Worship

		Nathan Clark
		Online Minister
		Director of Digital Innovation
		Northland, a Church Distributed
		http://northlandchurch.net
		407-949-4016	
	
	";
	
	return $textBody;
}


?>