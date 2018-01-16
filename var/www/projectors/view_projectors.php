
<?php
$page_title = 'View projectors, Smartboards/screens';
//from VIEW ENTIRE PROJECTOR INVENTORY option on header. Select radio buttons by model or school
include ('../includes/header_projectors.html');
?>



<?php

if (isset($_POST['submit'])) {					//OPEN SUBMIT
								//Submit form is below
  require_once ('../../mysql_connect_inventory.php');


if ( !isset($_POST['search'])  ){
	
	echo "<p>You forgot to specify a search term.</p>" ;
	exit();
	}

$search = $_POST['search'] ;

if ($search=='M'   ) {

$query = "SELECT CONCAT(mf, ' ',model) AS model,screen,school,room_name from manufacturers,projector_models,projectors,smartboards,screens,schools,room_names,locations where projector_models.mf_id=manufacturers.mf_id AND projectors.model_id=projector_models.model_id AND projectors.location_id=smartboards.location_id AND screens.screen_id=smartboards.screen_id AND projectors.location_id=locations.location_id AND locations.school_id=schools.school_id AND room_names.room_name_id=locations.room_name_id order by model,school" ;


$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">Projector and Smartboard Inventory</h1>';
  echo "<h3 align=\"center\">Total installations = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Projector Model</b></td>
  <td align="left"><b>Smartboard or Screen</b></td>
  <td align="left"><b>School</b></td>
  <td align="left"><b>Room</b></td>
  
</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['model'] . '</td>

  <td align="left">' . $row['screen'] . '</td>

  <td align="left">' . $row['school'] . '</td>

  <td align="left">' . $row['room_name'] . '</td>

 </tr>';
}
  echo '</table>';

mysql_free_result ($result);
exit();
} else {
  echo '<p class="error">The data could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }


elseif ($search=='B'   ) {

$query = "SELECT CONCAT(mf, ' ',model) AS model,screen,school,room_name from manufacturers,projector_models,projectors,smartboards,screens,schools,room_names,locations where projector_models.mf_id=manufacturers.mf_id AND projectors.model_id=projector_models.model_id AND projectors.location_id=smartboards.location_id AND screens.screen_id=smartboards.screen_id AND projectors.location_id=locations.location_id AND locations.school_id=schools.school_id AND room_names.room_name_id=locations.room_name_id order by screen,school" ;


$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">Projector and Smartboard Inventory</h1>';
  echo "<h3 align=\"center\">Total installations = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  
  <td align="left"><b>Smartboard/Screen Type</b></td>
  <td align="left"><b>Projector Model</b></td>
  <td align="left"><b>School</b></td>
  <td align="left"><b>Room</b></td>
  
</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['screen'] . '</td>

  <td align="left">' . $row['model'] . '</td>

  <td align="left">' . $row['school'] . '</td>

  <td align="left">' . $row['room_name'] . '</td>

 </tr>';
}
  echo '</table>';

mysql_free_result ($result);
exit();
} else {
  echo '<p class="error">The data could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }
else if ($search=='S'   ) {

$query = "SELECT CONCAT(mf, ' ',model) AS model,screen,school,room_name from manufacturers,projector_models,projectors,smartboards,screens,schools,room_names,locations where projector_models.mf_id=manufacturers.mf_id AND projectors.model_id=projector_models.model_id AND projectors.location_id=smartboards.location_id AND screens.screen_id=smartboards.screen_id AND projectors.location_id=locations.location_id AND locations.school_id=schools.school_id AND room_names.room_name_id=locations.room_name_id order by school,screen" ;

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">Projector and Smartboard Inventory</h1>';
  echo "<h3 align=\"center\">Total installations = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>School</b></td>
  <td align="left"><b>Projector Model</b></td>
  <td align="left"><b>Smartboard/Screen Type</b></td>
  <td align="left"><b>Room</b></td>

</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['school'] . '</td>
  <td align="left">' . $row['model'] . '</td>
  <td align="left">' . $row['screen'] . '</td>
  <td align="left">' . $row['room_name'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The data could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }

}  //CLOSE THE SUBMIT

?>


<form action="view_projectors.php" method="post">

<h1 id="mainhead" align="center">Projector and Smartboard Inventory</h1>;
<fieldset><legend>View projector and screen inventory for all locations</legend>

<p>Sort by: <input type="radio" name="search" value="M" />Projector Model 

<input type="radio" name="search" value="B" />Smartboard Model/Screen Type

<input type="radio" name="search" value="S" />School
</p>




</fieldset>
<div align="center"><input type="submit" name="submit" value="Submit 
Info Request" /></div>
</form>

</body>
</html>


