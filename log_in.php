<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
	<h4> ERMS</h4>
</head>
<body>
<?php
session_destroy();
?>
<form action='log_in_validation.php' method="post">
Username: <input type="text" name="Username"><br/>
Password: <input type="password" name="Password"><br/>
<input type="submit" name="submit" value="Login">
</form>

</body>
</html>
