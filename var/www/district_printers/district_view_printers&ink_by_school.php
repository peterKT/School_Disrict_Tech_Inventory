<?php 

//View printer OR ink inventory by school
//From district_select_school.php to get school_id and view option

if ($_POST['submit']) {					//OPEN SUBMIT

$page_title = 'View district printers or ink by school';

include ('../includes/header_district_printers.html');

//Do some probably unnecessary validation
  
  if ( !isset($_POST['schools']) ) {
  	
  	echo "<p>You neglected to specify a school.</p>" ;	
  	exit();
  	} else {
    		$school_id = $_POST['schools'] ;
  			}


$view = $_POST['view'] ;

//Don't forget the hidden inputs

//$school = $_POST['school'] ;



require_once ('../../mysql_connect_district_printers.php');



$query = "SELECT school FROM schools WHERE school_id = $school_id";


$result = @mysql_query($query);

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) {
$school = $row['school'];
}

/*
At this point, values for $school (school name) and $school_id are set.
No need to get them again.   

*/
if ($view == 'printers') {


echo '<form action="district_view_printers&ink_by_school.php" method="post">';


echo"<fieldset><legend>View printer inventory for $school</legend>";
echo "The school ID is $school_id <br />";
echo "The view submitted is $view <br />";



echo '<p>Sort by: <br /><input type="radio" name="search" value="M" />Model 

<br /><input type="radio" name="search" value="R" />Room
</p>' ;



echo "<input type=\"hidden\" name=\"school_id\" value= \"$school_id\" >" ;
echo "<input type=\"hidden\" name=\"school\" value= \"$school\" >" ;



echo '</fieldset>
<div align="center"><input type="submit" name="submit2" value="Submit 
Info Request" /></div>


</form>' ;




}

elseif ($view == 'cart') {
	
//No need to submit a sort preference; go immediately to school's toner info	


echo"<fieldset><legend>View cartridge inventory for $school</legend>";


$query = "SELECT toner_no, toner_color, room_name, school, quantity FROM toner, toner_color, room_names, schools, locations,toner_inventory WHERE toner_inventory.toner_id=toner.toner_id AND toner.toner_color_id=toner_color.toner_color_id AND toner_inventory.location_id=locations.location_id AND  room_names.room_name_id=locations.room_name_id AND schools.school_id=locations.school_id AND locations.school_id = '$school_id' ORDER BY toner_no";

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo "<h1 align=\"center\">$school Toner Inventory</h1>";
  echo "<h3 align=\"center\">Total cartridges = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Cartridge Number</b></td>
  <td align="left"><b>Color</b></td>  
  <td align="left"><b>Room</b></td>
  <td align="left"><b>Quantity</b></td>
 </tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['toner_no'] . '</td>

  <td align="left">' . $row['toner_color'] . '</td>
  <td align="left">' . $row['room_name'] . '</td>  
  <td align="left">' . $row['quantity'] . '</td>  
  
  
</tr>';
}
  echo '</table>';

echo '</fieldset>';



mysql_free_result ($result);
} else {
  echo '<p class="error">The printers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }


// ELSEIF none of the above 


}



//End of the submission form for receiving school ID and choosing printers or cart

//Query district_printers based on information submitted in search preference form




if ($_POST['submit2']) {

$page_title = 'View district printers by selected school';

include ('../includes/header_district_printers.html');


require_once ('../../mysql_connect_district_printers.php');

if ( !isset($_POST['search'])  ){
	
	echo "<p>You forgot to specify a search term.</p>" ;
	exit();
	}

$search = $_POST['search'] ;
$school_id = $_POST['school_id'] ;
$school = $_POST['school'] ;

if ($search=='M'   ) {
	

echo"<fieldset><legend>View printer inventory for $school</legend>";
echo "The school ID is $school_id <br />";	
	
	

$query = "SELECT CONCAT(type, ' ',printer_no) AS printers, room_name, school FROM district_printer_models, district_printer_types, room_names, schools, district_printers,locations WHERE district_printer_models.pt_id=district_printer_types.pt_id AND district_printers.printer_model_id=district_printer_models.printer_model_id AND district_printers.location_id=locations.location_id and locations.room_name_id=room_names.room_name_id AND locations.school_id=schools.school_id and locations.school_id='$school_id' order by printers" ;

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo "<h1 align=\"center\">$school Printer Inventory</h1>";
  echo "<h3 align=\"center\">Total printers = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Model</b></td>
  <td align="left"><b>Room</b></td>
 </tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['printers'] . '</td>

  <td align="left">' . $row['room_name'] . '</td>
</tr>';
}
  echo '</table>';

echo '</form>';
echo '</fieldset>';



mysql_free_result ($result);
} else {
  echo '<p class="error">The printers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }


if ($search=='R'   ) {
	
echo '<form action="district_view_printers&ink_by_school.php" method="post">';
echo"<fieldset><legend>View printer inventory for $school</legend>";
echo "The school ID is $school_id <br />";	
	
	

$query = "SELECT CONCAT(type, ' ',printer_no) AS printers, room_name, school FROM district_printer_models, district_printer_types, room_names, schools, district_printers,locations WHERE district_printer_models.pt_id=district_printer_types.pt_id AND district_printers.printer_model_id=district_printer_models.printer_model_id AND district_printers.location_id=locations.location_id and locations.room_name_id=room_names.room_name_id AND locations.school_id=schools.school_id and locations.school_id='$school_id' ORDER BY room_name" ;

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo "<h1 align=\"center\">$school Printer Inventory</h1>";
  echo "<h3 align=\"center\">Total printers = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Model</b></td>
 </tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['printers'] . '</td>

  <td align="left">' . $row['room_name'] . '</td>
</tr>';
}
  echo '</table>';

echo '</form>';
echo '</fieldset>';



mysql_free_result ($result);
} else {
  echo '<p class="error">The printers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }


}  //Close submission of search order preferences





?>



</body>
</html>


