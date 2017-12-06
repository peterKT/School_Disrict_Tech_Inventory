
<?php
$page_title = 'Choose a Printer';

include('../includes/header_district_printers.html');

?>

<form action="district_select_printers5.php" method="post">
<fieldset><legend>Pick a Printer</legend>

<?php
require_once('../../mysql_connect_district_printers.php');

$query = "SELECT printer_model_id,CONCAT(type,' ',printer_no) AS model FROM district_printer_types, district_printer_models WHERE district_printer_types.pt_id = district_printer_models.pt_id order by printer_no";

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo "<p>The district contains $num different models of printers.</p>";
  echo '<select name="printer_id">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['printer_model_id'] . '">' . '  ' . $row['model'] .   '</option>\\n';
	
  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The printers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

include ('../includes/footer_district.html');
mysql_close();  
exit();
 }


echo '</fieldset>
<div align="center"><input type="submit" name="submit" value="Submit" /></div>
</form>' ;

?>




<?php


//DEFINE FUNCTION

function get_info($printer_id) {

	$query = "SELECT mid,CONCAT (toner.toner_no, ' ','(', toner_alias,')',' ',toner_color.toner_color) AS toner  FROM toner,toner_color,printer_toner_matrix WHERE printer_toner_matrix.printer_model_id = $printer_id AND printer_toner_matrix.toner_id = toner.toner_id AND toner.toner_color_id=toner_color.toner_color_id";

		$result = mysql_query($query);

		if ($result) {

		echo "<p>The printer id is $printer_id</p>";
		  echo '<p>The cartridge(s) required for your selected printer is (are):</p><table align="center" cellspacing="0" cellpadding="5"><tr><td align="left"><b>Edit</b></td> <td align="left"><b>Ink Cartridge Number (Alias) & Color</b></td></tr>';

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) { 	
			echo '<tr><td align="left"><a href=district_toner_correction.php?mid=' . $row['mid'] . '&printer_id=' . $printer_id . '>Edit</a></td><td align "left">' . $row['toner'] . '</td></tr>';


}
  echo '</table>';
				

} 	       else { echo "<p>You goofed</p>"; }


}		//CLOSE FUNCTION		 

		

if ($_POST['submit']) {					//OPEN SUBMIT


if (isset($_POST['printer_id']) ) {
	$printer_id=$_POST['printer_id'];

	 echo get_info($printer_id);
	}
	else

	{ echo "<p>You forgot to make a choice.</p>"; }

} //CLOSE THE SUBMIT


  	include ('../includes/footer_district.html');





?>






