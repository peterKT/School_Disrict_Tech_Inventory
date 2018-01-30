
<?php
$page_title = 'Correct Toner Info for a Printer';

include('../includes/header_district_printers.html');
 

/*
Receive two pieces of information from district_select_printers5.php: mid and printer_id. The mid will be used to correct the toner_id in printer_toner_matrix table and the printer_id will be used to display the printer that is getting its toner information updated.

*/
if (!isset($_POST['submitted']) ) {

if (  (isset($_GET['mid'])) && (is_numeric($_GET['mid'])) )  {		

	$mid = $_GET['mid'];

} else {
	
	echo '<p class="error">Sorry, the mid information submitted is not numeric.</p>'; 	
 
exit();	
}	


	
if (  (isset($_GET['printer_id'])) && (is_numeric($_GET['printer_id'])) )  {		

	$printer_id = $_GET['printer_id'];

} else {
	
	echo '<p class="error">Sorry, the printer_id information submitted is not numeric.</p>'; 	
 
exit();	
}	
	
}

		

if (isset($_POST['submitted'])) {					//OPEN SUBMIT

require_once('../../mysql_connect_inventory.php');

$mid=$_POST['replace_mid'];


	$printer_model = $_POST['printer_model'] ; 
	$old_toner = $_POST['old_toner'] ;	
	$new_toner_id = $_POST['new_toner_id'];



echo '<p>printer model is ' . $printer_model . '</p>' ;


echo '<p>Old toner was ' . $old_toner . '</p>' ;

echo '<p>The new toner ID and MID are '. $new_toner_id . ' and ' . $mid . '</p>';


	$query = "UPDATE printer_toner_matrix SET toner_id=$new_toner_id WHERE mid=$mid";
	$result = @mysql_query($query);
		if (mysql_affected_rows() == 1) {		//START CONDITION 2
			echo '<h1 id="mainhead">Replace Toner Info for a ' . $printer_model . '</h1>
			<p>The toner info has been corrected.</p>' ;
			echo "<p>The old toner was $old_toner and the new toner ID is $new_toner_id .</p>
			<p><br /><br /></p>";
			
$body = "The printer_toner_matrix table has been updated and toner for '$printer_model' --'$old_toner' -- has been replaced with a new cartridge with ID '$new_toner_id' .\n\n" ;
	mail ('ptitus@localhost', 'Change in printers', $body, 'From: district_toner_correction.php') ;


		} else  {

			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The toner could not be replaced due to a system error.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			}
	

mysql_close();  

include ('../includes/footer_district.html');	
exit();

}


?>


<form action="district_toner_correction.php" method="post">
<fieldset><legend>Correct a Printer</legend>



<?php
require_once('../../mysql_connect_inventory.php');


$query = "SELECT printer_models.printer_model_id,mid,CONCAT(type,' ',printer_no) AS model, CONCAT (toner.toner_no, ' ','(', toner_alias,')',toner_color.toner_color) AS toner FROM printer_types, printer_models, toner, toner_color, printer_toner_matrix WHERE mid=$mid AND printer_toner_matrix.printer_model_id=printer_models.printer_model_id AND printer_models.pt_id=printer_types.pt_id AND printer_toner_matrix.toner_id=toner.toner_id and toner.toner_color_id=toner_color.toner_color_id";

$result = @mysql_query($query);

if ($result) {
$row = mysql_fetch_array($result, MYSQL_ASSOC);

$printer_model=$row['model'];
$toner=$row['toner'];

//We now have values for printer_model_id, the printer model and the toner cartridge
//We already have printer_toner_matrix mid value
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The printers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

include ('../includes/footer_district.html');
mysql_close();  
exit();
 }
echo "<h2>Correct Toner Info</h2>
Printer model: $printer_model<br>
Erroneous cartridge: $toner<br><br>
Select a different cartridge and click the Submit button.";


	$query = "SELECT toner_id, CONCAT (toner_no, ' ','(', toner_alias,')',' ',toner_color) AS toner  FROM toner,toner_color WHERE toner.toner_color_id=toner_color.toner_color_id ORDER BY toner_no";


		$result = mysql_query($query);

		if ($result) {

  echo '<select name="new_toner_id">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['toner_id'] . '">' . '  ' . $row['toner'] .   '</option>\\n';
	
  	}  echo '</select>'; 
	  

mysql_free_result ($result);
} else {
  echo '<p class="error">The toner cartridges could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

 
echo '</fieldset>
<div align="center"><input type="submit" name="submit" value="Submit" /></div>
      <input type="hidden" name="submitted" value="TRUE" />
		<input type="hidden" name="replace_mid" value="' . $mid . '" />
		<input type="hidden" name="printer_model" value="' . $printer_model . '" /> 		
		<input type="hidden" name="old_toner" value="' . $toner . '" />';
	
include ('../includes/footer_district.html');		
echo '</form>' ;


?>






