
<?php
$page_title = 'View all printers';

include('../includes/header_district_printers.html');
#echo '<h1 id="mainhead">Printers</h1>';
?>

<form action="district_view_all_printers.php" method="post">
<fieldset><legend>View printer info for entire district</legend>

<p>Order by: <input type="radio" name="school_sort" value="S" />School <input type="radio"
name="model_sort" value="M" />Model</p>
</fieldset>
<div align="center"><input type="submit" name="submit" value="Submit 
Info Request" /></div>
</form>


<?php

if ($_POST['submit']) {					//OPEN SUBMIT

require_once('../../mysql_connect_inventory.php');

if (isset($_POST['school_sort']) ) {
	$sort=$_POST['school_sort'];
	}

elseif (isset($_POST['model_sort']) ) {
	$sort=$_POST['model_sort'];
	}


if ($sort=='S'   ) {

$query = "SELECT CONCAT(type, ' ',printer_no) AS printers, room_name, school FROM printer_models, printer_types, room_names, schools, printers,locations WHERE printer_models.pt_id=printer_types.pt_id AND printers.printer_model_id=printer_models.printer_model_id AND printers.location_id=locations.location_id and locations.room_name_id=room_names.room_name_id AND locations.school_id=schools.school_id order by school,room_name";


$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">District Printers</h1>';
  echo "<h3 align=\"center\">Total printers in use = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Printer</b></td>
<td align="left"><b>Room</b></td>
<td align="left"><b>School</b></td>



</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['printers'] . '</td>
  <td align="left">' . $row['room_name'] . '</td>
  <td align="left">' . $row['school'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The printers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }



if ($sort=='M'   ) {

$query = "SELECT CONCAT(type, ' ',printer_no) AS printers, room_name, school FROM printer_models, printer_types, room_names, schools, printers,locations WHERE printer_models.pt_id=printer_types.pt_id AND printers.printer_model_id=printer_models.printer_model_id AND printers.location_id=locations.location_id and locations.room_name_id=room_names.room_name_id AND locations.school_id=schools.school_id order by printers,school";


$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">Entire District Printers</h1>';
  echo "<h3 align=\"center\">Total printers in use = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Printer</b></td>
<td align="left"><b>Room</b></td>
<td align="left"><b>School</b></td>



</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['printers'] . '</td>
  <td align="left">' . $row['room_name'] . '</td>
  <td align="left">' . $row['school'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The printers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }











else if ( !isset($sort) ) 


{ echo "<p>You forgot to make a choice.</p>"; }

}  //CLOSE THE SUBMIT

include('../includes/footer2.html');
?>

