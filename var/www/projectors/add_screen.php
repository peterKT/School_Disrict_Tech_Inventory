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
  require_once ('../../mysql_connect_projectors.php');

$query = "SELECT school FROM schools WHERE school_id = $school_id";


$result = @mysql_query($query);

while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) {
$school = $row['school'];

}

mysql_free_result ($result);










if (isset($_POST['submitted']) ) {				// START SUBMIT, MUST CLOSE
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



//SELECT ROOM

//ONLY FOR SCHOOL_ID 11

if ($school_id == 11) {

if ($_POST['room1'] != '152') {
	$room1 = $_POST['room1'] ;
	} 

if ($_POST['room2'] != '153') {
	$room2 = $_POST['room2'] ;
	}

if ($_POST['room3'] != '154') {
	$room3 = $_POST['room3'] ;
	}


if ($room1 && $room2 || $room1 && $room3 || $room2 && $room3) {
	echo "You selected $room1 and $room2 and $room3<br>";
	$errors[] = 'Sorry, only one room may be selected.' ;
	}
	

if ( !empty($room1) ) {
	$room_id = $room1 ; 
	}

elseif (!empty($room2) ) {
	$room_id = $room2;
	}

elseif (!empty($room3) ) {
	$room_id = $room3 ;
	}



else { $errors[] = 'You must select a room.';
	}



}

//Select room for all other buildings

else {

if (isset($_POST['room0']) ) {
	
	$room_id = $_POST['room0'] ;
	echo "You entered room ID $room_id";	
	} else { $errors[] = 'Your room0 value is messed up.';
}
}


//END SELECT ROOM


if (empty($errors)) {						// START IF EMPTY ERRORS, MUST CLOSE

  require_once ('../../mysql_connect_projectors.php');

//CHECK FOR EXISTING PROJECTOR

$query = "SELECT screen_id FROM smartboards WHERE smartboards.school_id=$school_id AND smartboards.room_id=$room_id";
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
 

  $query = "INSERT INTO smartboards (screen_id,mount_id,school_id,room_id,serial_no) VALUES
  ('$screen','$mount','$school_id','$room_id','$serial')";

$result = mysql_query($query); 
if ($result) {
  
  echo '<h1 id="mainhead">Thank you!</h1>
  <p>Your screen type and location went through.</p><p>
  <br /></p>';
  
  $body = "A new screen with ID '$screen_id' has been added to room_id $room_id in $school .\n\n" ;
	mail ('ptitus@localhost', 'Change in projectors database', $body, 'From: add_screen.php');

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

  $query = "INSERT INTO smartboards (screen_id,mount_id,school_id,room_id) VALUES
  ('$screen','$mount','$school_id','$room_id')";

$result = mysql_query($query); 
if ($result) {
  
  echo '<h1 id="mainhead">Thank you!</h1>
  <p>Your screen type and location went through.</p><p>
  <br /></p>';
 
echo "<p>The serial number was left blank.  If this is a Smartboard, you can add this information later using the Edit Screens feature</p>" ;

 
  $body = "A new screen with ID '$screen_id' been added to room_id $room_id in $school .\n\n" ;
	mail ('ptitus@localhost', 'Change in projectors database', $body, 'From: add_screen.php');
  
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

require_once ('../../mysql_connect_projectors.php');

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


if ($school_id == 11) {

echo '<h4>First Floor</h4>';

$query = "SELECT room, room_id FROM rooms WHERE school_id=11 AND room LIKE '1%' ORDER BY room" ;

$result = @mysql_query($query);

if ($result) {
 
  echo '<select name="room1">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['room_id'] . '">' . ' ' . $row['room'] . '</option>\\n';

//	echo '<option value="' . $row['printer_id'] . '">' . '  ' . $row['type'] .  $row['model'] .   '</option>\\n';

  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The rooms could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

// SECOND FLOOR

echo '<h4>Second Floor</h4>';
$query = "SELECT room, room_id FROM rooms WHERE school_id = $school_id AND room LIKE '2%' ORDER BY room" ;

$result = @mysql_query($query);


if ($result) {
 
  echo '<select name="room2">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['room_id'] . '">' . ' ' . $row['room'] . '</option>\\n';

//	echo '<option value="' . $row['printer_id'] . '">' . '  ' . $row['type'] .  $row['model'] .   '</option>\\n';

  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The rooms could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

// THIRD FLOOR

echo '<h4>Third Floor</h4>';
$query = "SELECT room, room_id FROM rooms WHERE school_id=$school_id AND room LIKE '3%' ORDER BY room" ;

$result = @mysql_query($query);


if ($result) {
 
  echo '<select name="room3">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['room_id'] . '">' . ' ' . $row['room'] . '</option>\\n';

//	echo '<option value="' . $row['printer_id'] . '">' . '  ' . $row['type'] .  $row['model'] .   '</option>\\n';

  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The rooms could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

}	//END SELECT ROOMS IF SCHOOL IS ID=11 (MODIFY FOR OTHER MULTI-STORY BUILDINGS)

//NOW CREATE ROOM SELECTIONS FOR SINGLE-FLOOR BUILDINGS BASED ON SCHOOL_ID

else {

echo "<h4>All rooms in $school</h4>";
//echo "School ID is $school_id <br /><br />";	
	
$query = "SELECT room_id,room FROM rooms WHERE school_id=$school_id ORDER BY room" ;

$result = @mysql_query($query);

if ($result) {
 
  echo '<select name="room0">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['room_id'] . '">' . ' ' . $row['room'] . '</option>\\n';

  	}  echo '</select>'; 
	
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The rooms could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }
	


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