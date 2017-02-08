<?php
include_once 'config.php';

//////////////////////////////////////////////////////////////////////////////////////////////
// 		Start a PHP session in a secure way.
// 		You should call this function at the top of any page in which you wish to access a PHP session variable. 
//////////////////////////////////////////////////////////////////////////////////////////////
function sec_session_start() {
	$session_name = 'sec_session_id';   // Set a custom session name
	$secure = SECURE;
	// This stops JavaScript being able to access the session id.
	$httponly = true;

	// Forces sessions to only use cookies.
	if (ini_set('session.use_only_cookies', 1) === FALSE) {
		header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
		exit();
	}

	// Gets current cookies params.
	$cookieParams = session_get_cookie_params();
	session_set_cookie_params($cookieParams["lifetime"],
							$cookieParams["path"], 
							$cookieParams["domain"], 
							$secure,
							$httponly);

	// Sets the session name to the one set above.
	session_name($session_name);
	session_start();            // Start the PHP session 
	
	// regenerated the session, delete the old one.  
	// helps prevent session hijacking.
	session_regenerate_id();    
}


//////////////////////////////////////////////////////////////////////////////////////////////
// 		Check the email and password against the database.
// 		Return true if there is a match. 
//////////////////////////////////////////////////////////////////////////////////////////////
function login($email, $password, $mysqli) {
	// Using prepared statements means that SQL injection is not possible. 
	$query 	= "SELECT member_id, password, salt, screen_name, group_id
			FROM exp_members
			WHERE email = ? LIMIT 1";
	if ($stmt = $mysqli->prepare($query)) 
	{
		$stmt->bind_param('s', $email);  	// Bind "$email" to parameter.
		$stmt->execute();    				// Execute the prepared query.
		$stmt->store_result();

		// get variables from result.
		$stmt->bind_result($user_id, $db_password, $salt, $screen_name, $group_id);
		$stmt->fetch();
		
		// hash the password with the unique salt.
		// This $salt.$password exactly matches with EE password algorithm.
		$password = hash('sha512', $salt.$password);

		if ($stmt->num_rows > 0) {
			// If the user exists we check if the account is locked
			// from too many login attempts 
			if (checkbrute($user_id, $mysqli) == true) {
				// TO DO::::::::::::::::
				// Account is locked 
				// Send an email to user saying their account is locked
				return false;
			} else {
				// Check if the password in the database matches with the password the user submitted.
				if ($db_password == $password) {
					// Password is correct!
					// XSS protection as we might print this value
					$user_id 				= preg_replace("/[^0-9]+/", "", $user_id);
					$_SESSION['user_id'] 	= $user_id;

					// Get the user-agent string of the user.
					$user_browser 				= $_SERVER['HTTP_USER_AGENT'];
					$_SESSION['login_string'] 	= hash('sha512', $password . $user_browser);
					$_SESSION['screen_name'] 	= $screen_name;
					$_SESSION['email'] 			= $email;
					if ($group_id == 25)
						$_SESSION['role'] 			= 'Manager';
					else if ($group_id == 26)
						$_SESSION['role'] 			= 'Admin';
					else if ($group_id == 1)
						$_SESSION['role'] 			= 'Super Admin';
					else
						$_SESSION['role'] 			= 'User';

					// Login successful.
					return true;
				} else {
					// Password is not correct
					// We record this attempt in the database
//					$now = time();
//					$mysqli->query("INSERT INTO login_attempts(user_id, time) VALUES ('$user_id', '$now')");
					return false;
				}
			}
		} else {
			// No user exists.
			return false;
		}

	}
}


 
//////////////////////////////////////////////////////////////////////////////////////////////
// 		If a user account has more than five failed logins their account is locked.
//////////////////////////////////////////////////////////////////////////////////////////////
function checkbrute($user_id, $mysqli) {
	// Get timestamp of current time 
	$now = time();

	// All login attempts are counted from the past 2 hours. 
	$valid_attempts = $now - (2 * 60 * 60);
/*
	if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) 
	{
		$stmt->bind_param('i', $user_id);

		// Execute the prepared query. 
		$stmt->execute();
		$stmt->store_result();

		// If there have been more than 5 failed logins 
		if ($stmt->num_rows > 5) {
			return true;
		} else {
			return false;
		}
	}
*/
	return false;
}


//////////////////////////////////////////////////////////////////////////////////////////////
// 		Check logged in status.
//		Doing this helps prevent session hijacking.
//////////////////////////////////////////////////////////////////////////////////////////////
function login_check($mysqli) {
	// Check if all session variables are set 
	if (isset($_SESSION['user_id'], $_SESSION['login_string'])) 
	{
		$user_id 		= $_SESSION['user_id'];
		$login_string 	= $_SESSION['login_string'];
		// Get the user-agent string of the user.
		$user_browser 	= $_SERVER['HTTP_USER_AGENT'];

		$query 		= "SELECT password FROM exp_members WHERE member_id = ? LIMIT 1";
		if ($stmt = $mysqli->prepare($query)) 
		{
			// Bind "$user_id" to parameter. 
			$stmt->bind_param('i', $user_id);
			$stmt->execute();   			// Execute the prepared query.
			$stmt->store_result();
 
			if ($stmt->num_rows == 1) {
				// If the user exists get variables from result.
				$stmt->bind_result($password);
				$stmt->fetch();

				$login_check = hash('sha512', $password . $user_browser);
				if ($login_check == $login_string) {
					// Logged In!!!! 
					return true;
				} else {
					// Not logged in 
					return false;
				}
			} else {
				// Not logged in 
				return false;
			}
		} else {
			// Not logged in 
			return false;
		}
	} else {
		// Not logged in 
		return false;
	}

}



//////////////////////////////////////////////////////////////////////////////////////////////
// 		Sanitize URL from PHP_SELF.
//////////////////////////////////////////////////////////////////////////////////////////////
function esc_url($url) {

	if ('' == $url) {
		return $url;
	}

	$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

	$strip = array('%0d', '%0a', '%0D', '%0A');
	$url = (string) $url;

	$count = 1;
	while ($count) {
		$url = str_replace($strip, '', $url, $count);
	}
 
	$url = str_replace(';//', '://', $url);

	$url = htmlentities($url);

	$url = str_replace('&amp;', '&#038;', $url);
	$url = str_replace("'", '&#039;', $url);

	if ($url[0] !== '/') {
		// We're only interested in relative links from $_SERVER['PHP_SELF']
		return '';
	} else {
		return $url;
	}
}


