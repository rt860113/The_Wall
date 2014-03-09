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
	if (!isset($_SESSION['error']))
	{
		$_SESSION['success']='You are our member now.';
		$salt=bin2hex(openssl_random_pseudo_bytes(22));
		$password=crypt($post['password'],$salt);
		$query="INSERT INTO users (first_name,last_name,email,birthdate,password,created_at,updated_at,file_path)
		 VALUES ('".mysqli_real_escape_string($connection,$post['first_name'])."','".mysqli_real_escape_string($connection,$post['last_name'])."','".mysqli_real_escape_string($connection,$post['email'])."','".mysqli_real_escape_string($connection,$birthdate)."','".mysqli_real_escape_string($connection,$password)."',now(),now(),'".mysqli_real_escape_string($connection,$newfilepath)."')";
		$result=mysqli_query($connection,$query);
		var_dump($result); 
		// $query1="SELECT id FROM users WHERE email=".$post['email'];
		// $result1=mysqli_query($connection,$query1);
		// var_dump($result1);
		// $row=mysqli_fetch_assoc($result1);
		// var_dump($row);
		$user_id=mysqli_insert_id($connection);
		$_SESSION['id']=$user_id;
		header('Location: profile.php?id='.$user_id);
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
		header('Location: profile.php?id='.$row['id']);
	}else
	{
		header("Location:index_1.php");
	}
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






?>