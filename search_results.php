<?php
	include 'header.php';
	$username=$_SESSION[session_id()]['Username'];
	$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	if ($_SERVER['REQUEST_METHOD']=="POST") {
		$query_order=" ORDER BY r.ResourceStatus,r.ResourceName;";
		if (empty($_POST['Keyword']) && $_POST['esf_search']=='none' && $_POST['corresponding_incident']=='none')
		{
			$query_get_results="SELECT r.ResourceID,r.ResourceName,r.CostType,r.CostValue, r.NextAvailableDate, r.ResourceStatus,r.ResourceOwner,
					User.name AS 'Owner Name' FROM resource as r INNER JOIN User ON r.ResourceOwner=User.Username";
		}
		//check if incident radius is empty and valid 
		else {
			if ($_POST['corresponding_incident']!='none')
			{
				$search_long=$_SESSION[session_id()]['user_incident'][$_POST['corresponding_incident']]['IncidentLongitude'];
				$search_lat=$_SESSION[session_id()]['user_incident'][$_POST['corresponding_incident']]['IncidentLatitude'];
				$query_get_results="SELECT r.ResourceID,r.ResourceName,r.CostType,r.CostValue, r.NextAvailableDate, r.ResourceStatus,r.ResourceOwner,
				remain_distance.distance,User.name AS 'Owner Name'FROM resource as r INNER JOIN User ON r.ResourceOwner=User.Username ";
				// check if radius is empty
				if ($_POST['incident_radius']!='' && preg_match('/^\d*\.?\d+$/', $_POST['incident_radius'])){
					$_POST['incident_radius']=(int)$_POST['incident_radius'];
					$query_add_distance="
					INNER JOIN (select * from (select resourceID,@latdiff:=radians(resourcelatitude)-radians(".$search_lat.
					"),@longdiff:=radians(resourcelongitude)-radians(".$search_long."),
					@a:=pow(sin(@latdiff/2),2)+cos(radians(resourcelatitude))*cos(radians(".$search_lat."))*pow(sin(@longdiff/2),2),@c:=2*atan2(sqrt(@a),sqrt(1-@a)),@d:=6371*@c as distance from resource) as resource_distance where resource_distance.distance <".
					$_POST['incident_radius'].") as remain_distance on r.resourceID=remain_distance.resourceID";
				}
				else {
					$query_add_distance="
					INNER JOIN (select * from (select resourceID,@latdiff:=radians(resourcelatitude)-radians(".$search_lat.
					"),@longdiff:=radians(resourcelongitude)-radians(".$search_long."),
					@a:=pow(sin(@latdiff/2),2)+cos(radians(resourcelatitude))*cos(radians(".$search_lat."))*pow(sin(@longdiff/2),2),@c:=2*atan2(sqrt(@a),sqrt(1-@a)),@d:=6371*@c as distance from resource) as resource_distance) as remain_distance on r.resourceID=remain_distance.resourceID";
					
				}
				$query_get_results=$query_get_results.$query_add_distance;
				$query_order=" ORDER BY distance,r.ResourceStatus,r.ResourceName;";
			}
		else {
			$query_get_results="SELECT r.ResourceID,r.ResourceName,r.CostType,r.CostValue, r.NextAvailableDate, r.ResourceStatus,r.ResourceOwner,
			User.name AS 'Owner Name' FROM resource as r INNER JOIN User ON r.ResourceOwner=User.Username ";
			
			}
		if ($_POST['esf_search']!='none') {
			$query_add_esf="
				where (r.PrimaryESFNumber=".$_POST['esf_search']." or 
				r.ResourceID in (select resourceID from SecondaryESF where ESFNumber=".$_POST['esf_search']."))";
			$query_get_results=$query_get_results.$query_add_esf;
			$add_esf_success=true;
		}
		if (!empty($_POST['Keyword'])) {
			if ($add_esf_success) {
				$query_add_keyword=" and (r.ResourceName like '%".$_POST['Keyword']."%' or r.resourceID IN (select ResourceID from ResourceCapability where Capability like '%".
				$_POST['Keyword']."%') or r.ModelName like '%".$_POST['Keyword']."%' or r.Description like '%".$_POST['Keyword']."%')";
			}
			else {
				$query_add_keyword=
				" where (r.ResourceName like '%".$_POST['Keyword'].
				"%' or r.resourceID IN (select ResourceID from ResourceCapability where Capability like '%".
				$_POST['Keyword']."%') or r.ModelName like '%".$_POST['Keyword']."%' or r.Description like '%".$_POST['Keyword']."%')";
			}
			$query_get_results=$query_get_results.$query_add_keyword;
		}
	}
			
			$query_get_results=$query_get_results.$query_order;

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<h3> <?php 
		//fint if the user input specfic incident
		if ($_POST['corresponding_incident']!='none'){
			echo "<h4>Search Results for Incident:</h4><h4>"
			.$_POST['corresponding_incident']."#"
			.$_SESSION[session_id()]['user_incident'][$_POST['corresponding_incident']]['IncidentDescription']."</h4>";
		} else {
			echo "Search with no incident";
		}
	?>
	</h3>
</head>
<body>
<table>
<!--table-->
<tr><td>ID</td><td>Name</td><td>Owner</td><td>Cost</td><td>Status</td><td>Next Available</td><td>Distance</td><td>Action</td></tr>

<?php		
		$result_get_results=mysqli_query($con,$query_get_results);
		if (mysqli_num_rows($result_get_results)!=0) {
			while ($row_get_results=mysqli_fetch_array($result_get_results,MYSQLI_ASSOC)) {
			echo "<tr> <td>".$row_get_results['ResourceID']."</td>
			<td>".$row_get_results['ResourceName']."</td>
			<td>".$row_get_results['Owner Name']."</td>
			<td>".$row_get_results['CostValue']."/".$row_get_results['CostType']."</td>
			<td>".$row_get_results['ResourceStatus']."</td>";
			if ($row_get_results['ResourceStatus']=='Available') {
				echo "<td>Now</td>";
			} else {
				echo "<td>".$row_get_results['NextAvailableDate']."</td>";
			}
				if (isset($row_get_results['distance'])) {
					echo "<td>".round($row_get_results['distance'],4)." km</td>";
					if ($row_get_results['ResourceStatus']=='In Use' && $row_get_results['ResourceOwner']!=$username){
						echo "<td><a href=request_form.php?incidentid=".$_POST['corresponding_incident']."&resourceid=".$row_get_results['ResourceID'].
						">Request</a></td></tr>";
					}
					elseif ($row_get_results['ResourceStatus']=='Available' && $row_get_results['ResourceOwner']!=$username) {
						echo "<td><a href=request_form.php?incidentid=".$_POST['corresponding_incident']."&resourceid=".$row_get_results['ResourceID'].
						">Request</a></td></tr>";
					}
					elseif ($row_get_results['ResourceStatus']=='Available' && $row_get_results['ResourceOwner']==$username) {
						echo "<td><a href=resource_status.php>Deploy</a><a href=repair_form.php?resourceid=".$row_get_results['ResourceID'].">Repair</a></td></tr>";
					}
					elseif ($row_get_results['ResourceStatus']=='In Use' && $row_get_results['ResourceOwner']==$username) {
						echo "<td><a href=repair_form.php?resourceid=".$row_get_results['ResourceID'].">Repair </a></td><tr>";
					}
					else {
						echo "<td></td></tr>";
					}
					}

				else { echo "</tr>";}
		}}
	}
	mysqli_close($con);
?>
	
</table>
<a href='main_menu.php'> Main Menu </a>
</body>
</html>