<?php
	include'header.php';
	$con= mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$query_request_validation="SELECT * FROM Request WHERE ResourceID=".$_POST['ResourceID']." AND IncidentID=".$_POST['IncidentID'].";";
		$result_request_validation=mysqli_query($con,$query_request_validation);
		if (mysqli_num_rows($result_request_validation)==0) {
			$query_request="INSERT INTO Request (ResourceID,IncidentID,ExpectedReturnDate,Status,UseStartDate) 
						VALUES (".$_POST['ResourceID'].",".$_POST['IncidentID'].",'".$_POST['ExpectedReturnDate']."','Waiting',NULL);";
			$result_request=mysqli_query($con,$query_request);
			echo "You have already requested the resource successfully.";
			} 
		else {
			echo "You have already requested the resource within this incident.";
		}
	
}
	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="refresh" content="3;url=search_resource.php"/>
</head>
<body>

</body>
</html>