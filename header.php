<?php
session_start();
include 'config.php';
if(empty($_SESSION['currentid']))
{
	header('Location:log_in.php');
	exit();
}

if($_SESSION['currentid'] != session_id())
{
	header('Location:log_in.php');
	exit();
	
}


?>
