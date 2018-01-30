<?php # district_add_new.php FROM district_select_school3.php

//Add a printer to a specific building. Can also be done by using edit schools form.
//But that form does not permit adding a second printer to a room.

$page_title = 'Add Printer or Cartridge';
include('../includes/header_district_printers.html');

if ( isset($_POST['submit']) ) {

  if ( isset($_POST['schools']) ) {
		
		if ( empty($_POST['view']) ) {
			echo "<p>You need to select either printers or cartridges.</p>";
			include ('../includes/footer_district.html');
			exit();
			}	else {
				
				$view = $_POST['view'];
				$school_id = $_POST['schools'];
 	} 
 	
 	} else {

  	echo "<p>You neglected to specify a school.</p>" ;	
  	include ('../includes/footer_district.html');
  	exit();
	}

} //END submission from select_school3 and CONTINUE following the stuff submitted from the following block.


/*
Put the "submitted" info here because the connection will have already
been set up. Submitted will provide values for printer_model_id, location_id and
corresponding info for cartridges in addition to school_id received above.
*/



if (isset($_POST['submitted'])) {	//BEGIN SUBMITTED


$location_id = $_POST['location'];
$add_id = $_POST['printer'];
echo "<p>The submitted location_id is $location_id and printer_model_id is $add_id </p>";

require_once('../../mysql_connect_inventory.php');

$query = "SELECT CONCAT(type, ' ',printer_no) AS model, room_name, school FROM printer_models, printer_types,room_names,schools,locations WHERE printer_model_id = $add_id AND printer_models.pt_id=printer_types.pt_id AND locations.location_id = $location_id AND room_names.room_name_id=locations.room_name_id AND locations.school_id=schools.school_id" ;

$result = @mysql_query($query);
if ($result) {
	while ($row = mysql_fetch_array($result,MYSQL_ASSOC) ) {
		$model = $row['model'];
		$room_name = $row['room_name'];
		$school = $row['school'] ;
		 
		 echo "<p>The model is $model and the room is $room_name and the school is $school</p>";
		 }
		 mysql_free_result($result);
} else {
	echo '<h1 id="mainhead">System Error</h1>
			<p class="error">That printer model, room, and school could not be identified.</p>';
mysql_free_result($result);
mysql_close();  
exit();
	}


//require_once('../../mysql_connect_inventory.php');

$query = "SELECT * FROM printers WHERE printer_model_id = $add_id AND location_id = $location_id";
$result = mysql_query($query);
	if (mysql_affected_rows() == 0 ) {	
		

	$query = "INSERT INTO printers(printer_model_id,location_id) VALUES ( $add_id , $location_id )";
	$result = @mysql_query($query);
		if (mysql_affected_rows() == 1) {		//START CONDITION 2
			echo '<h1 id="mainhead">Add a Printer</h1>
			<p>The printer ' . $model . ' has been added to ' . $room_name . ' in ' . $school . '.</p>
			<p><br /><br /></p>';


$body = "A new printer with ID number '$add_id' has been added to '$room_name' in  '$school'.\n\n" ;
	mail ('ptitus@localhost', 'Change in printers database', $body, 'From: district_add_new.php') ;


		} else  {

			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The printer could not be replaced due to a system error.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			}
	

mysql_close();  
exit();

} else {
	echo '<h1 id="mainhead">System Warning</h1>
			<p class="error">Possible duplicate: same printer already exists in that location.</p>';
			
	$query = "INSERT INTO printers(printer_model_id,location_id) VALUES ( $add_id , $location_id )";
	$result = @mysql_query($query);
		if (mysql_affected_rows() == 1) {		//START CONDITION 2
			echo '<h1 id="mainhead">Add a Printer</h1>
			<p>The printer ' . $model . ' has been added to ' . $room_name . ' in ' . $school . '.</p>
			<p><br /><br /></p>';


$body = "A new printer with ID number '$add_id' has been added to '$room_name' in  '$school'.\n\n" ;
	mail ('ptitus@localhost', 'Change in printers database', $body, 'From: district_add_new.php') ;


		} else  {

			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The printer could not be replaced due to a system error.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			}


mysql_close();  
exit();
	}

} // END SUBMITTED

if (isset($_POST['submitted2'])) {	//BEGIN SUBMITTED2


$location_id = $_POST['location'];
$toner_id = $_POST['new_toner_id'];
$quantity = $_POST['ink_qty'];

echo "<p>The submitted location_id is $location_id quantity is $quantity and toner_id is $toner_id </p>";

require_once('../../mysql_connect_inventory.php');


$query = "SELECT toner_id, CONCAT (toner_no, ' ','(', toner_alias,')',' ',toner_color) AS toner  FROM toner,toner_color WHERE toner.toner_color_id=toner_color.toner_color_id ORDER BY toner" ;

$query = "SELECT toner_id, CONCAT (toner_no, ' ','(', toner_alias,')',' ',toner_color) AS toner, room_name, school FROM toner,toner_color,room_names,schools,locations WHERE toner_id = $toner_id AND toner.toner_color_id = toner_color.toner_color_id AND locations.location_id = $location_id AND room_names.room_name_id=locations.room_name_id AND locations.school_id=schools.school_id" ;

$result = @mysql_query($query);
if ($result) {
	while ($row = mysql_fetch_array($result,MYSQL_ASSOC) ) {
		$toner = $row['toner'];
		$room_name = $row['room_name'];
		$school = $row['school'] ;
		 
		 echo "<p>The toner is $toner and the room is $room_name and the school is $school</p>";
		 }
		 mysql_free_result($result);
} else {
	echo '<h1 id="mainhead">System Error</h1>
			<p class="error">That printer model, room, and school could not be identified.</p>';
mysql_free_result($result);
mysql_close();  
exit();
	}


//require_once('../../mysql_connect_inventory.php');

$query = "SELECT * FROM toner_inventory WHERE toner_id = $toner_id AND location_id = $location_id";
$result = mysql_query($query);
	if (mysql_affected_rows() == 0 ) {	
		

	$query = "INSERT INTO toner_inventory(toner_id,location_id,quantity) VALUES ( $toner_id , $location_id , $quantity )";
	$result = @mysql_query($query);
		if (mysql_affected_rows() == 1) {		//START CONDITION 2
			echo '<h1 id="mainhead">Add a Cartridge</h1>
			<p>The quantity '. $quantity .' of cartridge ' . $toner . ' has been added to ' . $room_name . ' in ' . $school . '.</p>
			<p><br /><br /></p>';


$body = "A new toner with ID number '$toner_id' has been added to '$room_name' in  '$school'.\n\n" ;
	mail ('ptitus@localhost', 'Change in printers database', $body, 'From: district_add_new.php') ;


		} else  {

			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The printer could not be replaced due to a system error.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			}
	

mysql_close();  
exit();

} else {
	echo '<h1 id="mainhead">System Error</h1>
			<p class="error">That toner cartridge already exists in that location.</p>';
mysql_free_result($result);
mysql_close();  
exit();
	}

} // END SUBMITTED2


  


//Set up the school name
require_once('../../mysql_connect_inventory.php');

$query = "SELECT school FROM schools WHERE school_id = $school_id";


$result = @mysql_query($query);

while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) {
$school = $row['school'];

}

mysql_free_result ($result);

if ($view == 'printers') {

echo "<h2>Add Printer to  $school Inventory</h2>

<p>School ID = $school_id</p>
<p>School = $school</p>" ;

?>

<form action="district_add_new.php" method="post">
<fieldset>


<!--BEGIN SELECT printer MODEL <legend>Add Printer to Building</legend> -->

<h3>Select Printer Model</h3>
<?php

// ALREADY DONE (require_once('../../mysql_connect_inventory.php');

$query = "SELECT printer_model_id, CONCAT(type, ' ',printer_no) AS model FROM printer_models, printer_types WHERE printer_models.pt_id=printer_types.pt_id ORDER BY printer_no";

$result = @mysql_query($query);

if ($result) {
 
  echo '<select name="printer">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['printer_model_id'] . '">' . '  ' . $row['model'] .   '</option>\\n';
  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The printers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

?>


<!-- END SELECT MODEL -->

<!--BEGIN SELECT ROOM  -->

<h3>Identify Location</h3>
<?php

echo "<h4>All rooms in $school</h4>";
	
$query = "SELECT room_name,room_names.room_name_id,location_id from room_names,locations WHERE school_id = $school_id AND locations.room_name_id=room_names.room_name_id ORDER BY room_name" ;

$result = @mysql_query($query);

if ($result) {
 
  echo '<select name="location">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['location_id'] . '">' . ' ' . $row['room_name'] . '</option>\\n';

  	}  echo '</select>'; 
	
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The rooms could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }
	


?>

<br /><br /><br /><br />



<!-- END SELECT ROOM -->

</fieldset>

<div align="center">  
<input type="submit" name="submitted  " value="Submit"/></div>
<input type="hidden" name="submitted" value="TRUE"/>


<?php
//Following is already set up and does not neet to be re-submitted 
//echo 	'<input type="hidden" name="schools" value="' . $school_id . '" />';
echo '</form>';


include ('../includes/footer_district.html');

} elseif ($view == 'cart') {

echo "<h2>Add Toner Cartridge to  $school Inventory</h2>

<p>School ID = $school_id</p>
<p>School = $school</p>" ;

?>

<form action="district_add_new.php" method="post">
<fieldset>


<!--BEGIN SELECT toner <legend>Add Toner to Location in a Building</legend> -->

<h3>Select Toner</h3>
<?php


	$query = "SELECT toner_id, CONCAT (toner_no, ' ','(', toner_alias,')',' ',toner_color) AS toner  FROM toner,toner_color WHERE toner.toner_color_id=toner_color.toner_color_id ORDER BY toner_no";


		$result = mysql_query($query);

		if ($result) {

  echo '<select name="new_toner_id">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['toner_id'] . '">' . '  ' . $row['toner'] .   '</option>\\n';
	
  	}  echo '</select>'; 
	  

mysql_free_result ($result);
} else {
  echo '<p class="error">The toner cartridges could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

?>


<!-- END SELECT toner -->

<!--BEGIN SELECT ROOM  -->

<h3>Identify Location</h3>
<?php

echo "<h4>All rooms in $school</h4>";
	
$query = "SELECT room_name,room_names.room_name_id,location_id from room_names,locations WHERE school_id = $school_id AND locations.room_name_id=room_names.room_name_id ORDER BY room_name" ;

$result = @mysql_query($query);

if ($result) {
 
  echo '<select name="location">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['location_id'] . '">' . ' ' . $row['room_name'] . '</option>\\n';

  	}  echo '</select>'; 
	
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The rooms could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }
	
echo '<br /><br /><h4>Quantity:</h4> <select name="ink_qty">';
			 
			 for ($x = 20; $x >= 0 ; $x-- ) { 
				echo "<option value=\"$x\">$x<br></option>\n "; }
		echo '</select><br>' ;


?>

<br /><br /><br /><br />



<!-- END SELECT ROOM -->

</fieldset>


<div align="center">  
<input type="submit" name="submitted2  " value="Submit"/></div>
<input type="hidden" name="submitted2" value="TRUE"/>


<?php
//Following is already set up and does not neet to be re-submitted 
//echo 	'<input type="hidden" name="schools" value="' . $school_id . '" />';
echo '</form>';


include ('../includes/footer_district.html');


} //END if view == cart
	
	
	
?>





