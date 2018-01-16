<?php # Script 7.4 - view_screens.php
//from SELECT-SCHOOL_BOARDS.PHP, accessed from Edit Screens header option.
$page_title = 'View and Edit Screens by School';

include ('../includes/header_projectors.html');


if  ( isset($_POST['submit']) )  {					//OPEN SUBMIT

  require_once ('../../mysql_connect_inventory.php');


if ( isset($_POST['schools'])  ) {			//OPEN DEFINE SCHOOL

if ( isset($_POST['sort']) ) {
	$order_by = $_POST['sort'] ;
	}
else $order_by = 'room_names.room_name_id'  ;

$school_id = $_POST['schools'] ;
echo '<p>School ID is ' . $school_id . '</p>';

//OPEN INFO FOR SPECIFIC SCHOOL 

$query = "SELECT school FROM schools WHERE school_id=$school_id";
$result = mysql_query($query);
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$school_name = $row['school'] ;
mysql_free_result($result) ;

$query = "SELECT board_id,screen,mount,school,room_name,serial_no from smartboards,screens,mounts,schools,locations,room_names where locations.school_id=$school_id AND smartboards.location_id=locations.location_id and schools.school_id=locations.school_id and smartboards.screen_id=screens.screen_id and locations.room_name_id=room_names.room_name_id AND smartboards.mount_id=mounts.mount_id ORDER BY $order_by";

$result = mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {



  echo '<table align="center" cellspacing="0" cellpadding="5">';

  echo '<h1 id="mainhead" align="center">' . $school_name . ' Screen Inventory</h1>';
  echo "<h3 align=\"center\">Total screens at this school = $num</h3>";
  echo '<tr>
  <td align="left"><b>Edit</b></td>
  <td align="left"><b>Delete</b></td>
  <td align="left"><b>Screen Type</a></b></td>
  <td align="left"><b>Mount</a></b></td>
  <td align="left"><b>School</b></td>
  <td align="left"><b>Room</b></td>
  <td align="left"><b>Serial No.</b></td>

</tr>';


$bg = '#eeeeee';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

    $bg = ($bg=='#eeeeee') ? '#ffffff' : '#eeeeee';
  echo '<tr bgcolor="' . $bg . '">

  <td align="left"><a href="edit_screens.php?id='  . $row['board_id'] . '">Edit</a></td>

  <td align="left"><a href="delete_screen.php?id=' . $row['board_id'] . ' ">Delete</a></td>


  <td align="left">' . $row['screen'] . '</td>	
  <td align="left">' . $row['mount'] . '</td>	
  <td align="left">' . $row['school'] . '</td>	
  <td align="left">' . $row['room_name'] . '</td>
  <td align="left">' . $row['serial_no'] . '</td>

  </tr>';

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


//} //CLOSE RETRIEVE INFO FOR SPECIFIC SCHOOL




}  // PROBABLY NOT NECESSARY SINCE NO SUBMIT LACKS SCHOOLS

} // CLOSE SUBMIT





include ('../includes/footer.html');
?>


