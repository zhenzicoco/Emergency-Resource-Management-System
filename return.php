<?php
	include 'header.php';
	$con= mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	$query_return_request_relation="UPDATE Request SET Status='Done' WHERE ResourceID=".$_GET['resourceid']." AND IncidentID=".$_GET['incidentid'].";";
	$result_return_request_relation=mysqli_query($con,$query_return_request_relation);
	$query_return_resource_relation="UPDATE Resource SET NextAvailableDate=CURDATE(),ResourceStatus= 'Available' WHERE ResourceID=".$_GET['resourceid'].";";
	$result_return_resource_relation=mysqli_query($con,$query_return_resource_relation);
	if ($result_return_resource_relation && $result_return_request_relation) {
		echo "You have returned the resource successfully.";
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