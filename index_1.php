<?php
session_start();
var_dump($_SESSION);
var_dump($_FILES);
require_once('connection.php');
?>
<html>
<head>
	<meta charset='utf-8'>
	<title>Login and Registration</title>
</head>
<body>
	<?php
		if (isset($_SESSION['error'])) 
		{
			foreach ($_SESSION['error'] as $name => $value) 
			{?>
			
			<p><?= $value;?></p>
			<?php }
		}
	?>
	<?php
		if (isset($_SESSION['success'])) 
		{?>
			<p><?= $_SESSION['success'];?></p>
		<?php }
	?>

	<div class='register'>
		<form action='process.php' method='post' enctype="multipart/form-data">
			<p>Register Now!</p>
			<input type='hidden' name='action' value='register'>
			<input type='text' name='first_name' placeholder='First Name'>
			<input type='text' name='last_name' placeholder='Last Name'>
			<input type='text' name='email' placeholder='Email Address'>
			<input type='text' name='birthdate' placeholder='Birthdate'>
			<input type='text' name='password' placeholder='Password'>
			<input type='text' name='confirm_password' placeholder='Retype Your Password'>
			<input type="file" name='file'>
			<input type='submit' value='Register'>
		</form>
	</div>
		<div class='login'>
		<form action='process.php' method='post'>
			<p>Login In Here</p>
			<input type='hidden' name='action' value='login'>
			<input type='text' name='email' placeholder='Email'>
			<input type='text' name='password' placeholder='Password'>
			<input type='submit' value='Log In'>
		</form>
	</div>
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
		width: 500px;
		height: 500px;
		border: 1px solid black;
	}

</style>