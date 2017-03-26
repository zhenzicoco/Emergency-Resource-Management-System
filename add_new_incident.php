<?php 
	include 'header.php';
	$username=$_SESSION[session_id()]['Username'];
	$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$des_success=0;
		$date_success=0;
		$longitude_success=0;
		$latitude_success=0;
		//check descripton
		if (empty($_POST['IncidentDescription'])) {
			$incident_des_err="Description is mandatory";
		} else {
			$des_success=true;
		}

		//check date
		if (empty($_POST['IncidentDate'])) {
			$incident_date_err="Date is mandatory";
		} elseif (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['IncidentDate'])) {
			$incident_date_err="Please input valid date";
		} else {
			$date_success=true;
		}
		//check latitude
		if (empty($_POST['IncidentLatitude'])){
			$Latitude_err="Latitude is mandatory";
		} elseif (!preg_match('/^-?\d*\.{0,1}\d+$/', $_POST['IncidentLatitude']) || (float)$_POST['IncidentLatitude'] < -90 || (float)$_POST['IncidentLatitude'] >90 ) {
			$Latitude_err="Please input valid Latitude";
		} else {
			$_POST['IncidentLatitude']=(float)$_POST['IncidentLatitude'];
			$latitude_success=true;
		}
		//check longitude
		if (empty($_POST['IncidentLongitude'])) {
			$Longitude_err="Longitude is mandatory";
		} elseif (!preg_match('/^-?\d*\.{0,1}\d+$/',$_POST['IncidentLongitude']) ||(float)$_POST['IncidentLongitude'] <-180 || (float)$_POST['IncidentLongitude'] >180) {
			$Longitude_err="Please input valid Longitude";
		} else {
			$_POST['IncidentLongitude']=(float)$_POST['IncidentLongitude'];
			$longitude_success=true;
		}
		if ($date_success && $latitude_success && $longitude_success && $des_success) {
				$query_add_incident="INSERT INTO Incident (IncidentDescription,IncidentLongitude,IncidentLatitude,OwnerUsername,IncidentDate) VALUES 
			('".$_POST['IncidentDescription']."',".$_POST['IncidentLongitude'].",".$_POST['IncidentLatitude'].",'".$_SESSION[session_id()]['Username']."','".$_POST['IncidentDate']."');";
			$query_success=true;
			//echo $query_add_incident;
			$result_add_incident=mysqli_query($con,$query_add_incident);

		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="refresh" content="3;url=new_incident_form.php"/>
</head>
<body>
<?php
		if ($query_success) {
		echo "Add Incident Success";
		}else{
		echo $_err;
		}
		mysqli_close($con);
?>
</body>
</html>




