<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

	<title>Northland InSite: Finance</title>


	<!-- New CSS along with DataTables -->
	<link rel="stylesheet" type="text/css" href="libs/Bootstrap-3.3.6/css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="libs/jquery-ui-1.11.4/jquery-ui.css"/>
	<link rel="stylesheet" type="text/css" href="libs/DataTables-1.10.12/css/dataTables.bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="libs/Buttons-1.2.1/css/buttons.bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="libs/Select-1.2.0/css/select.bootstrap.css"/>
 	<link rel="stylesheet" type="text/css" href="libs/FixedHeader-3.1.2/css/fixedHeader.bootstrap.css"/>	
	
    <!-- Custom styles for this template -->
    <link href="./_css/navbar-fixed-top.css" rel="stylesheet">



<!--
	<link rel="stylesheet" type="text/css" href="https://northlandchurch.net/fonts/368811/FB3E643E0C8939C86.css" />
-->

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="./bootstrap-3.3.5/docs/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<?php
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	 
	sec_session_start();

	$logged_in = login_check($mysqli);
	if ($logged_in) {
		$logged = 'in';
		if (isset($_SESSION['role']))
		{
			$role	= $_SESSION['role'];
		}
		else
		{
			$role	= 'None';
		}
	} else {
		$logged = 'out';
	}

?>

<body>

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand brand-primary" href="http://newinsite.northlandchurch.net">InSite</a><a class="navbar-brand brand-secondary" href="http://newinsite.northlandchurch.net">Finance</a>
		</div>

		<!--/.nav-collapse -->
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
			<?php if ($logged_in) : ?>
				<?php if ($role == 'Manager') : ?>		<!-- For Finance Director -->
					<li id="navbar-recurring"><a href="./recurring.php">Recurring</a></li>
					<li id="navbar-maintenance"><a href="./maintenance.php">Maintenance</a></li>
					<li id="navbar-history"><a href="./history.php">History</a></li>
				<?php elseif ($role == 'Admin') : ?>	
					<li id="navbar-recurring"><a href="./recurring.php">Recurring</a></li>
					<li id="navbar-maintenance"><a href="./maintenance.php">Maintenance</a></li>
					<li id="navbar-history"><a href="./history.php">History</a></li>
				<?php elseif ($role == 'Super Admin') : ?>
					<li id="navbar-recurring"><a href="./recurring.php">Recurring</a></li>
					<li id="navbar-maintenance"><a href="./maintenance.php">Maintenance</a></li>
					<li id="navbar-history"><a href="./history.php">History</a></li>
				<?php else : ?>
				<?php endif; ?>
			<?php else : ?>
			<?php endif; ?>
			</ul>

			<ul class="nav navbar-nav navbar-right">
			<?php if ($logged_in) : ?>
				<li><a href="#">Logged in as <?php echo $role ?></a></li>	
				<li><a href="./logout.php">Sign Out</a></li>	
			<?php else : ?>
				<li><a href="./index.php">Sign In</a></li>
			<?php endif; ?>
			</ul>
		</div>
		<!--/.nav-collapse -->
	</div>
</nav>


