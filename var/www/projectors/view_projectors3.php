<?php # Script 7.4 - view_projectors2.php
//from SELECT_SCHOOL.php (accessed from view and edit projector header option). Sorts by room by default.
//WARNING--NEVER USE mysql_fetch_array twice in the same block.  It will drop the first row.
$page_title = 'View and Edit Projectors by School';

include ('../includes/header_projectors.html');


if  ( isset($_POST['submit']) )  {					//OPEN SUBMIT BLOCK 0 BEGINGS

  require_once ('../../mysql_connect_inventory.php');


if ( isset($_POST['schools'])  ) {			//OPEN DEFINE SCHOOL BLOCK 00 BEGINS

if ( isset($_POST['sort']) ) {
	$order_by = $_POST['sort'] ;
	}
else $order_by = 'room_names.room_name_id'  ;

$school_id = $_POST['schools'] ;
echo '<p>School ID is ' . $school_id . '</p>';


$query = "SELECT CONCAT(mf, ' ',model) AS model,school,room_name,projector_id FROM manufacturers,models,schools,room_names,locations,projectors WHERE projectors.school_id=$school_id AND schools.school_id=$school_id AND projectors.model_id=models.model_id AND models.mf_id=manufacturers.mf_id AND projectors.location_id=locations.location_id and locations.room_name_id=room_names.room_name_id ORDER BY $order_by";


$result = mysql_query($query);
//$row = mysql_fetch_array($result,MYSQL_ASSOC);
$num = mysql_num_rows($result);

if ($result) { 
  echo '<table align="center" cellspacing="0" cellpadding="5">';

  echo '<h1 id="mainhead" align="center">' . $row['school'] . ' Projector Inventory</h1>';
  echo "<h3 align=\"center\">Total projectors at this school = $num</h3>";
  echo '<tr>
  <td align="left"><b>Edit</b></td>
  <td align="left"><b>Delete</b></td>
  <td align="left"><b>Model</a></b></td>
  <td align="left"><b>Room</a></b></td>
  <td align="left"><b>ID</b></td>

</tr>';

$bg = '#eeeeee';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 

    $bg = ($bg=='#eeeeee') ? '#ffffff' : '#eeeeee';
  echo '<tr bgcolor="' . $bg . '">

  <td align="left"><a href="edit_projectors.php?id='  . $row['projector_id'] . '">Edit</a></td>

  <td align="left"><a href="delete_projector.php?id=' . $row['projector_id'] . ' ">Delete</a></td>


  <td align="left">' . $row['model'] . '</td>	
  <td align="left">' . $row['room_name'] . '</td>
  <td align="left">' . $row['projector_id'] . '</td>
  </tr>';


} 
  echo '</table>';

mysql_free_result ($result);

} else {
  echo '<p class="error">The projectors could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 } // BLOCK 8 END

mysql_close();  
exit();


}  // PROBABLY NOT NECESSARY SINCE NO SUBMIT LACKS SCHOOLS BLOCK 00 ENDS

} // CLOSE SUBMIT BLOCK 0 ENDS


include ('../includes/footer.html');
?>


