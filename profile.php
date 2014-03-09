<?php
session_start();
var_dump($_GET);
require_once('connection.php');
?>
<html>
	<head>
		<meta charset='utf-8'>
		<title>Profile Page</title>
	</head>
	<body>
		<?php
		$query="SELECT first_name,last_name,file_path FROM users WHERE id=".$_GET['id'];
		$result=mysqli_query($connection,$query);
		var_dump($result);
		$row=mysqli_fetch_assoc($result);
		var_dump($row);
		?>
		<p>Welcome <?= ' '.$row['first_name'].' '.$row['last_name'].'!'?></p>
		<img src="<?php echo $row['file_path']?>">
		<?php
		if (isset($_SESSION['success'])) 
		{?>
		<p><?= $_SESSION['success']?></p>
			
	<?php	}
		?>
		<form action='process.php' method=post>
			<input type='hidden' name='action' value='log_out'>
			<input type="submit" value='Log Out'>
			
		</form>
	</body>
</html>