<?php
	include 'header.php';
	
	$username=$_SESSION[session_id()]['Username'];
	$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	$query_getid="select max(resourceid) as max from resource";
	$result_getid=mysqli_query($con,$query_getid);
	$row_getid=mysqli_fetch_array($result_getid,MYSQLI_ASSOC); 
	$new_id=$row_getid['max']+1;
	$_SESSION[session_id()]['New_ResourceId']=$new_id;
	$Longitude_err="";
	$Latitude_err="";
	$costvalue_err="";
	$name_err="";
	$query_success=0;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$Longitude_success=0;
			$Latitude_success=0;
			$costvalue_success=0;
			$name_success=0;
			//check empty name 
			if (empty($_POST['ResourceName'])) {
				$_err="Resource Name is mandatory";
				//$name_err="Resource Name is mandatory";
			}
			else {
				$name_success=true;
			}
			//check Longitude
			if (empty($_POST['ResourceLongitude'])) {
				$_err="Longitude is mandatory";
				//$Longitude_err="Longitude is mandatory";
			}
			elseif (!preg_match('/^-?\d*\.{0,1}\d+$/',$_POST['ResourceLongitude']) || (float)$_POST['ResourceLongitude'] < -180 || (float)$_POST['ResourceLongitude'] >180 ) {
				$_err='Invalid Longitude Number';
				//$Longitude_err='Invalid Longitude Number';
			}
			else {
				$_POST['ResourceLongitude']=(float)$_POST['ResourceLongitude'];
				$Longitude_success=true;
			}
			//check Latitude
			if (empty($_POST['ResourceLatitude'])) {
				$_err='Latitude is mandatory';
				//$Latitude_err='Latitude is mandatory';
			}
			elseif (!preg_match('/^-?\d*\.{0,1}\d+$/', $_POST['ResourceLatitude']) || (float)$_POST['ResourceLatitude'] < -90 || (float)$_POST['ResourceLatitude'] >90 ) {
				$_err= 'Invalid Latitude Number';
				//$Latitude_err= 'Invalid Latitude Number';
			}  
			else {
				$_POST['ResourceLatitude']=(float)$_POST['ResourceLatitude'];
				$Latitude_success=true;
			}
			//check if cost value is number 
			if (empty($_POST['CostValue'])) {
				$_err="Cost Value is mandatory";
				//$costvalue_err="Cost Value is mandatory";
			}
			elseif (!preg_match('/^\d*\.?\d+$/', $_POST['CostValue'])) {
				$_err= 'Invalid Cost Value';
				//$costvalue_err= 'Invalid Cost Value';
			} 
			else {
				$_POST['CostValue']=(int)$_POST['CostValue'];
				$costvalue_success=true;
			}
			//add resource query
			if ($Latitude_success && $Longitude_success && $costvalue_success && $name_success) {
			$query_add_resource="INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
		  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
		VALUES ('".$_POST['ResourceName']."','".$_POST['CostType']."',".$_POST['PrimaryESFNumber'].",".$_POST['CostValue'].",'".$_POST['Description']."',".
		$_POST['ResourceLatitude'].",".$_POST['ResourceLongitude'].",'".$_POST['ModelName']."','".$_SESSION[session_id()]['Username']."',CURDATE());";
				$query_success=true;
				$result_add_resource=mysqli_query($con,$query_add_resource);	
				if (isset($_POST['Sec_ESFNumber'])) {
					foreach ($_POST['Sec_ESFNumber'] as $value) {
						$query_add_sec="INSERT INTO SecondaryESF VALUES (".$_SESSION[session_id()]['New_ResourceId'].','.$value.');';
						$result_add_sec=mysqli_query($con,$query_add_sec);
					}
				}
				if (isset($_POST['Capabilities'])){
					$Capabilities=trim($_POST['Capabilities']);
					$Capabilities_array=explode("\r\n",$Capabilities);
					foreach ($Capabilities_array as $value) {
						$query_add_cap="INSERT INTO ResourceCapability VALUES(".$_SESSION[session_id()]['New_ResourceId'].",'".$value."');";
						$result_add_cap=mysqli_query($con,$query_add_cap);
					}
				}
		}
	}

	
?>
<html>
	<head>
		<meta http-equiv="refresh" content="1;url=new_resource.php"/>
	</head>
	<body>
	<?php
		
		if ($query_success) {
		echo "Add Resource Success";
		}else{
		echo $_err;
		}
		mysqli_close($con);

	 ?>	
	</body>
</html>


