<?php
	include 'header.php';
	$username=$_SESSION[session_id()]['Username'];
	$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	$query_get_total_resource='select PrimaryESFNumber,count(*) as numbers from resource GROUP BY PrimaryESFNumber ORDER BY PrimaryESFNumber ASC;';
	$query_get_inuse_resource='select PrimaryESFNumber, count(*) as numbers from resource where ResourceStatus="In Use" GROUP BY PrimaryESFNumber ORDER BY PrimaryESFNumber ASC;';
	$result_get_total_resource=mysqli_query($con,$query_get_total_resource);
	$result_get_inuse_resource=mysqli_query($con,$query_get_inuse_resource);
	$total_resource_array=array();
	$inuse_resource_array= array();
	$query_total_number='select count(*) as total_number from resource;';
	$query_inuse_number='select count(*) as inuse_number from resource where ResourceStatus="In Use";';
	while ($row_get_total_resource=mysqli_fetch_array($result_get_total_resource,MYSQLI_ASSOC)) {
		$total_resource_array[$row_get_total_resource['PrimaryESFNumber']]=$row_get_total_resource['numbers'];
	}

	while ($row_get_inuse_resource=mysqli_fetch_array($result_get_inuse_resource,MYSQLI_ASSOC)) {
		$inuse_resource_array[$row_get_inuse_resource['PrimaryESFNumber']]=$row_get_inuse_resource['numbers'];
	}
	for ($i=1;$i<=15;$i++) {
		if (!isset($total_resource_array[$i])) {
			$total_resource_array[$i]=0;
		}
		if (!isset($inuse_resource_array[$i])) {
			$inuse_resource_array[$i]=0;
		}
	}
	$result_total_number=mysqli_query($con,$query_total_number);
	$result_inuse_number=mysqli_query($con,$query_inuse_number);
	$total_number=mysqli_fetch_array($result_total_number)['total_number'];
	if (mysqli_num_rows($result_inuse_number)==0) {
		$inuse_number=0;
	} else {
		$inuse_number=mysqli_fetch_array($result_inuse_number)['inuse_number'];
	}
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<h4> Resource Report by Primary Emergency Support Function</h4>
	</head>
	<body>
	<table> 
		<tr><td>#</td><td>Primary Emergency Support Function</td><td>Total Resources</td><td>Resources in Use</td></tr>
		<?php
		for ($i=1;$i<=15;$i++) {
			echo "<tr><td>".$i."</td><td>".$_SESSION['esf'][$i]."</td><td>".$total_resource_array[$i]."</td><td>".
			$inuse_resource_array[$i]."</td></tr>";
		}
		?>
		<tr> <td></td><td>Total</td><td><?php echo $total_number."</td><td>".$inuse_number?> </td></tr>
		<tr><td><a href="main_menu.php">Main Menu</a></td></tr> 
	</table>

	</body>
	</html>