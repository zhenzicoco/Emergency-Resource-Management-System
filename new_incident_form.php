<?php
	include"header.php";
	$username=$_SESSION[session_id()]['Username'];
	$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	$query_getid="select max(incidentid) as max from incident";
	$result_getid=mysqli_query($con,$query_getid);
	 

	if (mysqli_num_rows($result_getid)==0) {
		$new_id=1;
	}
	else {
		$row_getid=mysqli_fetch_array($result_getid,MYSQLI_ASSOC);
		$new_id=$row_getid['max']+1;
	}
	mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="js/jq.js"></script>
	<script type="text/javascript" src="js/func.js"></script>

	<title></title>
</head>
<body>
	<h4>New Incident Info</h4>
	<form action="add_new_incident.php" method="post" id="new_incident_form"> 
	<table>
	<!-- Main Form -->
	<tr><td> Incident ID</td><td> <?php echo $new_id ?></td></tr>
	<tr> <td> Date</td> <td><input type="date" name="IncidentDate" id="Date" required=true> </td><td> <font color="red"> </font></td></tr>
	<tr> <td> Description </td><td> <input type="text" name ="IncidentDescription" id="Description" required=true> </td><td> <font color="red"> </font></td></tr> 
	<tr><td> Location:Latitude</td><td><input type="text" name="IncidentLatitude" id="Latitude" required=true> </td> <td><font color="red"> </font></td></tr>
	<tr><td> Location:Longitude</td><td> <input type="text" name="IncidentLongitude" id="Longitude" required=true></td><td> <font color="red"> </font></td></tr>
	<tr><td> <input type="submit" value="Submit" name="submit"></td><td><a href="main_menu.php">Main Menu</a></td></tr>
	</table>
	</form>
<script>
		error_1 = 'Invalid Input';
		
		$("input#Longitude").blur(function(){
			res_type = check_type($(this), 'float', error_1) || check_type($(this,'int',error_1));
			if( !res_type ){
				return;
			}
			res_range = check_range($(this), -180, 180, error_1 );
			if( !res_range ){
				return;
			}
		});

		$("input#Latitude").blur(function(){
			res_type = check_type($(this), 'float', error_1) || check_type($(this,'int',error_1));
			if( !res_type ){
				return;
			}
			res_range = check_range($(this), -180, 180, error_1 );
			if( !res_range ){
				return;
			}
		});
		$("form").submit(function(){

			//Latitude check
			res_type = check_type($('input#Latitude'), 'float', error_1) || check_type($('input#Latitude'),'int',error_1);
			if( !res_type ){
				return false;
			}
			res_range = check_range($('input#Latitude'), -90, 90, error_1);
			if( !res_range ){
				return false;
			}

			//longtitude check
			res_type = check_type($('input#Longitude'), 'float', error_1) || check_type($('input#Longitude'),'int',error_1);
			if( !res_type ){
				return false;
			}
			res_range = check_range($('input#Longitude'), -180, 180, error_1 );
			if( !res_range ){
				return false;
			}
		});

</script>
</body>
</html>

