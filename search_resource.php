<?php
	include 'header.php';
	$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	$username=$_SESSION[session_id()]['Username'];
	$query_get_incident="select * from incident where OwnerUsername='".$username."';";
	$result_get_incident=mysqli_query($con,$query_get_incident);
	// get incident of current user 
	if (mysqli_num_rows($result_get_incident)!=0) {
		$incident_exist=true;
		$user_incident_array=array();
		while($row_get_incident=mysqli_fetch_array($result_get_incident,MYSQLI_ASSOC)){
			$user_incident_array[$row_get_incident['IncidentID']]=array("IncidentID" => $row_get_incident['IncidentID'],
				"IncidentDescription" =>$row_get_incident['IncidentDescription'],
				"IncidentLongitude" => $row_get_incident['IncidentLongitude'],
				"IncidentLatitude" => $row_get_incident['IncidentLatitude'])
				;}
		$_SESSION[session_id()]['user_incident']=$user_incident_array;
	} else {
		$incident_exist=false;
	}
	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
	<h4>Search Resource</h4>
</head>
<body>
<!-- form -->
<form action="search_results.php" method="POST">
	<table>
		<!-- row of keyword-->
		<tr> <td>Keyword</td><td><input type="text" name="Keyword"></td></tr>
		<!-- row of esf-->
		<tr><td>ESF</td><td> <select name="esf_search">
				<option value='none'> No specific esf </option>
				<?php 
					foreach ($_SESSION['esf'] as $key => $value) {
						echo '<option value='.$key.'>#'.$key.":".$value.'</option>';
						}
				?>
					</select>
			
						</td></tr>

		<!-- row of location -->
		<tr><td>Location</td><td>Within <input type="text" name="incident_radius"> Kilometers of incident</td></tr>
		<tr><td> Incident </td> <td> <select name='corresponding_incident'>
								<option value='none'>No inicident </option>;
								<?php
								if ($incident_exist) {
									foreach ($user_incident_array as $key => $value) {
										echo '<option value='.$value['IncidentID'].'>#'.$value['IncidentID'].$value['IncidentDescription'].'</option>';
									}
								}
								?> </select>
			
		</td></tr>
		<tr> <td><a href="main_menu.php"> Cancel </a></td><td><input type="submit" value="Save" name="submit"></td></tr>

	</table>
</form>
</body>
</html>




