<?php # Script 7.4 - view_projectors2.php
//from SELECT_SCHOOL.php (accessed from view and edit projector header option). Sorts by room by default.
//WARNING--NEVER USE mysql_fetch_array twice in the same block.  It will drop the first row.
$page_title = 'View and Edit Projectors by School';

include ('../includes/header_projectors.html');


if  ( isset($_POST['submit']) )  {					//OPEN SUBMIT

  require_once ('../../mysql_connect_projectors.php');


if ( isset($_POST['schools'])  ) {			//OPEN DEFINE SCHOOL

if ( isset($_POST['sort']) ) {
	$order_by = $_POST['sort'] ;
	}
else $order_by = 'rooms.room_id'  ;

$school_id = $_POST['schools'] ;
echo '<p>School ID is ' . $school_id . '</p>';


// OPEN VIEW PROJECTORS IN ALL FESTA, NOT JUST SINGLE WING
if ($school_id == 4) { 

$query = "SELECT CONCAT(mf, ' ',model) AS model,school,room,projector_id FROM manufacturers,models,schools,rooms,projectors WHERE (projectors.school_id >= 15 AND projectors.school_id <= 19) AND projectors.school_id=schools.school_id AND projectors.model_id=models.model_id AND models.mf_id=manufacturers.mf_id AND projectors.room_id=rooms.room_id ORDER BY $order_by";

$result = mysql_query($query);
//$row = mysql_fetch_array($result,MYSQL_ASSOC);
$num = mysql_num_rows($result);

if ($result) {

  echo '<table align="center" cellspacing="0" cellpadding="5">';

 // while ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {

  echo '<h1 id="mainhead" align="center">' . $row['school'] . ' Projector Inventory</h1>';
  echo "<h3 align=\"center\">Total projectors at Felix Festa = $num</h3>";
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
  <td align="left">' . $row['room'] . '</td>
  <td align="left">' . $row['projector_id'] . '</td>
  </tr>';

//<td align="left">' . $row['model'] . '</td>
//  <td align="left">' . $row['room'] . '</td>
//  <td align="left">' . $row['projector_id'] . '</td>

}
  echo '</table>';

mysql_free_result ($result);

//}


} else {
  echo '<p class="error">The projectors could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

mysql_close();  
exit();


}  //CLOSE RETRIEVE INFO FOR ALL FESTA


// OPEN VIEW SPECIFIC SCHOOL
else {

$query = "SELECT CONCAT(mf, ' ',model) AS model,school,room,projector_id FROM manufacturers,models,schools,rooms,projectors WHERE projectors.school_id=$school_id AND schools.school_id=$school_id AND projectors.model_id=models.model_id AND models.mf_id=manufacturers.mf_id AND projectors.room_id=rooms.room_id ORDER BY $order_by";

$result = mysql_query($query);
//$row = mysql_fetch_array($result,MYSQL_ASSOC);
$num = mysql_num_rows($result);

if ($result) {

  echo '<table align="center" cellspacing="0" cellpadding="5">';

 // while ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {

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
  <td align="left">' . $row['room'] . '</td>
  <td align="left">' . $row['projector_id'] . '</td>
  </tr>';

//<td align="left">' . $row['model'] . '</td>
//  <td align="left">' . $row['room'] . '</td>
//  <td align="left">' . $row['projector_id'] . '</td>

}
  echo '</table>';

mysql_free_result ($result);

//}


} else {
  echo '<p class="error">The projectors could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

mysql_close();  
exit();


}  //CLOSE RETRIEVE INFO FOR SPECIFIC SCHOOL


}  // PROBABLY NOT NECESSARY SINCE NO SUBMIT LACKS SCHOOLS

} // CLOSE SUBMIT


include ('../includes/footer.html');
?>


