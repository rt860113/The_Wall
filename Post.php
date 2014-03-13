<?php
session_start();
// var_dump($_GET);
// var_dump($_SESSION);
require_once('connection.php');
if (!isset($_SESSION['success'])) 
{
	header("Location:/");
}
?>
<html>
	<head>
		<meta charset='utf-8'>
		<title>The Wall</title>
	</head>
	<body>
		<?php 
			$query_welcome="SELECT first_name,last_name FROM users WHERE users.id=".$_SESSION['id'];
			$result_welcome=mysqli_query($connection,$query_welcome);
			$row_welcome=mysqli_fetch_assoc($result_welcome);
		?> 
		<h1 class="header">Welcome <?= ucwords(strtolower($row_welcome['first_name'])).' '.ucwords(strtolower($row_welcome['last_name'])).'!'?></h1>
		<?php
			if (isset($_SESSION['id1']['error1'])) 
			{ ?>
			<p class='error1_message'><?= $_SESSION['id1']['error1']?></p>	
		<?php }
		?>
		<?php
			if (isset($_SESSION['id1']['error2'])) 
			{ ?>
			<p class='error1_message'><?= $_SESSION['id1']['error2']?></p>	
		<?php }
		?>
		<div class='center'>
			<form action='process.php' method="post">
				<p id='message_title'>Post your Message:</p>
				<input type="hidden" name="get_id" value="<?php echo $_GET['id'];?>">
				<input type="hidden" name='action' value="message">
				<textarea type='text' name="message" class='post_area'></textarea>
				<input type='submit' value="Submit" class='button'>
			</form>
		</div>
		<?php
		$query="SELECT users.first_name as first_name,users.last_name as last_name,messages.id as m_id, messages.message as message,messages.created_at as m_date
				FROM users
				LEFT JOIN messages ON users.id=messages.user_id
				Group by messages.id
				order by messages.created_at desc";
		// echo $query;
		$result=mysqli_query($connection,$query);
		while ($row=mysqli_fetch_assoc($result)) 
		{?>
			
		<?php
			if (!empty($row['m_id'])) 
			{ ?>
		<div class='container'>
			<div class="message">
			<h1><?= ucwords(strtolower($row['first_name'])).' '.ucwords(strtolower($row['last_name'])).' at '.$row['m_date']?></h1>
			<p class='message'><?= $row['message']?></p>
			</div>
			
			<?php	$query1="SELECT users.first_name as first_name,users.last_name as last_name,comments.comment as comment,comments.created_at as c_date 
					FROM comments
					LEFT JOIN users on users.id=comments.user_id
					LEFT JOIN messages on messages.id=comments.message_id
					WHERE messages.id=".$row['m_id']." 
					order by comments.created_at asc";
			// echo $query1;
			$result1=mysqli_query($connection,$query1);
			// var_dump($result1);
			while ($row1=mysqli_fetch_assoc($result1)) 
			{ ?>
			<div class='comment'>
				<h6><?= ucwords(strtolower($row1['first_name'])).' '.ucwords(strtolower($row1['last_name'])).' at '.$row1['c_date']?></h6>
				<p class='comment'><?= $row1['comment']?></p>
				</div>
			<?php	} ?>
				<form action="process.php" method="post" class='c_form'>
					<p>Comments:</p>
					<input type="hidden" name="action" value="comment">
					<input type="hidden" name="message_id" value="<?php echo $row['m_id'];?>">
			<!-- 		<input type='hidden' name='get_id' value="<?php echo $_GET['id'];?>"> -->
					<textarea type='text' name="comment"></textarea>
					<input type="submit" value="Submit" class='c_button'>
				</form>	
				<form action='process.php' method="post" class='C-form'>
					<input type='hidden' name='action' value='delete_comment'>
					<input type='hidden' name="message_id" value="<?php echo $row['m_id'];?>">
					<input type='submit' value='Delete One comment'>
				</form>
			</div>
			<?php	} ?>
			
		<?php }

		?>
		
		<form action='process.php' method="post">
			<input type='hidden' name='action' value='delete_message'>
			<input type="submit" value='Delete One Message'>
		</form>

		<form action='process.php' method="post">
			<input type='hidden' name='action' value='log_out'>
			<input type="submit" value='Log Out'>
		</form>
		
	</body>
</html>
<style type="text/css">
	.comment{
		background-color: pink;
		color: black;
		font-size: 0.8em;
		margin-left: 30px;
	}
	.message{
		
		font-size: 1.2em;

	}
	#message_title{
		font-size: 1.5em;
	}
	.post_area{
		width: 80%;
		height: 100px;
	}
	.center{
		margin-left: 12%;
		width: 70%;
	}
	.button,.c_button{
		display: block;	
	}
	.container{
		margin-left: 150px;
		border-bottom: 1px solid black;
	}
	.c_form{
		position: relative;
		left: 60%;
		display: inline-block;
		
	}
	.c_form textarea{
		width: 400px;
		height: 50px;
	}
	.error1_message{
		margin-left: 130px;
		color: red;
		font-weight: bold;
	}
	.header{
		margin-left: 400px;
	}
</style>
