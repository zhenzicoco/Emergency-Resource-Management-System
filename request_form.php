<?php
	include 'header.php';
	$username=$_SESSION[session_id()]['Username'];
	$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	$query_resource_information="SELECT ResourceName,NextAvailableDate FROM Resource WHERE ResourceID=".$_GET['resourceid'].";";
	$result_resource_information=mysqli_query($con,$query_resource_information);
	$row_resource_information=mysqli_fetch_array($result_resource_information,MYSQLI_ASSOC);
	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
	<h4> Request Resource</h4>
</head>
<body>
<table>
<form action="request.php" method="POST">
<tr><td> Incident </td>	<td> <?php echo $_SESSION[session_id()]['user_incident'][$_GET['incidentid']]['IncidentDescription']?></td></tr>
<tr><td> Incident ID </td><td><input type="text" name='IncidentID' value=<?php echo $_GET['incidentid'] ?> readonly=true></td></tr>
<tr><td> Resource ID </td><td> <input type="text" name="ResourceID" value=<?php echo $_GET['resourceid']?> readonlu=true></td></tr>
<tr><td> Resource Name</td><td> <?php echo $row_resource_information['ResourceName'] ?></td></tr>
<tr> <td> ExpectedReturnDate</td><td> <input name="ExpectedReturnDate" min="<?php echo$row_resource_information['NextAvailableDate']?>" type="date" required=true></td></tr>
<tr><td> <input type="submit" name="Submit"></td></tr>
</form>
<tr><td><a href="search_resource.php">Cancel</a></td></tr>
</table>
</body>
</html>