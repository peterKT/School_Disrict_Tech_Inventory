
<?php

//From district_select_school2.php which submits school ID as Post Value "schools"  
//To district_edit_locations.php which processes info from this form



if ($_POST['submit']) {					//Receive school_id from select_school2
    	
   $school_id = $_POST['schools'] ;
	$view = $_POST['view'] ;  		
    		


include ('../includes/header_district_printers.html');

require_once('../../mysql_connect_district_printers.php');
 
 
$query = "SELECT school FROM schools WHERE school_id = $school_id";


$result = @mysql_query($query);

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) {
$school_name = $row['school'];

}

//We have $school_id and $school_name already. Need to get $room_name and $location_id for no-printer rooms.

//Begin edit printers section. Edit cartridges follows below.

if ($view == 'printers') {
	
$page_title = 'Edit printer locations';

$query2 = "SELECT room_name,location_id FROM room_names,locations WHERE locations.room_name_id=room_names.room_name_id AND locations.school_id = $school_id AND locations.location_id NOT IN (SELECT location_id FROM district_printers) AND room_names.room_name_id NOT IN (322,323,324,325,326,407) order by room_name" ;


$result2 = @mysql_query($query2);

if ($result2) {

echo "<h1 align=\"center\">$school_name Rooms Without Printers</h1>";


  echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
  <td align="left"><b>Room</b></td>
  <td align="left"><b>Add</b></td>


</tr>';


  while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {

   
  echo '<tr>
  <td align="left">' . $row2['room_name'] . '</td>
 
  <td align="left"><a href="district_add_printer.php?lid='  . $row2['location_id'] . '&room=' . $row2['room_name'] . '&school=' . $school_name . '">Add a printer</a></td>


  </tr>';
}


  echo '</table>';

mysql_free_result ($result2);



} else {
  echo '<p class="error">The locations could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
mysql_free_result ($result);
mysql_close();  

}

//Repeat for rooms with printers. Need to send printer ID to edit_locations.



$query = "SELECT room_name, CONCAT(type, ' ',printer_no) AS model, printer_id FROM room_names,locations,district_printers,district_printer_types,district_printer_models WHERE locations.room_name_id=room_names.room_name_id AND locations.school_id = $school_id AND locations.location_id IN (SELECT location_id FROM district_printers) AND locations.location_id=district_printers.location_id AND district_printer_models.printer_model_id=district_printers.printer_model_id AND district_printer_types.pt_id=district_printer_models.pt_id order by room_name,model" ;




$result = @mysql_query($query);

if ($result) {

echo "<h1 align=\"center\">$school_name Rooms With Printers</h1>";
echo "<h2 align=\"center\">Use 'Add Printers' from menu if necessary for these locations.</h2>";


  echo '<table align="center" cellspacing="0" cellpadding="5">
<tr>
  <td align="left"><b>Remove</b></td>
  <td align="left"><b>Replace</b></td>
  <td align="left"><b>Room</b></td>
  <td align="left"><b>Printer</b></td>
</tr>';

//Now show rooms with link to edit page appended with school_id, room_id, printer_model_id and school name for edit or delete

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

   
  echo '<tr>

  <td align="left"><a href="district_edit_locations.php?remove_prid=' . $row['printer_id'] . '&room=' . $row['room_name'] . '&model=' . $row['model'] . '&school=' . $school_name .'">Remove </a></td>
  
  
  <td align="left"><a href="district_edit_locations.php?replace_prid=' . $row['printer_id'] . '&room=' . $row['room_name'] . '&model=' . $row['model'] . '&school=' . $school_name . '">Replace</a></td>



  <td align="left">' . $row['room_name'] . '</td>
  <td align="left">' . $row['model'] . '</td>	


  </tr>';
}


  echo '</table>';

mysql_free_result ($result);
mysql_close();  


} else {
  echo '<p class="error">The locations could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
mysql_free_result ($result);
mysql_close();  

}


} // END of edit printers section. Continue with edit cartridges section.

elseif ($view == 'cart') {


/*
We have $school_id and $school_name already. Need to display the toner_no , toner_color, toner_alias, room_name and quantitity, and also collect the ti_id. Then send this ti_id to district_update_inventory to update the quantity, change the location or add toners to a new location.
The updating allows for changing the quantity.


*/

$page_title = 'Manage Ink Inventory at ' . $school_name ;

echo '<h1 id="mainhead" align="center">Ink Inventory at ' .$school_name . '</h1>';


$query = "SELECT ti_id, toner_no, toner_alias, toner_color, quantity, room_name FROM toner_inventory, toner, toner_color, room_names,locations WHERE toner.toner_color_id=toner_color.toner_color_id AND toner_inventory.toner_id=toner.toner_id and toner_inventory.location_id=locations.location_id AND locations.room_name_id=room_names.room_name_id AND locations.school_id = $school_id ORDER BY toner_no" ;


$result = mysql_query($query);
$num = mysql_num_rows($result);


if ($result) {

  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Edit</b></td>
  <td align="left"><b>Cartridge Number</b></td>
  <td align="left"><b>Alias</b></td>
  <td align="left"><b>Color</b></td>
  <td align="left"><b>Quantity</b></td>
  <td align="left"><b>Room</b></td>  

</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr>

  <td align="left"><a href="district_update_inventory.php?ti_id='  . $row['ti_id'] . '">Edit</a></td>

  <td align="left">' . $row['toner_no'] . '</td>
  <td align="left">' . $row['toner_alias'] . '</td>	
  <td align="left">' . $row['toner_color'] . '</td>
  <td align="left">' . $row['quantity'] . '</td>
  <td align="left">' . $row['room_name'] . '</td>
  </tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The current inks could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();



} else {
	
	echo '<p>You need to select printers or cartridges.</p>';


	}



} // END submit of school ID from district_select_schools2.php
include ('../includes/footer_district.html');
?>

