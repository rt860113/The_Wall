<?php
session_start();
var_dump($_POST);
var_dump($_SESSION);
// var_dump($_FILES);
require_once('connection.php');
function log_out()
{
	$_SESSION=array();
	session_destroy();
}
function register_validate($connection,$post)
{	
		foreach ($post as $name => $value)
	{
			
		
		if (empty($value)&&$name!='action'&&$name!='file') 
		{
			$_SESSION['error'][$name]=$name.' is empty. Please fill it out.';	
		}
		else
		{
			switch ($name) 
			{
				case 'first_name':
					if (preg_match('/[0-9]/', $value))
					{
						$_SESSION['error'][$name]=$name.' can not contain numbers.';
					}
					break;
				case 'last_name':
					if (preg_match('/[0-9]/', $value))
					{
						$_SESSION['error'][$name]=$name.' can not contain numbers.';
					}
					break;
				case 'email':
					if (!filter_var($value,FILTER_VALIDATE_EMAIL))
					{
						$_SESSION['error'][$name]=$name.' is not in correct form.';
					}else
					{
						$query_email="SELECT email FROM users";
						$result_email=mysqli_query($connection,$query_email);
						while ($row_email=mysqli_fetch_assoc($result_email)) 
						{
							if ($row_email['email']==$value) 
							{
								$_SESSION['error'][$name]='This email has already been used.';
							}
						}
					}

					break;
				case 'birthdate':
					$t_birthdate=explode('/', $value);
					if (!checkdate($t_birthdate[0], $t_birthdate[1], $t_birthdate[2])) 
					{
						$_SESSION['error'][$name]='Please input your birthday in the right format mm/dd/yyyy.';
					}
					else
					{
						$birthdate=$t_birthdate[2]."-".$t_birthdate[0]."-".$t_birthdate[1];
					}
					break;
				case 'password':
					
				case 'confirm_password':
					if (strlen($value)<8)
					{
						$_SESSION['error'][$name]=$name.' can be less than 8.';
					}elseif (($post['password']!=$post['confirm_password'])&&$name=='confirm_password') 
					{
						$_SESSION['error'][$name]=$name.' should match with password you entered.';
					}
					break;
			}
		}
	}
	var_dump($_FILES);
	if ($_FILES['file']['error']==0) 
	{
		$targetpath='uploads/';
		$filename=$_FILES['file']['name'];
		$newfilepath=$targetpath.$filename;
		if (file_exists($newfilepath)) 
		{
			$_SESSION['error']['file']=$filename.' already exits.';
		}else
		{
		$newfile=move_uploaded_file($_FILES['file']['tmp_name'], $targetpath.$filename);
		var_dump($newfile);
		}
	}
	if (isset($_SESSION['error'])) 
	{
		$_SESSION['error_type']='register';
	}
	if (!isset($_SESSION['error']))
	{	
		if (!isset($newfilepath)) 
		{
			$newfilepath=null;
		}
		$_SESSION['success']='You are our member now.';
		$salt=bin2hex(openssl_random_pseudo_bytes(22));
		$password=crypt($post['password'],$salt);
		$query="INSERT INTO users (first_name,last_name,email,birth_date,password,created_at,updated_at,file_path)
		 VALUES ('".mysqli_real_escape_string($connection,$post['first_name'])."','".mysqli_real_escape_string($connection,$post['last_name'])."','".mysqli_real_escape_string($connection,$post['email'])."','".mysqli_real_escape_string($connection,$birthdate)."','".mysqli_real_escape_string($connection,$password)."',now(),now(),'".mysqli_real_escape_string($connection,$newfilepath)."')";
		// echo $query;
		$result=mysqli_query($connection,$query);
		// var_dump($result); 
		// $query1="SELECT id FROM users WHERE email=".$post['email'];
		// $result1=mysqli_query($connection,$query1);
		// var_dump($result1);
		// $row=mysqli_fetch_assoc($result1);
		// var_dump($row);
		$user_id=mysqli_insert_id($connection);
		$_SESSION['id']=$user_id;
		header('Location: post.php?id='.$user_id);
	}else
	{
		header("Location: index_1.php");
	}
}
function log_in($connection,$post)
{
	
	foreach ($post as $name => $value) 
	{
	
		if (empty($value)&&$name!='action') 
		{
			$_SESSION['error'][$name]=$name.':Your input information is empty. Login in failed.';
		}elseif($name!='action')
		{
			$query="SELECT id,email,password FROM users WHERE email='".$post['email']."'";
					$result=mysqli_query($connection,$query);
					var_dump($result);
					$row=mysqli_fetch_assoc($result);
					var_dump($row);
			if (empty($row))
			{
				$_SESSION['error']['empty']='Your information is not in the database.';
			}
			else
			{
				switch ($name) 
				{
					case 'email':
						
						if (!$row['email']==$value) 
						{
							$_SESSION['error'][$name]=$name.': Wrong email address. Login failed.';
						}
						break;
					case 'password':
						if ($row['password']!=crypt($value,$row['password']))
						{
							$_SESSION['error'][$name]=$name.': Wrong password. Login failed.';
						}
						break;
					
				}
			}
		}
		
	}
	if (!isset($_SESSION['error'])) 
	{
		$_SESSION['success']='Log in successfully.';
		$_SESSION['id']=$row['id'];
		header('Location: post.php?id='.$row['id']);
	}else
	{
		$_SESSION['error_type']='login';
		header("Location:index_1.php");
	}
}
function post_message($connection,$post)
{
	if (empty($post['message'])) 
	{
		$_SESSION['error']['post_message']='Nothing in the message!';
	}
	else
	{
		$query="INSERT INTO messages (user_id,message,created_at,updated_at) VALUES (".$_SESSION['id'].",'".$post['message']."',now(),now())";
		$result=mysqli_query($connection,$query);
		unset($_SESSION['id1']);
		header("Location:post.php?id=".$_SESSION['id']);

	}
}
function post_comment($connection,$post)
{
	if (empty($post['comment'])) 
	{
		$_SESSION['error']['post_comment']='Nothing in the message!';
	}else
	{
		$query="INSERT INTO comments (message_id,user_id,comment,created_at,updated_at) VALUES (".$post['message_id'].",".$_SESSION['id'].",'".$post['comment']."',now(),now())";
		echo $query;
		$result=mysqli_query($connection,$query);
		header("Location:post.php?id=".$_SESSION['id']);

	}

}
function delete_message($connection)
{
	$query="SELECT id,time_to_sec(timediff(now(),created_at))/60 FROM messages WHERE user_id=".$_SESSION['id'];
	echo $query;
	$result=mysqli_query($connection,$query);
	$row=mysqli_fetch_assoc($result);
	var_dump($row);
	if (!empty($row)) 
	{
		if ($row['time_to_sec(timediff(now(),created_at))/60']<30) 
		{
			$query1="DELETE comments FROM comments left join messages
			on comments.message_id=messages.id
			WHERE messages.id=".$row['id']; 
			echo $query1;
			$result1=mysqli_query($connection,$query1);
			$query2="DELETE FROM messages WHERE messages.id=".$row['id'];
			echo $query2;
			$result2=mysqli_query($connection,$query2);
		}else
		{
			$_SESSION['id1']['error1']="You can't delete the message which is created more than 30 minutes ago.";
		}
	}
	else
	{
		$_SESSION['id1']['error1']='No more message to delete.';
	}
	header("Location:post.php?id=".$_SESSION['id']);
}
function delete_comment($connection,$post)
{
	$query="SELECT comments.id as id FROM comments 
			LEFT JOIN messages ON comments.message_id=messages.id
			WHERE comments.user_id=".$_SESSION['id']." AND messages.id=".$post['message_id'];
	echo $query;
	$result=mysqli_query($connection,$query);
	$row=mysqli_fetch_assoc($result);
	if (!empty($row)) 
	{
		$query1="DELETE comments FROM comments WHERE id=".$row['id'];
		$result1=mysqli_query($connection,$query1);
		unset($_SESSION['id1']['error2']);
	}else
	{
		$_SESSION['id1']['error2']='No more comment to delete for this message.';
	}
	header("Location:post.php?id=".$_SESSION['id']);
}

if (isset($_POST['action'])&&$_POST['action']=='register') 
{
	register_validate($connection,$_POST);
}
if (isset($_POST['action'])&&$_POST['action']=='login') 
{
	log_in($connection,$_POST);
}
if (isset($_POST['action'])&&$_POST['action']=='log_out') 
{
	log_out();
	header('Location:index_1.php');
}
if (isset($_POST['action'])&&$_POST['action']=='message') 
{
	post_message($connection,$_POST);
}
if (isset($_POST['action'])&&$_POST['action']=='comment') 
{
	post_comment($connection,$_POST);
}
if (isset($_POST['action'])&&$_POST['action']=='delete_message')
{
	delete_message($connection);
}
if (isset($_POST['action'])&&$_POST['action']=='delete_comment') 
{
	delete_comment($connection,$_POST);	
}
?>