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
	<!-- 	<?php
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
		?> -->
		<form action='process.php' method="post">
			<p>Message:</p>
			<input type="hidden" name='action' value="message">
			<textarea type='text' name="message"></textarea>
			<input type='submit' value="Submit">
		</form>
		<?php
		$query="SELECT users.first_name as first_name,users.last_name as last_name,messages.id as m_id, messages.message as message,messages.created_at as m_date,
				FROM users
				LEFT JOIN messages ON users.id=messages.user_id
				Group by messages.id
				order by messages.created_at desc";
		$result=mysqli_query($connection,$query);
		while ($row=mysqli_fetch_assoc($result)) 
		{?>
			<div class="message">
			<h1><?= $row['first_name'].' '.$row['last_name'].' '.$row['m_date']?></h1>
			<p><?= $row['message']?></p>
			</div>
		<?php	
			$query1="SELECT users.first_name as first_name,users.last_name as last_name,comments.comment as comment,comments.created_at as c_date, FROM comments
					LEFT JOIN users on users.id=comments.user_id
					LEFT JOIN messages on messages.id=comments.message_id
					WHERE comments.message_id=$row[m_id] 
					order by comments.created_at asc";
			$result1=mysqli_query($connection,$query1);
			while ($row1=mysqli_fetch_assoc($result1)) 
			{ ?>
				<div class='comment'>
				<p><?= $row1['first_name'].$row1['last_name'].$row1['c_date']?></p>
				<p><?= $row1['comment']?></p>
				</div>
		<?php	} ?>
			<form action="process.php" method="post">
				<p>Comments:</p>
				<input type="hidden" name="action" value="comment">
				<input type="hidden" name="message_id" value="<?= $row['m_date']?>">
				<textarea type='text' name="comment"></textarea>
				<input type="submit" value="Submit">
			</form>
		 }

		?>

		
		<form action='process.php' method=post>
			<input type='hidden' name='action' value='log_out'>
			<input type="submit" value='Log Out'>
		</form>
	</body>
</html>
<?php

?>
