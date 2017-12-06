<?php # add_projector.php



$page_title = 'Add Projector';
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
 

if (empty($_POST['model'])) {	
  $errors[] = 'You must enter a model.';
} else {
  $model = $_POST['model'];	
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


//Determine the room_id value based on information submitted in the form





//CHECK FOR EXISTING PROJECTOR

$query = "SELECT projector_id FROM projectors WHERE projectors.school_id=$school_id AND projectors.room_id=$room_id";
$result = mysql_query($query);


if (mysql_num_rows($result)==0) {		   		//CHECK FOR CURRENT ROOM STATUS			
	
echo "<p>This location does not currently have a projector</p>";

	} else {   							
	echo "<p>Warning, this room already has a projector. You are adding a second one.</p>";
		
	}			




//MAKE THE QUERY IF OK
//Enter the printer model and its type


  $query = "INSERT INTO projectors (model_id,school_id,room_id) VALUES
  ('$model','$school_id','$room_id')";

$result = mysql_query($query); 
if ($result) {
  
  echo '<h1 id="mainhead">Thank you!</h1>
  <p>Your projector model and location went through.</p><p>
  <br /></p>';
  
  $body = "A new projector with ID '$model' been added to room $room in $school.\n\n" ;
	mail ('ptitus@localhost', 'Change in projectors database', $body, 'From: add_projector.php');
  }


 else {
  echo '<h1 id="mainhead">System Error</h1>
  <p class="error">Your projector data could not be entered due to a system error. No data was sent.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  echo '</body></html>';

  exit();
}



} else      { 						//  Close If empty errors
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


echo "<h2>Add Projector to  $school Inventory</h2>

School ID = $school_id" ;

?>

<form action="add_projector.php" method="post">
<fieldset>


<!--BEGIN SELECT Projector MODEL <legend>Add Projector to Inventory</legend> -->

<h3>Select Projector Model</h3>
<?php

  require_once ('../../mysql_connect_projectors.php');

$query = "SELECT CONCAT(mf, ' ',model) AS model,model_id FROM manufacturers,models WHERE models.mf_id=manufacturers.mf_id" ;

$result = @mysql_query($query);


if ($result) {
 
  echo '<select name="model">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['model_id'] . '">' . ' ' . $row['model'] . '</option>\\n';


  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The projectors could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

?>


<!-- END SELECT PROJECTOR MODEL -->

<!--BEGIN SELECT ROOM  -->

<h3>Identify Location</h3>
<?php

//require_once ('../../mysql_connect_computers.php');


/*
Use this query to limit select list to an individual building. No need to the floor-specific search for one-story buildings.

select room from rooms,computers where computers.room_id = rooms.room_id and rooms.room like '1%' and rooms.room_id not in (152,153,154,176) and computers.school_id=11 group by room

*/




// echo "School ID is $school_id <br /><br />";

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



<!-- END SELECT ROOM -->

</fieldset>

<div align="center">  
<input type="submit" name="submit" value="Submit"/></div>
<input type="hidden" name="submitted" value="TRUE"/>


<?php

echo 	'<input type="hidden" name="schools" value="' . $school_id . '" />';
echo '</form>';


include ('../includes/footer.html');
?>
