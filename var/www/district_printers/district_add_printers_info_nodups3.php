<?php #Add printer to database



$page_title = 'Submit Printer Information';

include ('../includes/header_district_printers.html');

if (isset($_POST['submitted']) ) {				// START SUBMIT, MUST CLOSE
  $errors = array();

if (empty($_POST['model'])) {					// START ERROR COLLECTION
  $errors[] = 'You forgot to enter a model number.';
} else {
  $mn = trim($_POST['model']);	
  $pt = $_POST['type'] ;
}

if (empty($_POST['yes']) && empty($_POST['no']) ) {			
  $errors[] = 'You forgot to say whether you have ink info.';
} elseif ( !empty($_POST['yes']) && !empty($_POST['no']) ) {			
  $errors[] = 'Please pick either yes or no on ink question';
} 

if ( isset($_POST['cyan']) || isset($_POST['magenta']) || isset($_POST['yellow']) AND
	empty($_POST['magenta']) || empty($_POST['cyan']) || empty($_POST['yellow'])    ) 
	{  $errors[] = 'You didn\'t enter all colors.';
	echo '<h1 id="mainhead">Error!</h1>
  	<p class="error">The following error(s) occurred:<br />';
  		foreach ($errors as $msg) {
  			echo " -$msg<br />\n";
  	 		echo '</p><p>Please try again.</p><p><br /></p>';
			exit();
			}
	} else {
  
						// START IF EMPTY ERRORS

  	$yes = ($_POST['yes']);	
  	$no = ($_POST['no']) ;
	}


if (empty($errors)) {						// START IF EMPTY ERRORS, MUST CLOSE


require_once('../../mysql_connect_inventory.php');
//CHECK FOR DUPLICATE PRINTER MODEL

$query = "SELECT printer_model_id FROM printer_models WHERE printer_no='$mn'";
$result = mysql_query($query);
if (mysql_num_rows($result)==0) {					// START NO DUPS, MUST CLOSE
	
echo "<p>This printer is not a duplicate</p>";

//MAKE THE QUERY IF OK
//Enter the printer model and its type


  $query = "INSERT INTO printer_models (printer_no,pt_id) VALUES
  ('$mn','$pt')";

$result = mysql_query($query); 
if ($result) {
  
  echo '<h1 id="mainhead">Thank you!</h1>
  <p>Your printer model and type data went through--no duplicates.</p><p>
  <br /></p>';
  
  
  }

 else {
  echo '<h1 id="mainhead">System Error</h1>
  <p class="error">Your model/type data could not be entered due to a system error. No other data was sent.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  echo '</body></html>';

  exit();
}

} else {   							//CLOSE NOT A DUPLICATE
echo "<p>Sorry, that printer is already in the database.</p>";

	
}


				//Enter the printer's ink cartridge number(s) and its color


if (isset($_POST['yes']) )	{
	$query = "SELECT type FROM printer_types,printer_models WHERE printer_types.pt_id = printer_models.pt_id and printer_models.printer_no = '$mn'" ;
	$result=mysql_query($query);
	while ($row=mysql_fetch_array($result,MYSQL_ASSOC) ) {
	echo '<h2>Add New Ink Cartridge Information</h2>
		<h3>Model ' . $row['type'] . ' ' . $mn . '</h3>' ;
	}
	echo '	
	<form action="district_add_ink.php" method="post">
	<fieldset><legend>Enter ink information for your printer in the fields below</legend>

	
	<p>Black Ink Cartridge No. (i.e. C4096A): <input type="text" name="black" size="10" maxlength="10"  /></p>
	<p> &nbsp &nbsp Alias (if any) (i.e. 96A): <input type="text" name="alias1" size="10" maxlength="10" /></p>

	<p>Multicolor Ink Cartridge No.: <input type="text" name="multi" size="10" maxlength="10" /></p>
	<p> &nbsp &nbsp Alias: <input type="text" name="alias2" size="10" maxlength="10"  /></p>

	<p>Cyan Ink Cartridge No.: <input type="text" name="cyan" size="10" maxlength="10" /></p>
	<p>Yellow Ink Cartridge No.: <input type="text" name="yellow" size="10" maxlength="10"  /></p>
	<p>Magenta Ink Cartridge No.: <input type="text" name="magenta" size="10" maxlength = "10"  /></p>
	</fieldset>

	<div align="center">  
	<input type="hidden" name="model" value="' . $mn . '"/>
	<input type="submit" name="submit" value="Submit"/></div>
	<input type="hidden" name="submitted" value="TRUE"/>
	</form>
	</body>
	</html>' ;
	exit();
	}
								//  Close If empty errors

} else { 
  echo '<h1 id="mainhead">Error!</h1>
  <p class="error">The following error(s) occurred:<br />';
  foreach ($errors as $msg) {
  	echo " -$msg<br />\n";
  }
  echo '</p><p>Please try again.</p><p><br /></p>' ;
}
										//close the submit
}

?>

<h2>Add New Printers and Ink</h2>
<form action="district_add_printers_info_nodups3.php" method="post">
<fieldset><legend>Enter printer and ink information in the fields below</legend>

<p>Model (i.e. 4MV, 3600n): <input type="text" name="model" size="10" maxlength="12" value="<?php if 
(isset($_POST['model'])) echo $_POST['model'];
?>" /></p>

<p>Type: <select name="type"><option value="1">LaserJet</option>
<option value="2">Business InkJet</option>
<option value="3">Color LaserJet</option>
<option value="4">Color DesignJet</option>
<option value="5">DeskJet</option>
<option value="6">Multifunction</option></select>
</p>

<p>Would you like to add the ink cartridge information for this printer?</p>

<p> <input type="radio" name="yes" value="yes" />Yes</p>
<p> <input type="radio" name="no" value="no" />No</p>


</fieldset>

<div align="center">  
<input type="submit" name="submit" value="Submit"/></div>
<input type="hidden" name="submitted" value="TRUE"/>
</form>


</body>
</html>


