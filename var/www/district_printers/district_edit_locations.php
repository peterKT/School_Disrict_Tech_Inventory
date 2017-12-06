
<?php
$page_title = 'Edit the printer locations';


include('../includes/header_district_printers.html');

/*

This section processes the data submitted at the end of this form. Go down for data received from
district_edit_printers

*/

if (isset($_POST['submitted'])) {	


$remove_prid=$_POST['remove_prid'];


	$same_room = $_POST['same_room'] ; 
	$same_school = $_POST['same_school'] ;	
	$old_model = $_POST['old_model'];


echo '<p>printer id is ' . $remove_prid . '</p>' ;


echo '<p>room is ' . $remove_room . '</p>' ;

echo '<p>printer to be removed from room '. $same_room . ' is ' . $old_model . '</p>';




require_once('../../mysql_connect_district_printers.php');	//OPEN SUBMITTED
	if ($_POST['sure'] == 'Yes' ) {					//START CONDITION 1
		$query = "DELETE FROM district_printers WHERE printer_id = $remove_prid";
		$result = @mysql_query($query);
			if (mysql_affected_rows() == 1) {		//START CONDITION 2
				echo '<h1 id="mainhead">Remove a Printer at ' . $same_school . '</h1>
				<p>The ' . $old_model . 'printer has been removed from location ' . $same_room . '.</p>
				<p><br /><br /></p>';

$body = "The printer in '$same_room' --'$old_model' -- has been removed from ' . $same_school . ' .\n\n" ;
	mail ('ptitus@localhost', 'Change in district_printers database', $body, 'From: district_edit_locations.php') ;


			} else  {

				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The printer could not be removed due to a system error.</p>';
				echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			}
									//CLOSE CONDITION 2

	} else {							// IF CONDITION 1 NOT MET
		echo '<h1 id="mainhead">Remove a Printer</h1>
		<p>The printer has NOT been removed.</p>
		<p><br /><br /></p>';
		}							//CLOSE CONDITION 1


mysql_close();  

}

// END REMOVE

//START REPLACE PRINTER FORM

elseif (isset($_POST['replace_submitted'])) {	



$replace_prid = $_POST['replace_prid'];

$new_printer = $_POST['new_printer'];

	$same_room = $_POST['same_room'] ; 
	$same_school = $_POST['same_school'] ;	
	$old_model = $_POST['old_model'];



require_once('../../mysql_connect_district_printers.php');		

	$query = "UPDATE district_printers SET printer_model_id=$new_printer WHERE printer_id=$replace_prid";
	$result = @mysql_query($query);
		if (mysql_affected_rows() == 1) {		//START CONDITION 2
			echo '<h1 id="mainhead">Replace a Printer at ' . $same_school . '</h1>
			<p>The printer has been replaced.</p>' ;
			echo "<p>The old model was $old_model and the room is $same_room .</p>
			<p><br /><br /></p>";
			
$body = "The printer in '$same_room' --'$old_model' -- has been replaced with printer_model_id: '$new_printer' .\n\n" ;
	mail ('ptitus@localhost', 'Change in district printers database', $body, 'From: district_edit_locations.php') ;


		} else  {

			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The printer could not be replaced due to a system error.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			}
	

mysql_close();  

}




elseif (  (isset($_GET['replace_prid']))  && (is_numeric($_GET['replace_prid'])) )  {		

	$replace_prid = $_GET['replace_prid'];
	$same_room=$_GET['room'] ; 
	$same_school=$_GET['school'] ;	
	$old_model = $_GET['model'];

	echo '<p>replace prid is ' . $replace_prid .'</p>';



echo '<form action="district_edit_locations.php" method="post">
<fieldset><legend>Select a replacement printer for the ' .  $old_model . ' in location ' . $same_room . '</legend>' ;

//OK Up to Here 11/20
//Form needs to send printer ID and new model ID so the old model ID can be replaced in the district_printers table.

require_once('../../mysql_connect_district_printers.php');



$query = "SELECT printer_model_id, CONCAT(type, ' ',printer_no) AS model FROM district_printer_models, district_printer_types WHERE district_printer_models.pt_id=district_printer_types.pt_id ORDER BY printer_no";


$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
 
  echo '<select name="new_printer">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['printer_model_id'] . '">' . '  ' .  $row['model'] .   '</option>\\n';
  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The printers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

echo '</fieldset>
<div align="center"><input type="submit" name="replace_submitted" value="Submit" /></div>
      <input type="hidden" name="replace_submitted" value="TRUE" />
		<input type="hidden" name="replace_prid" value="' . $replace_prid . '" />
		<input type="hidden" name="same_room" value="' . $same_room . '" /> 		
		<input type="hidden" name="same_school" value="' . $same_school . '" />
		<input type="hidden" name="old_model" value="' . $old_model . '" />' ;		
		
echo '</form>' ;

}



//END REPLACE PRINTER

//BEGIN REMOVE PRINTER

elseif (  (isset($_GET['remove_prid']))  && (is_numeric($_GET['remove_prid'])) )  {		//CHECK FOR CORRECT INPUT




	$remove_prid = $_GET['remove_prid'];
	$same_room=$_GET['room'] ; 
	$same_school=$_GET['school'] ;	
	$old_model = $_GET['model'];

	echo '<p>remove prid is ' . $remove_prid .'</p>';


echo '<p>room is ' . $same_room . '</p>';
echo '<p>old printer is ' . $old_model .'</p>';



require_once('../../mysql_connect_district_printers.php');



	$query = "SELECT * from district_printers where printer_id = $remove_prid ";

	$result = @mysql_query($query);

	if (mysql_num_rows($result) == 1) {				//OPEN CONDITION 3
		$row = mysql_fetch_array($result, MYSQL_NUM);
		echo '<h2>Remove a Printer</h2>
		<form action="district_edit_locations.php" method="post">
		
		<h3>Printer: ' . $old_model . '</h3>
		<p>Are you sure you want to remove this printer from room ' . $same_room . '?<br />

		<input type="radio" name="sure" value="Yes" />Yes

		<input type="radio" name="sure" value="No" checked="checked" />No	
		
		</p>

		<input type="submit" name="submit" value="Submit" /></p>
		<input type="hidden" name="submitted" value="TRUE" />
		<input type="hidden" name="remove_prid" value="'. $remove_prid . '" />
		<input type="hidden" name="same_room" value="' . $same_room . '" /> 		
		<input type="hidden" name="same_school" value="' . $same_school . '" />
		<input type="hidden" name="old_model" value="' . $old_model . '" />
	
		</form>';


mysql_free_result ($result);
mysql_close();  



	} else {							//CLOSE CONDITION 3 

		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p>
		<p><br /><br /></p>';

		}

//END REMOVE PRINTER



}     else {
	echo '<h1 id="mainhead">
	Page Error</h1>
	<p class="error">This here page has been accessed in error.</p><p><br /><br /></p>';

exit();
}


?>

</body>
</html>
