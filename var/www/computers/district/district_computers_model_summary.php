
<?php
$page_title = 'View District Computer Model Summary';
//from District Model Summary option on header
//Model query determined by data received from form at bottom
include ('../../includes/header_district_computers.html');

//Model Ids and Models are:
//6. Dimension 25. Emac 17. Gateway 32. GX1 33. GX110 30 GX300 26. iMac 21.5" 47. iMac 27"
//42. Latitude 2110 41. Lat 2120 13. Lat D505 28. Lat D610 15 Lat D630 36 Lat E5410
//39. Lat E5420 44. Lat E6410 45. Lat E6420 48. MacPro 27. MacBook 46/40. Optiplex 390
//8. Optiplex 745 7. 755 37. 780 43. 790 38. 990 1. GX240 9/10. GX260 3. GX270
//11/4. GX280 5. GX400 29/2. GX620 12. GX760 21. Power Edge 2650 22. PE 6600
//23. Precision 340 24. Precision 670 49. Precision T3500  



?>

<!--Form for Type Summary  -->
<h2>Reporting Schools: South, Laurel Plains, New City, Strawtown, West Nyack</h2>

<form action="district_computers_model_summary.php" method="post">
<fieldset><legend>Summarize district computer inventory by type</legend>
<h2>Use this form to review district computer inventory by type.</h2>

<?php
  require_once ('../../../mysql_connect_computers.php');
  
$query1 = "SELECT computer_type,ct_id FROM computer_types WHERE ct_id != 4 AND ct_id != 8" ;

$result1 = @mysql_query($query1);

if ($result1) {
 
  echo '<select name="search1">';
  while ($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
  	echo '<option value="' . $row1['ct_id'] . '">' . ' ' . $row1['computer_type'] . '</option>\\n';


  	}  echo '</select>'; echo '&nbsp;&nbsp;&nbsp;&nbsp'; 
	

mysql_free_result ($result1);
} else {
  echo '<p class="error">The model types could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query1 . '</p>';


mysql_close();  
exit();
 }



?>


</fieldset>
<div align="center"><input type="submit" name="submit1" value="Submit 
Info Request" /></div>
</form>

<?php
if ($_POST['submit1']) {					//OPEN SUBMIT 


if ( isset($_POST['search1'])   ) {	

$type_id = $_POST['search1'] ;

//Just for debugging

echo '<p>Type ID is ' . $type_id . '</p>';


$query4 = "SELECT computer_name,model,computer_type from computers,computer_models,computer_types where computers.model_id=computer_models.model_id and computer_models.ct_id=computer_types.ct_id AND computer_models.ct_id='$type_id' ORDER BY computer_type,model";

$result4 = @mysql_query($query4);
$num4 = mysql_num_rows($result4);

if ($result4) {

  echo "<h3 align=\"center\">Total computers of this type = $num4</h3>";

mysql_free_result ($result4);
} else {
  echo '<p class="error">The computers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }


} // CLOSE SELECT DROP DOWN LIST 1


}


?>

<!--Form for Model Summary  -->
<br />
<br />
<form action="district_computers_model_summary.php" method="post">
<fieldset><legend>Summarize district computer inventory by model</legend>
<h2>Use this form to review district computer inventory by model.</h2>

<?php
  require_once ('../../../mysql_connect_computers.php');



  
$query2 = "SELECT model,model_id,computer_type FROM computer_models,computer_types WHERE computer_models.ct_id=computer_types.ct_id ORDER BY computer_models.model" ;

$result2 = @mysql_query($query2);


if ($result2) {
 
  echo '<select name="search2">';
  while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
  	echo '<option value="' . $row2['model_id'] . '">' . ' ' . $row2['model'] . ' ' . $row2['computer_type'] . '</option>\\n';


  	}  echo '</select>'; 
	

mysql_free_result ($result2);
} else {
  echo '<p class="error">The models could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query2 . '</p>';


mysql_close();  
exit();
 }

?>


</fieldset>
<div align="center"><input type="submit" name="submit2" value="Submit 
Info Request" /></div>
</form>

<?php

if ($_POST['submit2']) {					//OPEN SUBMIT 2

//  require_once ('../../../mysql_connect_computers.php');




if ( isset($_POST['search2'])   ) {	

$model_id = $_POST['search2'] ;

//Just for debugging

echo '<p>Model ID is ' . $model_id . '</p>';


$query3 = "SELECT model, school, room, service_tag FROM computer_models, schools, rooms, computers WHERE computers.model_id='$model_id' AND computer_models.model_id=computers.model_id AND schools.school_id=computers.school_id AND rooms.room_id=computers.room_id ORDER BY room";

$result3 = @mysql_query($query3);
$num3 = mysql_num_rows($result3);

if ($result3) {

  echo "<h3 align=\"center\">Total computers of this model type = $num3</h3>";
 /* USE THIS CODE IF YOU WANT FULL DETAILS (not likely)
 
  echo "<h4> align=\"center\">Details Below</h4>";
  echo '<table align="center" cellspacing="0" cellpadding="5">

<tr>

  <td align="left"><b>Model</b></td>
<td align="left"><b>School</b></td>
<td align="left"><b>Room</b></td>
<td align="left"><b>Service Tag</b></td>


</tr>';

  while ($row3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row3['model'] . '</td>
  <td align="left">' . $row3['school'] . '</td>
  <td align="left">' . $row3['room'] . '</td>
  <td align="left">' . $row3['service_tag'] . '</td>
</tr>';
}
  echo '</table>';

*/

mysql_free_result ($result3);
} else {
  echo '<p class="error">The computers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }


} // CLOSE SELECT DROP DOWN LIST 2

} // CLOSE SUBMIT

?>

</body>
</html>


