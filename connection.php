<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DB_DATABASE", "test");
$connection=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
// var_dump($connection);
if (mysqli_connect_errno()) {
	echo "error connecting to database.<br>";
	echo mysqli_connect_errno();
}
?>