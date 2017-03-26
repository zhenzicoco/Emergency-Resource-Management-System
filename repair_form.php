<?php
	include 'header.php';
	$username=$_SESSION[session_id()]['Username'];
	$con= mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	$query_resource_information="SELECT ResourceName,NextAvailableDate,ResourceStatus FROM Resource WHERE ResourceID=".$_GET['resourceid'].";";
	$result_resource_information=mysqli_query($con,$query_resource_information);
	$row_resource_information=mysqli_fetch_array($result_resource_information,MYSQLI_ASSOC);
	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
	<h4>Repair Resource</h4>
</head>
<body>
<table>
<form action="repair.php" method="POST">
<tr><td> Resource ID</td><td> <input type="text" name="ResourceID" value=<?php echo $_GET['resourceid']?> readonlu=true></td></tr>
<tr><td> Status</td><td> <input type="text" name="ResourceStatus" readonly=true 
								value="<?php echo $row_resource_information['ResourceStatus'] ?>"></td></tr>
<tr><td> Next Available </td><td> <input type="text" name="NextAvailableDate" readonly=true 
									value="<?php 
											if ($row_resource_information['ResourceStatus']=='Available'){
												echo "Now";
											}
											else {
												echo $row_resource_information['NextAvailableDate'];
											}
									  ?>"></td></tr>
<tr> <td> Lasting Days</td><td> <input type="number" name = "LastingDays" required=true min=1></td></tr>
<tr><td><input type="submit" name="Submit"></td></tr>
</form>
<tr><td><a href="resource_status.php">Cancel</a></td></tr>
</table>
</body>
</html>