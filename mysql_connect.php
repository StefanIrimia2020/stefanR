<?php
// conectare BD MySQL
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'blog';

$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (mysqli_connect_error()) {
	die(mysqli_connect_error());
}
?>
