
<?php
//2018a
//Open the search preference form based on school selection submission


if ($_POST['submit']) {					//OPEN SUBMIT

$page_title = 'View district computers by school';

include ('../../includes/header_district_computers2.php');
//Do some probably unnecessary validation
  
  if ( !isset($_POST['schools']) ) {
  	
  	echo "<p>You neglected to specify a school.</p>" ;	
  	exit();
  	} else {
    		$school_id = $_POST['schools'] ;
  			}

require_once ('../../../mysql_connect_inventory.php');

$query = "SELECT school FROM schools WHERE school_id = $school_id";


$result = @mysql_query($query);

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) {
$school = $row['school'];
}


echo '<form action="district_view_computers.php" method="post">';


echo"<fieldset><legend>View computer inventory for $school</legend>";
echo "The school ID is $school_id <br />";


echo '<p>Sort by: <br /><input type="radio" name="search" value="M" />Model 
<br /><input type="radio" name="search" value="N" />Computer Name 
<br /><input type="radio" name="search" value="R" />Room (excludes devices assigned to people)
<br /><input type="radio" name="search" value="T" />Service Tag
<br /><input type="radio" name="search" value="P" />Person (excludes computers in rooms)
</p>' ;



echo "<input type=\"hidden\" name=\"school_id\" value= \"$school_id\" >" ;
echo "<input type=\"hidden\" name=\"school\" value= \"$school\" >" ;



echo '</fieldset>
<div align="center"><input type="submit" name="submit2" value="Submit 
Info Request" /></div>


</form>' ;



//End of the submission form for receiving school ID and choosing sort preference
}


//Query inventory database based on information submitted in search preference form

if ($_POST['submit2']) {

$page_title = 'View district computers by selected school';

include ("../../includes/header_district_computers2.php");


require_once ('../../../mysql_connect_inventory.php');

if ( !isset($_POST['search'])  ){
	
	echo "<p>You forgot to specify a search term.</p>" ;
	exit();
	}

$search = $_POST['search'] ;
$school_id = $_POST['school_id'] ;
$school = $_POST['school'] ;

if ($search=='M'   ) {
$query = "SELECT CONCAT(mf, ' ',model, ' ', computer_type) AS model,computer_name,service_tag,room_name, CONCAT(first_name, ' ', last_name) as person FROM manufacturers,computer_models, computer_types, computers, room_names,locations,teachers WHERE computers.model_id=computer_models.model_id AND computer_models.mf_id=manufacturers.mf_id AND computer_types.ct_id = computer_models.ct_id AND computers.location_id=locations.location_id AND locations.room_name_id=room_names.room_name_id AND computers.teacher_id=teachers.teacher_id AND locations.school_id = $school_id ORDER BY model" ;

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo "<h1 align=\"center\">$school Computer Inventory</h1>";
  echo "<h3 align=\"center\">Total computers = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Model</b></td>
  <td align="left"><b>Room</b></td>
  <td align="left"><b>Service Tag</b></td>
  <td align="left"><b>Name</b></td>
    <td align="left"><b>Person</b></td>
</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['model'] . '</td>

  <td align="left">' . $row['room_name'] . '</td>

  <td align="left">' . $row['service_tag'] . '</td>

  <td align="left">' . $row['computer_name'] . '</td>

  <td align="left">' . $row['person'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The computers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }

else if ($search=='N'   ) {

$query = "SELECT computer_name,CONCAT(mf, ' ',model, ' ', computer_type) AS model,service_tag,room_name, CONCAT(first_name, ' ', last_name) as person FROM manufacturers,computer_models, computer_types, computers, room_names,locations,teachers WHERE computers.model_id=computer_models.model_id AND computer_models.mf_id=manufacturers.mf_id AND computer_types.ct_id = computer_models.ct_id AND computers.location_id=locations.location_id AND locations.room_name_id=room_names.room_name_id AND computers.teacher_id=teachers.teacher_id AND locations.school_id = $school_id ORDER BY computer_name" ;


$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo "<h1 align=\"center\">$school Computer Inventory</h1>";
  echo "<h3 align=\"center\">Total computers = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Name</b></td>
<td align="left"><b>Model</b></td>
<td align="left"><b>Service Tag</b></td>
<td align="left"><b>Room</b></td>


</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['computer_name'] . '</td>
  <td align="left">' . $row['model'] . '</td>
  <td align="left">' . $row['service_tag'] . '</td>
  <td align="left">' . $row['room_name'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The computers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }

else if ($search=='R'   ) {

$query = "SELECT room_name, computer_name,CONCAT(mf, ' ',model, ' ', computer_type) AS model,service_tag FROM manufacturers,computer_models, computer_types, computers, room_names,locations WHERE computers.model_id=computer_models.model_id AND computer_models.mf_id=manufacturers.mf_id AND computer_types.ct_id = computer_models.ct_id AND computers.location_id=locations.location_id AND locations.room_name_id=room_names.room_name_id AND locations.school_id = $school_id ORDER BY room_name" ;


$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo "<h1 align=\"center\">$school Computer Inventory</h1>";
  echo "<h3 align=\"center\">Total computers in rooms = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Room</b></td>
  <td align="left"><b>Name</b></td>
  <td align="left"><b>Model</b></td>
  <td align="left"><b>Service Tag</b></td>

</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['room_name'] . '</td>
  <td align="left">' . $row['computer_name'] . '</td>
  <td align="left">' . $row['model'] . '</td>
  <td align="left">' . $row['service_tag'] . '</td>


</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The computers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }

else if ($search=='T'   ) {

$query = "SELECT service_tag,room_name,computer_name,CONCAT(mf, ' ',model, ' ', computer_type) AS model FROM manufacturers,computer_models, computer_types, computers, room_names,locations WHERE computers.model_id=computer_models.model_id AND computer_models.mf_id=manufacturers.mf_id AND computer_types.ct_id = computer_models.ct_id AND computers.location_id=locations.location_id AND locations.room_name_id=room_names.room_name_id AND locations.school_id = $school_id ORDER BY service_tag" ;

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo "<h1 align=\"center\">$school Computer Inventory</h1>";
  echo "<h3 align=\"center\">Total computers = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Service Tag</b></td>
  <td align="left"><b>Model</b></td>
  <td align="left"><b>Room</b></td>
  <td align="left"><b>Name</b></td>

</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

  echo '<tr><td align="left">' . $row['service_tag'] . '</td>



  <td align="left">' . $row['model'] . '</td>

  <td align="left">' . $row['room_name'] . '</td>

  <td align="left">' . $row['computer_name'] . '</td>
  </tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The computers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }
 
else if ($search=='P'   ) {


$query = "SELECT CONCAT(mf, ' ',model, ' ', computer_type) AS model,service_tag,CONCAT(first_name, ' ', last_name) as person,service_tag,computer_name FROM manufacturers,computer_models, computer_types, computers,locations,teachers WHERE computers.model_id=computer_models.model_id AND computer_models.mf_id=manufacturers.mf_id AND computer_types.ct_id = computer_models.ct_id AND computers.location_id=locations.location_id AND computers.teacher_id=teachers.teacher_id AND computers.teacher_id != 143 AND locations.school_id = $school_id ORDER BY teachers.last_name";

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo "<h1 align=\"center\">$school Computer Inventory</h1>";
  echo "<h3 align=\"center\">Total computers assigned to people = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Model</b></td>
  <td align="left"><b>Person</b></td>
  <td align="left"><b>Service Tag</b></td>
  <td align="left"><b>Computer Name</b></td>
</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['model'] . '</td>

  <td align="left">' . $row['person'] . '</td>

  <td align="left">' . $row['service_tag'] . '</td>

  <td align="left">' . $row['computer_name'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The computers could not be retrieved. 
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


