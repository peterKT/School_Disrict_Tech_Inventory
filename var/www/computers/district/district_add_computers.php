<?php # district_add_computers.php FROM district_select_school3.php



$page_title = 'Add Computers';

//include ('../../includes/header_district_computers2.php'

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

require_once ('../../../mysql_connect_computers.php'); 

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

/*
if (empty($_POST['atag'])) {	
  $errors[] = 'You must enter an asset tage.';
} else {
  $atag = $_POST['atag'];	
}

*/


if (empty($errors)) {						// START IF EMPTY ERRORS, MUST CLOSE


//CHECK FOR DUPLICATE SERVICE TAG

$query = "SELECT computer_id FROM computers WHERE ( service_tag='$stag') ";
$result = mysql_query($query);
if (mysql_num_rows($result)==0) {					// START NO DUPS, MUST CLOSE
	
echo "<p>This computer is not a duplicate</p>";

//mysql_free_result ($result);

echo "<h1 id=\"mainhead\" align=\"center\">$school Computer Inventory</h1>";
echo "<h3 align=\"center\">Add computers to rooms</h3>";


//DETERMINE ROOM ID BY TAKING room1 or room2 or room3 or room 0 and finding it in rooms

/* This part has already been done

if ( isset($room1) ) {
	$room = $room1 ;
	}
	elseif ( isset($room2) ) {
	$room = $room2 ;
	}
	elseif ( isset($room3) ) {
	$room = $room3;
	}
	elseif ( isset($room0) ) {
	$room = $room0;
	}

*/

//Make sure room_id is valid

$query = "SELECT room FROM rooms WHERE room_id = '$room_id' ";
$result = mysql_query($query) ;
if ($result) {
	 $row = mysql_fetch_array($result, MYSQL_ASSOC) ;
$room = $row['room'] ;
	} else {

  	echo '<h1 id="mainhead">System Error</h1>
  	<p class="error">Your room id could not be determined due to a system error.</p>';
  	echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
  	echo '</body></html>';
  	exit();
	}


//MAKE THE QUERY IF OK
//Enter the printer model and its type


  $query = "INSERT INTO computers (service_tag,model_id,room_id,computer_name,school_id) VALUES
  ('$stag','$modelid','$room_id','$cname','$school_id')";

$result = mysql_query($query); 
if ($result) {
  
  echo '<h1 id="mainhead">Thank you!</h1>
  <p>Your computer model and associated data went through--no duplicates.</p><p>
  	You entered room ID ' . $room_id  . '	
  <br /></p>';
  echo "The room ID was $room_id <br />";
  
  $body = "A new computer with ID '$modelid' and service tag '$stag' has been added to room $room .\n\n" ;
	mail ('ptitus@localhost', 'Change in COMPUTER database', $body, 'From: district_add_computers.php');
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

<!-- IF SCHOOL IS MULTISTORY BUILDING -->

<form action="district_add_computers.php" method="post">
<fieldset><legend>Add Computers to Inventory</legend>
<!--BEGIN SELECT COMPUTER MODEL  -->

<h3>Select Computer Model</h3>
<?php

require_once ('../../../mysql_connect_computers.php');

$query = "SELECT model,model_id,computer_type FROM computer_models,computer_types WHERE computer_models.ct_id=computer_types.ct_id AND computer_models.model_id NOT IN (1,4,5,6,9,10,13,14,17,20,21,30,32,33) ORDER BY computer_models.model" ;

$result = @mysql_query($query);


if ($result) {
 
  echo '<select name="model">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['model_id'] . '">' . ' ' . $row['model'] . ' ' . $row['computer_type'] . '</option>\\n';

//	echo '<option value="' . $row['printer_id'] . '">' . '  ' . $row['type'] .  $row['model'] .   '</option>\\n';

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
