<?php
session_start();
// var_dump($_SESSION);
// var_dump($_FILES);
require_once('connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset='utf-8'>
	<title>Login and Registration</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/normalize.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>
<body>
	<?php
	if (isset($_SESSION['error_type'])&&$_SESSION['error_type']=='register')
	{
		if (isset($_SESSION['error'])) 
		{ ?>
			<div>
		<?php	foreach ($_SESSION['error'] as $name => $value) 
			{ ?>
			
			<p><kbd><?= $value;?></kbd></p>
			<?php } ?>
		   </div>
	<?php	}
	}
	?>
	<?php
		if (isset($_SESSION['success'])) 
		{?>
			<p><?= $_SESSION['success'];?></p>
		<?php }
	?>

	<div class='register container'>
		<form role ="form" action='process.php' method='post' enctype="multipart/form-data">
			<p>Register Now!</p>
			<input type='hidden' name='action' value='register'>
			<div class="form-group">
			<label for="first_name_input">First Name</label>
			<input type='text' id="first_name_input" name='first_name' placeholder='First Name' class="form-control">
			</div>
			<div class="form-group">
			<label for="last_name_input">Last Name</label>
			<input type='text' id="last_name_input" name='last_name' placeholder='Last Name' class="form-control">
		    </div>
		    <div class="form-group">
			<label for="email_input">Email Address</label>
			<input type='email' id="email_input" name='email' placeholder='Email Address' class="form-control">
			</div>
			<div class="form-group">
			<label for="birthdate_input">Birth Date</label>
			<input type='text'  id="birthdate_input" name='birthdate' placeholder='Birthdate' class="form-control">
			</div>
			<div class="form-group">
			<label for="password_input">Password</label>
			<input type='password' id="password_input" name='password' placeholder='Password' class="form-control">
			</div>
			<div class="form-group">
			<label for="c_password_input">Confirm Password</label>
			<input type='password' id="c_password_input" name='confirm_password' placeholder='Retype Your Password' class="form-control">
			</div>
			<div class="form-group">
			<label for="file_input">File Input</label>
			<input type="file" name='file' id="file_input">
			</div>
			<input type='submit' value='Register' class="btn btn-default">
		</form>
	</div>
	
	<div class='login container'>
		<form action='process.php' method='post'>
			<p>Login In Here</p>
			<input type='hidden' name='action' value='login'>
			<input type='text' name='email' placeholder='Email'>
			<input type='password' name='password' placeholder='Password'>
			<input type='submit' value='Log In'>
		</form>
	</div>
	<?php
	if (isset($_SESSION['error_type'])&&$_SESSION['error_type']=='login')
	{
		if (isset($_SESSION['error'])) 
		{
			foreach ($_SESSION['error'] as $name => $value) 
			{?>
			<div>
			<p><kbd><?= $value;?></kbd></p>
			</div>
			<?php }
		}
	}
	?>
</body>
</html>
<?php 
	$_SESSION=array();
?>
<style type="text/css">
	div
	{
		display: inline-block;
		vertical-align: top;
		width: 300px;
		height: auto;

		
	}
	.form-group{
		height: 70px;
		width: 250px;
		display: block;
		margin: 0;
	}
	.container
	{
		width: 400px;
	}

</style>