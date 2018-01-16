
<?php
$page_title = 'View District Printer Model Summary';
//from District Printer Model Summary option on header
//Model query determined by data received from form at bottom

include('../includes/header_district_printers.html');



?>

<!--Form for Type Summary  -->
<h2>Reporting Schools: South, Laurel Plains, New City, Strawtown, West Nyack</h2>

<form action="district_printers_model_summary.php" method="post">
<fieldset><legend>Summarize district printer inventory by type</legend>
<h2>Use this form to review district printer inventory by type.</h2>

<?php
require_once('../../mysql_connect_district_printers.php');
  
$query1 = "SELECT type,pt_id FROM district_printer_types" ;

$result1 = @mysql_query($query1);

if ($result1) {
 
  echo '<select name="search1">';
  while ($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
  	echo '<option value="' . $row1['pt_id'] . '">' . ' ' . $row1['type'] . '</option>\\n';


  	}  echo '</select>'; echo '&nbsp;&nbsp;&nbsp;&nbsp'; 
	

mysql_free_result ($result1);
} else {
  echo '<p class="error">The printer types could not be retrieved. 
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


$query4 = "SELECT type FROM district_printer_types,district_printer_models,district_printers WHERE district_printers.printer_model_id=district_printer_models.printer_model_id AND district_printer_models.pt_id=district_printer_types.pt_id AND district_printer_types.pt_id='$type_id' ";

$result4 = @mysql_query($query4);
$num4 = mysql_num_rows($result4);

if ($result4) {

  echo "<h3 align=\"center\">Total printers of this type = $num4</h3>";

mysql_free_result ($result4);
} else {
  echo '<p class="error">The printers could not be retrieved. 
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
<form action="district_printers_model_summary.php" method="post">
<fieldset><legend>Summarize district printer inventory by model</legend>
<h2>Use this form to review district printer inventory by model.</h2>

<?php

//  require_once ('../../../mysql_connect_computers.php');


$query2 = "SELECT printer_model_id, CONCAT(type,' ', printer_no) AS model FROM district_printer_types, district_printer_models WHERE district_printer_types.pt_id = district_printer_models.pt_id order by printer_no";



$result2 = @mysql_query($query2);


if ($result2) {
 
  echo '<select name="search2">';
  while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
  	echo '<option value="' . $row2['printer_model_id'] . '">' . ' ' . $row2['model'] . '</option>\\n';


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


$query3 = "SELECT district_printer_models.printer_model_id, printer_no FROM district_printer_models,district_printers WHERE district_printers.printer_model_id=district_printer_models.printer_model_id AND district_printers.printer_model_id='$model_id'";

$result3 = @mysql_query($query3);
$num3 = mysql_num_rows($result3);

if ($result3) {

  echo "<h3 align=\"center\">Total printers of this model = $num3</h3>";
  
  


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


