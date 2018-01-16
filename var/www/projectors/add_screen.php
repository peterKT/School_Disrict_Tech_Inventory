<?php # add_screen.php



$page_title = 'Add Screen';
include ('../includes/header_projectors.html');

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

echo "School ID is $school_id";


//Set up the school name
  require_once ('../../mysql_connect_inventory.php');

$query = "SELECT school FROM schools WHERE school_id = $school_id";


$result = @mysql_query($query);

while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) {
$school = $row['school'];
}

mysql_free_result ($result);


if (isset($_POST['submitted']) ) {		// START SUBMIT, MUST CLOSE
  $errors = array();

						// START ERROR COLLECTION
if (empty($_POST['screen'])) {	
  $errors[] = 'You must enter a screen.';
} else {
  $screen = $_POST['screen'];	
}

if (empty($_POST['mount'])) {	
  $errors[] = 'You must enter a mount.';
} else {
  $mount = $_POST['mount'];	
}

//GET ROOM ID

if (isset($_POST['location']) ) {
	
	$location = $_POST['location'] ;
	echo "<p>You entered location ID $location</p>";	
	} else { $errors[] = 'Your location ID value is messed up.';
	} //END GET ROOM ID


if (empty($errors)) {						// START IF EMPTY ERRORS, MUST CLOSE

require_once ('../../mysql_connect_inventory.php');

//CHECK FOR EXISTING SCREEN

$query = "SELECT screen_id FROM smartboards WHERE smartboards.location_id=$location";

$result = mysql_query($query);


if (mysql_num_rows($result)==0) {		   		//CHECK FOR CURRENT ROOM STATUS			
	
echo "<p>This location does not currently have a screen</p>";

	} else {   							
	echo "<p>Warning, this room already has a screen. You are adding another one.</p>";
		
	}			

//MAKE THE QUERY IF OK
//Enter the printer model and its type

if ( isset($_POST['serial']) ) {
	$serial = $_POST['serial'] ;
 

  $query = "INSERT INTO smartboards (screen_id,serial_no,mount_id,location_id) VALUES
  ('$screen','$serial','$mount','$location')";

$result = mysql_query($query); 
if ($result) {
  
  echo '<h1 id="mainhead">Thank you!</h1>
  <p>Your screen type and location went through.</p><p>
  <br /></p>';
  
  $body = "A new screen with ID '$screen_id' and serial number $serial has been added with location ID $locatin in $school .\n\n" ;
	mail ('ptitus@localhost', 'Change in smartboards table', $body, 'From: add_screen.php');

 exit();
  }


 else {
  echo '<h1 id="mainhead">System Error</h1>
  <p class="error">Your screen data could not be entered due to a system error. No data was sent.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  echo '</body></html>';

  exit();
}


} else {

 $query = "INSERT INTO smartboards (screen_id,mount_id,location_id) VALUES
  ('$screen','$mount','$location')";


$result = mysql_query($query); 
if ($result) {
  
  echo '<h1 id="mainhead">Thank you!</h1>
  <p>Your screen type and location went through.</p><p>
  <br /></p>';
 
echo "<p>The serial number was left blank.  If this is a Smartboard, you can add this information later using the Edit Screens feature</p>" ;

 
  $body = "A new screen with ID '$screen_id' been added with locatin ID $location in $school .\n\n" ;
	mail ('ptitus@localhost', 'Change in screens table', $body, 'From: add_screen.php');
  
 exit();

}


 else {
  echo '<h1 id="mainhead">System Error</h1>
  <p class="error">Your screen data could not be entered due to a system error. No data was sent.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  echo '</body></html>';

  exit();
}

}

} else { 						//  Close If empty errors
  	echo '<h1 id="mainhead">Error!</h1>
  	<p class="error">The following error(s) occurred:<br />';
  	foreach ($errors as $msg) {
  		echo " -$msg<br />\n";
  		}
	
  	echo '</p><p>Please try again.</p><p><br /></p>' ;
 
	exit();
}

										//close the submit
}


?>

<h2>Add Screen to Room</h2>
<form action="add_screen.php" method="post">
<fieldset><legend>Add Screen to a Room</legend>
<!--BEGIN SELECT SCREEN AND MOUNT  -->

<h3>Select Type of Screen</h3>
<?php

require_once ('../../mysql_connect_inventory.php');

$query = "SELECT screen_id,screen FROM screens" ;

$result = @mysql_query($query);


if ($result) {
 
  echo '<select name="screen">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['screen_id'] . '">' . ' ' . $row['screen'] . '</option>\\n';


  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The screens could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

$query = "SELECT mount_id,mount FROM mounts" ;

$result = @mysql_query($query);


if ($result) {
 
  echo '<select name="mount">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['mount_id'] . '">' . ' ' . $row['mount'] . '</option>\\n';


  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The mount types could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

?>

<br><br>
<p>Please enter the serial number if known</p>

<p>Serial Number: <input type="text" name="serial" size="16" maxlength="16" value="<?php 

//if (isset($_POST['room'])) echo $_POST['room'];
if (isset($serial)) echo $serial;

?>" /></p>

<!-- END SELECT SCREEN -->



<!--BEGIN SELECT ROOM -->

<h3>Identify Location</h3>
<?php

echo "<h4>All rooms in $school</h4>";
//echo "School ID is $school_id <br /><br />";	
	
$query = "SELECT locations.room_name_id,room_names.room_name,locations.location_id FROM locations,room_names WHERE locations.school_id=$school_id AND locations.room_name_id=room_names.room_name_id ORDER BY room_name";

$result = @mysql_query($query);

if ($result) {
 
  echo '<select name="location">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['location_id'] . '">' . ' ' . $row['room_name'] . '</option>\\n';

  	}  echo '</select>'; 
	
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The rooms could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }
	
?>

<br /><br /><br /><br />



<!-- END SELECT ROOM AND SCHOOL -->

</fieldset>

<div align="center">  
<input type="submit" name="submit" value="Submit"/></div>
<input type="hidden" name="submitted" value="TRUE"/>



<?php



echo 	'<input type="hidden" name="schools" value="' . $school_id . '" />';
echo '</form>';


include ('../includes/footer.html');
?>
