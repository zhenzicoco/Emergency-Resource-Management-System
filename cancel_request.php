<?php
	include 'header.php';
	$con= mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	$query_cancel_request="DELETE FROM Request WHERE ResourceID=".$_GET['resourceid']." AND IncidentID=".$_GET['incidentid'].";";
	$result_cancel_request= mysqli_query($con,$query_cancel_request);
	if ($result_cancel_request) {
		echo "You have canceled the request successfully.";
	} else {
		echo "You cannot cancel the request.";
	}
	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="refresh" content="3;url=resource_status.php"/>
</head>
<body>

</body>
</html>