<?php # district_add_computers.php FROM district_select_school3.php
//2018a


$page_title = 'Add Computers';

include ('../../includes/header_district_computers.html');


  if ( isset($_POST['schools']) ) {

    		$school_id = $_POST['schools'] ;
  			}
	elseif ( isset( $_GET['schools'] ) ) {
			$school_id = $_GET['schools'] ;
			}
	else {

  	echo "<p>You neglected to specify a school.</p>" ;	
  	exit();
	}

require_once ('../../../mysql_connect_inventory.php'); 

//Set up the school name


$query = "SELECT school FROM schools WHERE school_id = $school_id";


$result = @mysql_query($query);

while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) {
$school = $row['school'];

}

mysql_free_result ($result);


if (isset($_POST['submitted']) ) {				// START SUBMIT, MUST CLOSE
  $errors = array();

if (empty($_POST['model'])) {					// START ERROR COLLECTION
  $errors[] = 'You must enter a model.';
} else {
  $modelid = $_POST['model'];	
}

if (isset($_POST['location']) ) {
	
	$location_id = $_POST['location'] ;
	echo "You entered location ID $location_id";	
	} else { $errors[] = 'Your location value is messed up.';
}

if ( !empty($_POST['cname'] ) ) {
	$cname = $_POST['cname'] ;
	} else {
	echo '<p>You did not enter a computer name so it will be set to UNKNOWN. Please correct ASAP.</p>';
}

if (empty($_POST['stag'])) {	
  $errors[] = 'You must enter a service tag.';
} else {
  $stag = $_POST['stag'];	
}

if (empty($errors)) {						// START IF EMPTY ERRORS, MUST CLOSE


//CHECK FOR DUPLICATE SERVICE TAG

$query = "SELECT computer_id FROM computers WHERE ( service_tag='$stag') ";
$result = mysql_query($query);
if (mysql_num_rows($result)==0) {					// START NO DUPS, MUST CLOSE
	
echo "<p>This computer is not a duplicate</p>";

//mysql_free_result ($result);

echo "<h1 id=\"mainhead\" align=\"center\">$school Computer Inventory</h1>";
echo "<h3 align=\"center\">Add computers to rooms</h3>";


//Make sure room_id is valid

$query = "SELECT room_name FROM room_names,locations WHERE location_id=$location_id and locations.room_name_id=room_names.room_name_id ";
$result = mysql_query($query) ;
if ($result) {
	 $row = mysql_fetch_array($result, MYSQL_ASSOC) ;
$room = $row['room_name'] ;
	} else {

  	echo '<h1 id="mainhead">System Error</h1>
  	<p class="error">Your location could not be determined due to a system error.</p>';
  	echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
  	echo '</body></html>';
  	exit();
	}


//MAKE THE QUERY IF OK

  $query = "INSERT INTO computers (model_id,computer_name,service_tag,location_id) VALUES
  ('$modelid','$cname','$stag','$location_id')";

$result = mysql_query($query); 
if ($result) {
  
  echo '<h1 id="mainhead">Thank you!</h1>
  <p>Your computer model and associated data went through--no duplicates.</p><p>
  	You entered location ID ' . $location_id  . '	
  <br /></p>';
  echo "The room  was $room <br />";
  
  $body = "A new computer with ID '$modelid' and service tag '$stag' has been added to $school .\n\n" ;
	mail ('user@localhost', 'Change in COMPUTER database', $body, 'From: district_add_computers.php');
  }


 else {
  echo '<h1 id="mainhead">System Error</h1>
  <p class="error">Your model data could not be entered due to a system error. No data was sent.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  echo '</body></html>';

  exit();
}

} else {   							//CLOSE NOT A DUPLICATE
	echo "<p>Sorry, that service tag or computer name is already in the database.</p>";
	mysql_close();  
	exit();
}

} else      { 						//  Close If empty errors
  	echo '<h1 id="mainhead">Error!</h1>
  	<p class="error">The following error(s) occurred:<br />';
  	foreach ($errors as $msg) {
  		echo " -$msg<br />\n";
  		}
  	echo '</p><p>Please try again.</p><p><br /></p>' ;
	mysql_close();  
	exit();
}

										//close the submit
}


?>

<?php

echo "<h2>Add Computers to $school Inventory</h2>
<h3>Use \"Assign Laptops\" to associate mobile computers with people. This form only allows 
placement in rooms or laptop carts</h3>


School ID = $school_id" ;

?>

<form action="district_add_computers.php" method="post">
<fieldset><legend>Add Computers to Inventory</legend>
<!--BEGIN SELECT COMPUTER MODEL  -->

<h3>Select Computer Model</h3>
<?php

require_once ('../../../mysql_connect_inventory.php');

// Pick the model

$query = "SELECT mf,model,model_id,computer_models.mf_id, computer_type FROM computer_models,manufacturers,computer_types WHERE computer_models.mf_id=manufacturers.mf_id AND computer_models.ct_id=computer_types.ct_id ORDER BY computer_models.model" ;

$result = @mysql_query($query);


if ($result) {

  echo '<select name="model">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['model_id'] . '">' . $row['mf'] . '  ' . $row['model'] . ' ' . $row['computer_type'] . '</option>\\n';

  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The models could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

?>


<!-- END SELECT COMPUTER MODEL -->



<!--BEGIN SELECT ROOM  -->

<h3>Identify Location</h3>
<?php



echo "<h4>All rooms in $school</h4>";
//echo "School ID is $school_id <br /><br />";	
	
$query = "SELECT locations.location_id,room_names.room_name_id,room_name FROM locations,room_names WHERE locations.school_id=$school_id AND locations.room_name_id=room_names.room_name_id ORDER BY room_name" ;

$result = @mysql_query($query);

if ($result) {
 
  echo '<select name="location">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['location_id'] . '">' . ' ' . $row['room_name'] . '</option>\\n';
       // echo '<p>You selected ' . $row[0] . ' for location ID and ' . $row['room_name'] . ' for the room.</p>'; 
  	}  echo '</select>'; 
	
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The rooms could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }
	


// }


?>

<br /><br /><br /><br />



<!-- END SELECT ROOM -->

<p>Computer Name: <input type="text" name="cname" size="20" maxlength="24" value="<?php if 
(isset($_POST['cname'])) echo $_POST['cname'];
?>" /></p>


<p>Service Tag: <input type="text" name="stag" size="16" maxlength="16" value="<?php if 
(isset($_POST['stag'])) echo $_POST['stag'];
?>" /></p>


<!--
<p>Asset Tag: <input type="text" name="atag" size="20" maxlength="24" value="<?php if 
(isset($_POST['atag'])) echo $_POST['atag'];
?>" /></p>
-->


</fieldset>

<div align="center">  
<input type="submit" name="submit" value="Submit"/></div>
<input type="hidden" name="submitted" value="TRUE"/>
<?php
echo 	'<input type="hidden" name="schools" value="' . $school_id . '" />';

?>

</form>




<?php
include ('../../includes/footer.html');
?>
