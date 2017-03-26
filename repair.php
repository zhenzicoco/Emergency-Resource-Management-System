<?php
	include'header.php';
	$con= mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	
	if ($_SERVER['REQUEST_METHOD']=="POST"){
		if ($_POST['NextAvailableDate']=="Now") {
		$query_schedule_repair="INSERT INTO Repair (ResourceID,RepairStartDate,LastingDays,RepairStatus) VALUES (".$_POST['ResourceID']
		.", CURDATE() ,".$_POST['LastingDays'].",'In Repair');";
		$result_schedule_repair=mysqli_query($con,$query_schedule_repair);
		$query_resource_status="UPDATE Resource SET ResourceStatus = 'In Repair', NextAvailableDate=DATE_ADD(CURDATE(),INTERVAL "
		.$_POST['LastingDays']." DAY) WHERE ResourceID=".$_POST['ResourceID'].";";
		$result_resource_status=mysqli_query($con,$query_resource_status);
		if ($result_schedule_repair && $result_resource_status) {
			echo "Start the repair immediately.";
		}
	}	else {
		$query_schedule_repair="INSERT INTO Repair (ResourceID,RepairStartDate,LastingDays,RepairStatus) VALUES (".$_POST['ResourceID']
		.",'".$_POST['NextAvailableDate']."',".$_POST['LastingDays'].",'Scheduled');";
		$result_schedule_repair=mysqli_query($con,$query_schedule_repair);
		if ($result_schedule_repair) {
			echo "Schedule the repair successfully.";
		}
	}
}

	if ($_SERVER['REQUEST_METHOD']=="GET") {
		$query_cancel_repair="DELETE FROM Repair WHERE ResourceID=".$_GET['resourceid']." AND RepairStatus='Scheduled';";
		$result_cancel_repair=mysqli_query($con,$query_cancel_repair);
		if ($result_cancel_repair) {
			echo "Cancel repair successfully.";
		}
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