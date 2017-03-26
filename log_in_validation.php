<?php 
session_start();
include 'config.php';

	//connect database
	$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info["pwd"],$mysql_info['dbname']);
	if (!$con) {
		die ('Could not connect:'.mysqli_error());
	}
	mysqli_select_db($con,'erms');
	//quert username and password
	$query="select * from user where username='".$_POST['Username']."' and password='".$_POST['Password']."'";
	$result=mysqli_query($con,$query);
	//validate
	if (mysqli_num_rows($result)==0) {
		$bb = "Invalid username or password<br>Please go back to Log in Page";
		$next_action="Log_in.php";
		$submit_value="Back";
	}
	else {
		$bb =  "Welcome,you have successfully logged in.";
		$next_action="main_menu.php";
		$submit_value="Continue";
		$_SESSION['currentid']=session_id();
		$_SESSION[session_id()] = array('Username' => $_POST['Username'], 'Password'=> $_POST['Password']);

	}
	mysqli_close($con);
?>
<html>
<head>
<meta http-equiv="refresh" content="3;url=<?php echo $next_action; ?>"/>
</head>
</body>
	<p><?php echo $bb; ?></p>
</body>
</html>

