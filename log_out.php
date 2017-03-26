<?php
session_start();
unset($_SESSION['currentid']);
header('Location:log_in.php');

?>
