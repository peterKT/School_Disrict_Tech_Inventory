<?php #  - district_update_inventory.php

//accessed through district_edit_printers.php

$page_title = 'Update Toner Inventory';
include ('../includes/header_district_printers.html');


if (  (isset($_GET['ti_id'])) && (is_numeric($_GET['ti_id'])) )  {	//CHECK FOR CORRECT INPUT
	$ti_id=$_GET['ti_id'] ; 
} elseif ( (isset($_POST['ti_id'])) && (is_numeric($_POST['ti_id'])) ) {
	$ti_id=$_POST['ti_id'] ;
} else {

		echo "<p>Ink inventory ID is seen as $ti_id</p>";
	echo '<h1 id="mainhead">
	Page Error</h1>
	<p class="error">This here page has been accessed in error.</p><p><br /><br /></p>';
include ('../includes/footer_district.html');
exit();
}



require_once ('../../mysql_connect_inventory.php');

if (isset($_POST['submitted'])) {					//OPEN SUBMITTED for POST info

/*
The following values are received from the submitted form:
ink_qty for updated cartridge count
original for the old count
toner_no for the cartridge number for display purposes
ti_id for the entry that needs to be updated
school for display purposes
room_name for display purposes
toner_id and location_id for checking purposes

IF the user wants to remove the location, "remove" value "not_used_for_toner" will be received and
the ti_id entry will be deleted



*/

  			
		$count = $_POST['ink_qty'];
		$cn = $_POST['toner_no'];
		$orig = $_POST['original'];
		$school = $_POST['school'];
		$room_name = $_POST['room_name'];
		$toner_id = $_POST['toner_id'];
		$location_id = $_POST['location_id'];
		

/*
//USE TO CHECK INPUT
echo "<p>count is $count</p>";
echo "<p>cn is $cn</p>";
echo "<p>orig is $orig</p>";
*/



  
  $query = "SELECT * FROM toner_inventory WHERE toner_id = $toner_id AND location_id = $location_id AND ti_id != $ti_id";
  $result = mysql_query($query);
  if (mysql_num_rows($result)==0) {						//IF THERE IS NO MATCH ON THIS INK WITH ANOTHER ID
  
  
// Check to see if they want to remove this cartridge altogether. If so, delete. If not, update.  
  
  
  		if (!empty($_POST['remove']) ) {
  			echo '<p>You elected to remove this cartridge from this location.</p>';

	$query = "DELETE FROM toner_inventory WHERE ti_id=$ti_id";
	$result = mysql_query($query);
		if (mysql_affected_rows() == 1) {
			echo '<h1 id="mainhead">Remove Inventory</h1>
				<p>The inventory has been removed from this location.</p>
				<p><br /><br /></p>';



$body = "The quantity for cartridge '$cn' at $school in $room_name has been removed .\n\n" ;
mail ('ptitus@localhost', 'Change in inventory database', $body, 'From: district_update_inventory.php') ;

include ('../includes/footer_district.html');
mysql_close();
exit();


			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The cartridge information was not changed even though you tried to remove it.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			
include ('../includes/footer_district.html');
mysql_close();
exit();
			}

// Close remove inventory and proceed to update inventory

		} else {

	$query = "UPDATE toner_inventory SET quantity=$count  WHERE ti_id=$ti_id";
	$result = mysql_query($query);
		if (mysql_affected_rows() == 1) {
			echo '<h1 id="mainhead">Update Inventory</h1>
				<p>The inventory has been updated.</p>
				<p><br /><br /></p>';



$body = "The quantity for cartridge '$cn' at $school in $room_name has been changed from '$orig' to '$count' .\n\n" ;
mail ('ptitus@localhost', 'Change in inventory', $body, 'From: district_update_inventory.php') ;

include ('../includes/footer_district.html');
mysql_close();
exit();


			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The cartridge information was not changed.  Possibly you did not make a change.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			
include ('../includes/footer_district.html');
mysql_close();
exit();
			}
}


	} else {								// Go here if the cartridge number and location is not unique.
		echo '<h1 id="mainhead">Error!</h1>
			<p class="error">There is a mistake in the database; more than one cartridge in that location with that number.</p>';
			
include ('../includes/footer_district.html');
mysql_close();
exit();			
		}								

		}								//CLOSE SUBMITTED
		
//Receive the toner inventory ID, show quantity and location, and offer to change quantity or remove.

		
		
	$query = "SELECT toner_no, toner_alias, toner_color, quantity, room_name, school, toner_inventory.toner_id, toner_inventory.location_id FROM toner_inventory, toner, toner_color, room_names, schools, locations WHERE toner_inventory.ti_id= $ti_id AND toner.toner_color_id=toner_color.toner_color_id AND toner_inventory.toner_id=toner.toner_id and toner_inventory.location_id=locations.location_id AND locations.room_name_id=room_names.room_name_id AND schools.school_id=locations.school_id ORDER BY toner_no";		
		

	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	if ($num == 1) {

		$row = mysql_fetch_array($result,MYSQL_NUM);
		echo '<h2>Update Toner Inventory</h2>
		
		<form action="district_update_inventory.php" method="post">

		
		<p>Cartridge Number: ' . $row[0] . ' </p>
		<p>Alias (if any): ' . $row[1] . ' </p>
  		<p>Color: ' . $row[2] . '</p>
  		<p>Location: ' . $row[4] . '</p>
		<p>Current Quantity: ' . $row[3] . '</p>
		<p>Change Quantity: <select name="ink_qty">';
			 
			 for ($x = ($row[3]+12); $x >= 0 ; $x-- ) { 
				echo "<option value=\"$x\">$x<br></option>\n "; }
		echo '</select><br>' ;

/*		echo 'Decrease Quantity: <select name="ink_qty">';
			 
			 for ($y = $row[3]-1; $y >= 0; $y-- ) { 
				echo "<option value=\"$y\">$y<br></option>\n ";  }
				
			echo '</select>' ;

*/

echo '<br><br /><br /><p>If this location no longer contains toner cartridges, click here to remove : 
 
<br /><input type="radio" name="remove" value="not_used_for_toner" />Please Remove Location 

</p>' ;




		echo '<input type="hidden" name="ti_id"  value="' . $ti_id . '" />
		<input type="hidden" name="original"  value="' . $row[3] . '" />
		<input type="hidden" name="toner_no" value= "' . $row[0] . '" />
		<input type="hidden" name="room_name" value= "' . $row[4] . '" />
		<input type="hidden" name="school" value= "' . $row[5] . '" />		
		<input type="hidden" name="toner_id" value= "' . $row[6] . '" />		
		<input type="hidden" name="location_id" value= "' . $row[7] . '" />		
		<p><input type="submit" name="submit" value="Submit"/></p>
		
		<input type="hidden" name="submitted" value="TRUE"	/>
		
		</form>' ;

		} else {

			echo '<h1 id="mainhead">Page Error</h1>
			
			<p class="error">This page has been accessed in BOTTOM error.</p>
			<p><br /><br /></p>';
		}


mysql_close();
include ('../includes/footer_district.html');

?>	
				

			


