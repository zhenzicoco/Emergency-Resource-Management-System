<?php
	include 'header.php';
	$username=$_SESSION[session_id()]['Username'];
?>

<!DOCTYPE html>
<html>
<head>
	<h3>Resource Status</h3>
</head>
<body>
	<a href="main_menu.php">Main Menu</a>
	<?php
	$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	$query_user_in_use="SELECT Resource.ResourceID,Resource.ResourceName,Incident.IncidentDescription,
						User.Name,Request.ExpectedReturnDate,Request.UseStartDate,Incident.IncidentID
						FROM Request 
						INNER JOIN Resource 
						ON Request.ResourceID=Resource.ResourceID
						INNER JOIN Incident
						ON Request.IncidentID=Incident.IncidentID
						INNER JOIN User
						ON Resource.ResourceOwner=User.Username
						WHERE Incident.OwnerUsername='".$username."'AND Request.Status='In Use';";

	$result_user_in_use=mysqli_query($con,$query_user_in_use);
	if (mysqli_num_rows($result_user_in_use)!=0) {
		echo "<h4>Resource in Use</h4>";
		echo "<table>";
		echo "<tr><td>ID</td>
			<td>Resource Name</td>
			<td>Incident Description</td>
			<td>Owner</td>
			<td>Start Date</td>
			<td>Return By</td>
			<td>Action</td></tr>";
		while ($row_user_in_use=mysqli_fetch_array($result_user_in_use,MYSQLI_ASSOC)) {
			
			echo "<tr><td>".$row_user_in_use['ResourceID']."</td>
			<td>".$row_user_in_use['ResourceName']."</td>
			<td>".$row_user_in_use['IncidentDescription']."</td>
			<td>".$row_user_in_use['Name']."</td>
			<td>".$row_user_in_use['UseStartDate']."</td>
			<td>".$row_user_in_use['ExpectedReturnDate']."</td>
			<td><a href=return.php?resourceid=".$row_user_in_use['ResourceID']."&incidentid=".$row_user_in_use['IncidentID'].">Return </a></td>";
			echo "</tr>";

		}
		echo "</table>";
	}
	$query_user_requested="SELECT Resource.ResourceID,Resource.ResourceName,Incident.IncidentDescription,
							User.Name,Request.ExpectedReturnDate,Incident.IncidentID
							FROM Request 
							INNER JOIN Resource 
							ON Request.ResourceID=Resource.ResourceID
							INNER JOIN Incident
							ON Request.IncidentID=Incident.IncidentID
							INNER JOIN User
							ON Resource.ResourceOwner=User.Username
							WHERE Incident.OwnerUsername='".$username."' AND Request.Status='Waiting';";
	$result_user_requested=mysqli_query($con,$query_user_requested);
	if (mysqli_num_rows($result_user_requested)!=0) {
		echo "<h4> Resource Requested by Me</h4>";
		echo "<table>";
		echo "<tr><td>ID</td>
			<td>Resource Name</td>
			<td>Incident Description</td>
			<td>Owner</td>
			<td>Return By</td>
			<td>Action</td></tr>";
			while($row_user_requested=mysqli_fetch_array($result_user_requested,MYSQLI_ASSOC)) {
				echo "<tr><td>".$row_user_requested['ResourceID']."</td>
				<td>".$row_user_requested['ResourceName']."</td>
				<td>".$row_user_requested['IncidentDescription']."</td>
				<td>".$row_user_requested['Name']."</td>
				<td>".$row_user_requested['ExpectedReturnDate']."</td>
				<td> <a href=cancel_request.php?resourceid=".$row_user_requested['ResourceID']."&incidentid=".$row_user_requested['IncidentID'].">Cancel </a></td>";
				echo "</tr>";
			}
		echo "</table>";
	}

	$query_user_received="SELECT Resource.ResourceID,Resource.ResourceName,Incident.IncidentDescription,Resource.ResourceStatus,
							User.Name,Request.ExpectedReturnDate,Incident.IncidentID
							FROM Request
							INNER JOIN Resource
							ON Request.ResourceID=Resource.ResourceID
							INNER JOIN Incident
							ON Request.IncidentID=Incident.IncidentID
							INNER JOIN User
							ON Incident.OwnerUsername=User.Username
							WHERE Resource.ResourceOwner='".$username."' AND Request.Status='Waiting';";
	$result_user_received=mysqli_query($con,$query_user_received);
	if (mysqli_num_rows($result_user_received)!=0) {
		echo "<h4>Resource Requests Received by Me</h4>";
		echo "<table>";
		echo "<tr><td>ID</td>
			<td>Resource Name</td>
			<td>Incident </td>
			<td> Requested by </td>
			<td>Return by </td>
			<td>Action</td></tr>";
		while ($row_user_received=mysqli_fetch_array($result_user_received,MYSQLI_ASSOC)) {
				echo "<tr><td>".$row_user_received['ResourceID']."</td>
				<td>".$row_user_received['ResourceName']."</td>
				<td>".$row_user_received['IncidentDescription']."</td>
				<td>".$row_user_received['Name']."</td>
				<td>".$row_user_received['ExpectedReturnDate']."</td>";
				if ($row_user_received['ResourceStatus']=='In Use') {
					echo "<td><a href=deploy_or_reject.php?resourceid=".$row_user_received['ResourceID']."&incidentid=".
					$row_user_received['IncidentID']."&deploy=false".">Reject</a></td>";
				} else {
					echo "<td>
					<a href=deploy_or_reject.php?resourceid=".$row_user_received['ResourceID']."&incidentid=".
					$row_user_received['IncidentID']."&deploy=true&returnby=".$row_user_received['ExpectedReturnDate'].">Deploy</a>
					<a href=deploy_or_reject.php?resourceid=".$row_user_received['ResourceID']."&incidentid=".
					$row_user_received['IncidentID']."&deploy=false".">Reject</a></td>";
				}
				echo "</tr>";
		}
		echo "</table>";
	}

	$query_user_repair="SELECT Resource.ResourceID,Resource.ResourceName,Repair.RepairStartDate,
						DATE_ADD(Repair.RepairStartDate,INTERVAL LastingDays DAY) AS 'Ready by',Repair.RepairStatus
						FROM  Repair
						INNER JOIN Resource 
						ON Resource.ResourceID=Repair.ResourceID
						WHERE Resource.ResourceOwner='".$username."' 
						AND (Repair.RepairStatus='Scheduled' OR Repair.RepairStatus='In Repair');";
	$result_user_repair=mysqli_query($con,$query_user_repair);
	if(mysqli_num_rows($result_user_repair)!=0) {
		echo"<h4>Repair Scheduled/In-progress</h4>";
		echo "<table><tr>";
		echo "<td>ID</td>
			<td>Resource Name</td>
			<td> Start on </td>
			<td> Ready by </td>
			<td> Action </td></tr>";
		while ($row_user_repair=mysqli_fetch_array($result_user_repair,MYSQLI_ASSOC)) {
			echo "<tr><td>".$row_user_repair['ResourceID']."</td>
			<td>".$row_user_repair['ResourceName']."</td>
			<td>".$row_user_repair['RepairStartDate']."</td>
			<td>".$row_user_repair['Ready by']."</td>";
			if ($row_user_repair['RepairStatus']=='Scheduled') {
				echo "<td><a href=repair.php?resourceid=".$row_user_repair['ResourceID']."&action=cancel>Cancel</a></td>";
			} else {
				echo "<td></td>";
			}
			echo "</tr>";

		}	echo "</table>";
	} 
	$query_user_all_resources="SELECT Resource.ResourceID,Resource.ResourceName,Resource.ResourceStatus,
								A.RepairStatus,Resource.NextAvailableDate
								FROM Resource
								LEFT OUTER JOIN (SELECT * FROM Repair WHERE RepairStatus='Scheduled' OR RepairStatus='In Repair') AS A
								ON Resource.ResourceID = A.ResourceID WHERE Resource.ResourceOwner='".$username."';";
	$result_user_all_resources=mysqli_query($con,$query_user_all_resources);


	if (mysqli_num_rows($result_user_all_resources)!=0) {
		echo "<h4>All Resources Owned by Me</h4>";
		echo "<table><tr>";
		echo "<td> ID </td>
			<td> Resource Name </td>
			<td> Status </td>
			<td> Next Available </td>
			<td> If Repair Scheduled or In-progress</td>
			<td> Action</td></tr>";
			while($row_user_all_resources=mysqli_fetch_array($result_user_all_resources,MYSQLI_ASSOC)) {
				echo "<tr><td>".$row_user_all_resources['ResourceID']."</td>
				<td>".$row_user_all_resources['ResourceName']."</td>
				<td>".$row_user_all_resources['ResourceStatus']."</td>";
				if ($row_user_all_resources['ResourceStatus']=='Available') {
					echo "<td>Now</td>";
				} else {
					echo "<td>".$row_user_all_resources['NextAvailableDate']."</td>";
				}
				if ($row_user_all_resources['RepairStatus']=='In Repair') {
					echo "<td>In Repair</td><td></td></tr>";
				}elseif ($row_user_all_resources['RepairStatus']=='Scheduled') {
					echo "<td>Scheduled</td>
					<td> <a href=repair.php?resourceid=".$row_user_all_resources['ResourceID']."&action=cancel>Cancel</a></td></tr>";
				} elseif (!isset($row_user_all_resources['RepairStatus'])) {
					echo "<td>No</td><td><a href=repair_form.php?resourceid=".$row_user_all_resources['ResourceID']."&action=schedule".">Repair</a></td></tr>";
				}
			}
		echo "</table>";
	}
	mysqli_close($con);
	?>

</body>
</html>