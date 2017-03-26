<?php
include 'header.php';
$username=$_SESSION[session_id()]['Username'];
$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
$query_getid="select max(resourceid) as max from resource";
$result_getid=mysqli_query($con,$query_getid);
$row_getid=mysqli_fetch_array($result_getid,MYSQLI_ASSOC); 
$new_id=$row_getid['max']+1;
$_SESSION[session_id()]['New_ResourceId']=$new_id;
mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="js/jq.js"></script>
<script type="text/javascript" src="js/func.js"></script>
<head>
<body>
	<!--Main form -->
	<h4> New Resource Info </h4>
	<form action="add_new_resource.php" method="post" id ="new_resource_form">
	<table>
		<!-- row of  resource id -->
		<tr> <td> ResourceID </td> <td> <?php echo $new_id ?> </td> </tr>
		<!-- row of owner -->
		<tr> <td> Owner</td> <td> <?php echo $_SESSION[session_id()]['Name'] ?></td> </tr>
		<!-- row of resource name -->
		<tr> <td> Resource Name </td> <td> <input type="text" name="ResourceName" id="ResourceName" maxlength=45 required=true> </td> <td><font color="red"></font></td></tr>
		<!-- row of resource description -->
		<tr> <td> Resource Description</td> <td><input type="text" name="Description" id="Description" maxlength=250 > </td><td><font color="red"></font></td></tr>
		<!-- row of primary esf -->
		<tr> <td> Primary ESF</td>
				<td> <select name="PrimaryESFNumber">
				<?php 
					foreach ($_SESSION['esf'] as $key => $value) {
						echo '<option value='.$key.'>'.$key.":".$value.'</option>';
						}
				?>
					</select></td></tr>
		<!-- row of addtional esfs-->
		<tr><td> Addtional ESFs</td>
			<td> <select multiple="multiple" name="Sec_ESFNumber[]" size="5">
				<?php
					foreach ($_SESSION['esf'] as $key => $value) {
						echo '<option value='.$key.'>'.$key.":".$value.'</option>';
					}
				?></select></td></tr>
		<!-- row of model -->
		<tr> <td> Model</td><td> <input type="text" name="ModelName" maxlength=45 id="ModelName" ></td><td><font color="red"></font></td></tr>
		<!-- Location-->
		<tr> <td>Location:Latitude </td> <td> <input type="text" name="ResourceLatitude" id="Latitude" maxlength=19 required=true></td><td><font color="red"></font></td></tr>
		<tr> <td>Location Longitude </td><td><input type="text" name="ResourceLongitude" id="Longitude" maxlength=20 required=true></td><td><font color="red"></font></td></tr>
		<!-- row of Cost Type-->
		<tr> <td>Cost Type</td>
			<td> <select name="CostType">
				<?php
				foreach($_SESSION['cost'] as $value) {
					echo '<option value='.$value.'>'.$value.'</option>';
				}?></select></td></tr>
		<!-- Cost Value-->
		<tr><td> Cost Value$</td> <td>  <input type="number" name="CostValue" id="CostValue" required=true></td><td><font color="red"></font></td></tr>
		<!-- row of Capabilities-->
		<tr> <td> Capabilities</td>
			<td> <input type="text" id ="one_cap" > </td>
			<td> <button type="button" onclick="add_cap()">Add</button>
		</tr>
		<tr> <td></td> 
		<td>
			<textarea nrows="10" cols="50" name ="Capabilities" readonly="true" id="re_cap" >  </textarea></td>
			<script>
			function add_cap() {
				x=document.getElementById('one_cap');
				y=document.getElementById('re_cap');
				y.value=y.value+'\r\n'+x.value;
				x.value="";
			}</script>
		</tr>
		<!-- Submit and main menu button -->
		<tr> <td><input type="submit" name="Submit"><td><a href="main_menu.php">Main Menu</a></td></tr> 
	</table>
	
	</form>
		<script>

		error_1 = 'Invalid Input';

		////// events

		$("input#Latitude").blur(function (){

			res_type = check_type($(this), 'float', error_1) || check_type($(this,'int',error_1));
			if( !res_type ){
				return;
			}
			res_range = check_range($(this), -90, 90, error_1);
			if( !res_range ){
				return;
			}
		});

	
		$("input#Longitude").blur(function (){
			res_type = check_type($(this), 'float', error_1) || check_type($(this,'int',error_1));
			if( !res_type ){
				return;
			}
			res_range = check_range($(this), -180, 180, error_1 );
			if( !res_range ){
				return;
			}
		});
	

		// submit event
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


	</script>
</body>
</html>
