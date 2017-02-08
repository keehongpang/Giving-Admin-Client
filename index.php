<?php
	include_once 'html_open.php';
?>


<!--<div class="container-fluid" style="display:none">-->
<div class="container" style="display:none">
	<div class="alert alert-danger" role="alert">
		<h4>
			NOTE: The system is down for update.<BR />
			It may take 30 minutes or 1 hour to finish it. <BR />
			Thanks for your patience while we continue to expand to better serve you!
		</h4>
	</div>
</div>

<div class="container" style="display:none">
	<div class="alert alert-warning" role="alert">
		<h4>
			NOTE: We will be upgrading the system at 12:00 PM on 06/09/2016.<BR />
			You are not able to access Giving system during this upgrade process. <BR /> 
			It may take 30 minutes or 1 hour to finish it. <BR />
			Thanks for your patience while we continue to expand to better serve you!
		</h4>
	</div>
</div>

<?php if ($logged_in) : ?>
<div class="container" sytle="display:block">
	<h2 class="heading-large">InSite: SIGN IN</h2>

	<p>
		You are currently signed <?php echo $logged ?>.
		If you are done, please <a href="./logout.php">sign out</a>.<br />
	</p>

	<p>Send an Email to <a href="mailto:giving@northlandchurch.net">giving@northlandchurch.net</a> if you need help.</p>
</div>

<?php else : ?>


<div class="container" style="display:block">
	<h2 class="heading-large">InSite: SIGN IN</h2>

	<p>
<!--		If you don't have a login, please <a href="./register.php">Register</a>.<br />	-->
		You are currently signed <?php echo $logged ?>.
	</p>
	<p>Send an Email to <a href="mailto:giving@northlandchurch.net">giving@northlandchurch.net</a> if you need help.</p>

	<?php
		if (isset($_GET['error'])) {
			$error = $_GET['error'];
			echo '<p><div id="loginError" class="alert alert-danger" role="alert">' . $error . '</div></p>';
		}

		if (isset($_GET['success'])) {
			$success = $_GET['success'];
			echo '<p><div id="loginError" class="alert alert-success" role="alert">' . $success . '</div></p>';
		}
		
	?> 


	<div id="rsResetDiv" class="alert alert-info alert-dismissible" role="alert" style="display:none">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<span id="rsResetSpan">Message for information.</span>
	</div>	

	<div id="rsErrorDiv" class="alert alert-danger" role="alert" style="display:none">
		Message for immediate attention.
	</div>	

	
	<!-- Login Form -->
	<form class="form-signin" id="formSignin" name="formSignin" action="includes/process_login.php" method="post">
		<h3 class="form-signin-heading">Please Sign In</h3>
		<p>
			<label for="emaillogin" class="sr-only">Email address</label>
			<input name="emaillogin" id="emaillogin" class="form-control required-email-login" placeholder="Email address" required="" autofocus="" type="text" maxlength="60" >
			<div id="errorinvalidemaillogin" class="alert alert-warning" role="alert" style="display:none">Please enter valid email address!</div>
			<div id="errorexistemaillogin" class="alert alert-warning" role="alert" style="display:none">Email does not exist in the system. Please register first.</div>
		</p>
		<p>
			<label for="password" class="sr-only">Password</label>
			<input name="password" id="password" class="form-control" placeholder="Password" required="" type="password">
		</p>
		<div class="checkbox" style="display:none">
			<label>
				<input value="remember-me" type="checkbox"> Remember me
			</label>
		</div>
		<p>
			<button id="loginbutton"  class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>
		</p>
		<a href="#" id="forgotpasswordlink" class="heading">Forgot your password?</a>
	</form>

	<!-- Reset Password Form -->
	<form class="form-signin" id="formReset" method="get" action="#" style="display:none">
		<h3 class="form-signin-heading">Reset Your Password</h3>

		<div class="alert alert-warning" role="alert">
			If you forgot your password, please confirm your identity with your email address, then click Reset Password.
			<BR>
			We will send you an email containing instructions on how to reset your password.	
			<BR>
			Please add an email address <a href='mailto:giving@northlandchurch.net'>giving@northlandchurch.net</a> into your email list to avoid spam. 
		</div>

		<p>
			<label for="emailreset" class="sr-only">Email address</label>
			<input name="emailreset" id="emailreset" class="form-control required-email-reset" placeholder="Email address" required="" autofocus="" type="text" maxlength="60" >
			<div id="errorinvalidemailreset" class="alert alert-warning" role="alert" style="display:none">Please enter valid email address!</div>
			<div id="errorexistemailreset" class="alert alert-warning" role="alert" style="display:none">Email does not exist in the system. Please register first.</div>
		</p>
		<p>
			<button id="resetbutton"  class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>
		</p>
		<a href="#" id="backtosigninlink" class="heading">Go Back to Sign in</a>
	</form>
</div>

<?php endif; ?>


<?php
	include_once 'html_footer.php';
?>

<script>
'use strict';

$(document).ready(function () {

	// email validation check when text field entered
//	$("input[type='text'].required-email-login").change(function() {
	$("input[type='text'].required-email-login").keyup(function() {
		// Get the value of the text input and ID 
		var value		= $(this).val().trim();
		var valueid 	= $(this).attr('id');
		
		// Check email validation
		if (validateEmail(value) === true) 
		{
			$("#errorinvalid"+valueid).hide();
		} 
		else 
		{
			$("#errorinvalid"+valueid).show();
//			$("#loginbutton").prop('disabled', true);
			return false;
		}
		
		// Call to check the email exist in the system and act based on the response
		checkLoginEmailExist(value);
		
	}).keydown(function(event) {
		if ( event.which == 13 ) {
			event.preventDefault();
		}
	});

	
	// email validation check when text field entered
	$("input[type='text'].required-email-reset").keyup(function() {
		// Get the value of the text input and ID 
		var value		= $(this).val().trim();
		var valueid 	= $(this).attr('id');
		
		if (validateEmail(value) === true) 
		{
			$("#errorinvalid"+valueid).hide();
		} 
		else 
		{
			$("#errorinvalid"+valueid).show();
//			$("#resetbutton").prop('disabled', true);
			return false;
		}
		
		// Call to check the email exist in the system and act based on the response
		checkResetEmailExist(value);
		
	}).keydown(function(event) {
		if ( event.which == 13 ) {
			event.preventDefault();
		}
	});


	// Trigger proper method based on the form clicked
	$("form").submit(function(event) {
		event.preventDefault();
		
		switch(event.target.id) {
			case "formSignin":
				formhash(event.target, event.target.password);
				break;
			case "formReset":
				$("#resetbutton").prop('disabled', true);
				processResetPassword(event.target.emailreset);
				break;
			default:
				break;
		}

	});


	$("#forgotpasswordlink").click(function() {
		$("#formSignin").hide();
		$("#formReset").show();
		$("#loginError").hide();
		return false;
	});
	
	$("#backtosigninlink").click(function() {
		$("#formReset").hide();
		$("#formSignin").show();
		return false;
	});
	

});


///////////////////////////////////////////////////////////////////////////////
// Process password resetting when clicked 'Reset Password' button
///////////////////////////////////////////////////////////////////////////////
function processResetPassword(email)
{
	// Get all payments by starting up
	$.ajax({
		type: "GET",
		url: "includes/password_reset.php?email=" + email.value.trim(),
	})
	.done(function(data) {
		$("#rsResetSpan").html(data);
		$("#rsResetDiv").show();
		$("#resetbutton").prop('disabled', false);
	})
	.fail(function(jqXHR, textStatus) {
		var msg = "Request failed: " + textStatus + ".<BR />Contact <a href='mailto:giving@northlandchurch.net'>Administrator</a>.";
		$("#rsErrorDiv").html(msg);
		$("#rsErrorDiv").show();
//		alert("Request failed: " + textStatus);
	})
	.always(function() { });
}


////////////////////////////////////////////////////////////////////////////////////
// Check if Email for Login exist in the system when email for login entered
////////////////////////////////////////////////////////////////////////////////////
function checkLoginEmailExist(email)
{
	// Call a PHP to check email exist in the EE system
	$.ajax({
		type: "GET",
		url: "includes/check_email_exist.php?email=" + email,
	})
	.done(function(data) {
		// Email does not exist in the system
		if (data == "NotExist")
		{
			$("#errorexistemaillogin").show();
			$("#loginbutton").prop('disabled', true);
		} 
		// Email exists in the system
		else if (data == "Exist")
		{
			$("#errorexistemaillogin").hide();
			$("#loginbutton").prop('disabled', false);
		}
		// Error handling
		else 
		{
			$("#loginbutton").prop('disabled', true);
			alert(data);
		}
	})
	.fail(function(jqXHR, textStatus) {
		console.log("Request failed: " + textStatus);
	})
	.always(function() { });
	
}


////////////////////////////////////////////////////////////////////////////////////
// Check if Email for Reset exist in the system when email for reset entered
////////////////////////////////////////////////////////////////////////////////////
function checkResetEmailExist(email)
{
	// Call a PHP to check email exist in the EE system
	$.ajax({
		type: "GET",
		url: "includes/check_email_exist.php?email=" + email,
	})
	.done(function(data) {
		// Email does not exist in the system
		if (data == "NotExist")
		{
			$("#errorexistemailreset").show();
			$("#resetbutton").prop('disabled', true);
		} 
		// Email exists in the system
		else if (data == "Exist")
		{
			$("#errorexistemailreset").hide();
			$("#resetbutton").prop('disabled', false);
		}
		// Error handling
		else 
		{
			$("#resetbutton").prop('disabled', true);
			alert(data);
		}
		
	})
	.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	})
	.always(function() { });
	
}

</script>
	
</body>
</html>