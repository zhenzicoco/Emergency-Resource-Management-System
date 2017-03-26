<?php
	include 'header.php';
	$con= mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	if ($_GET['deploy']=='true') {
		$query_deploy_update_request="UPDATE Request SET Status='In Use', UseStartDate=CURDATE() WHERE ResourceID=".$_GET['resourceid']." AND "."IncidentID=".$_GET['incidentid'].";";
		$result_deploy_update_request=mysqli_query($con,$query_deploy_update_request);
		$query_deploy_update_resource="UPDATE Resource SET ResourceStatus='In Use', NextAvailableDate='".$_GET['returnby']."' WHERE ResourceID=".
		$_GET['resourceid'].";";
		$result_deploy_update_resource=mysqli_query($con,$query_deploy_update_resource);
		if ($result_deploy_update_resource && $result_deploy_update_request) {
			echo "Deploy the resource successfully.";
		} else {
			echo "You cannot deploy the resource.";
		}
	} else {
		$query_reject="DELETE FROM Request WHERE ResourceID=".$_GET['resourceid']." AND IncidentID=".$_GET['incidentid'].";";
		$result_reject=mysqli_query($con,$query_reject);
		if ($result_reject) {
			echo "Reject the request successfully.";
		} else {
			echo "You cannot reject the request.";
		}
	}
	mysqli_close($con);
?>
</!DOCTYPE html>
<html>
<head>
	<meta http-equiv="refresh" content="3;url=resource_status.php"/>
</head>
<body>

</body>
</html>