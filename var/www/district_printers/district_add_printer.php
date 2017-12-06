
<?php
$page_title = 'Add a Printer';

include ('../includes/header_district_printers.html');


//PROCESS THE SUBMITTED INFORMATION FROM THE ADD PRINTER FORM BELOW

if (isset($_POST['add_submitted'])) {	


$location_id = $_POST['location_id'];


$room = $_POST['room'];

$add_id = $_POST['printer'];


require_once('../../mysql_connect_district_printers.php');		

	$query = "INSERT INTO district_printers(printer_model_id,location_id) VALUES( $add_id, $location_id)";
	$result = @mysql_query($query);
		if (mysql_affected_rows() == 1) {		//START CONDITION 2
			echo '<h1 id="mainhead">Add a Printer</h1>
			<p>The printer has been added.</p>
			<p><br /><br /></p>';
//SAMPLE CODE			
//$body = "The printer in '$remove_room' --'$remove_printer' -- has been removed .\n\n" ;

$body = "A new printer with ID number '$add_id' has been added to '$room' in location ID '$location_id'.\n\n" ;
	mail ('ptitus@localhost', 'Change in district_printers database', $body, 'From: district_add_printer.php') ;


		} else  {

			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The printer could not be replaced due to a system error.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			}
	

mysql_close();  

}
//Receive location ID and room name from district_edit_printers.php. Add printer to this location.

if (isset($_GET['lid']) ) {

$location_id=$_GET['lid'];
echo '<p>location id is ' . $location_id . '</p>' ;
$room=$_GET['room'];
echo '<p>room number or name is ' . $room . '</p>' ;

$school=$_GET['school'];
echo '<p>school is '. $school .'</p>' ;

echo '<form action="district_add_printer.php" method="post">
<fieldset><legend>Select the printer you wish to add to room ' . $room . '</legend>' ;

require_once('../../mysql_connect_district_printers.php');

$query = "SELECT printer_model_id, CONCAT(type, ' ',printer_no) AS model FROM district_printer_models, district_printer_types WHERE district_printer_models.pt_id=district_printer_types.pt_id ORDER BY printer_no";

$result = @mysql_query($query);

if ($result) {
 
  echo '<select name="printer">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['printer_model_id'] . '">' . '  ' . $row['model'] .   '</option>\\n';
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
<div align="center"><input type="submit" name="add_submitted" value="Submit" /></div>
<input type="hidden" name="add_submitted" value="TRUE" />
		<input type="hidden" name="location_id" value="' . $location_id . '" />
		<input type="hidden" name="room" value="' . $room . '" />';

echo '</form>' ;

}



//END ADD PRINTER

?>

</body>
</html>

