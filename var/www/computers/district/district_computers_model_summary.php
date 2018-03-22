
<?php
//2018a
$page_title = 'View District Computer Model Summary';
//from District Model Summary option on header
//Model query determined by data received from form at bottom
include ('../../includes/header_district_computers.html');


?>

<!--Form for Type Summary  -->

<form action="district_computers_model_summary.php" method="post">
<fieldset><legend>Summarize district computer inventory by type</legend>
<h2>Use this form to review district computer inventory by type.</h2>

<?php
  require_once ('../../../mysql_connect_inventory.php');
  
$query1 = "SELECT computer_type,ct_id FROM computer_types WHERE ct_id != 4" ;

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
if (isset($_POST['submit1'])) {					//OPEN SUBMIT 


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
  require_once ('../../../mysql_connect_inventory.php');

 
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

if (isset($_POST['submit2'])) {					//OPEN SUBMIT 2

if ( isset($_POST['search2'])   ) {	

$model_id = $_POST['search2'] ;

//Just for debugging

echo '<p>Model ID is ' . $model_id . '</p>';


$query3 = "SELECT model, service_tag FROM computer_models, computers WHERE computers.model_id='$model_id' AND computer_models.model_id=computers.model_id";

$result3 = @mysql_query($query3);
$num3 = mysql_num_rows($result3);

if ($result3) {

  echo "<h3 align=\"center\">Total computers of this model type = $num3</h3>";


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


