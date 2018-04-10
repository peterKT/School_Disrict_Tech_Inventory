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
  require_once ('../../mysql_connect_inventory.php');

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
  $model = $_POST['model'];					// GATHER MODEL_ID
}


//SELECT ROOM

if (isset($_POST['room0']) ) 
	{
		$room_id = $_POST['room0'] ;
		echo "You entered room ID $room_id";	
	} else { 
		$errors[] = 'Your room0 value is messed up.';
	}

//END SELECT ROOM


if (empty($errors)) {						// START IF EMPTY ERRORS, MUST CLOSE

// LEFT OFF HERE

//Determine the location_id value based on information submitted in the form
//CHECK FOR EXISTING PROJECTOR

$query0= "SELECT location_id FROM locations WHERE school_id=$school_id AND room_name_id=$room_id";

$result0 = mysql_query($query0);
$row = mysql_fetch_array($result0,MYSQL_NUM);
$location = $row[0] ;


$query1 = "SELECT projector_id FROM projectors where location_id=$location";
$result1 = mysql_query($query1);

if (mysql_num_rows($result1)==0) {		   		//CHECK FOR CURRENT ROOM STATUS			
	
echo "<p>This location does not currently have a projector</p>";

	} else {   							
	echo "<p>Warning, this room already has a projector. You are adding another.</p>";
	}			

//MAKE THE QUERY IF OK
//Enter the printer model and its type


  $query2 = "INSERT INTO projectors (model_id,location_id) VALUES
  ('$model','$location')";

$result2 = mysql_query($query2); 
if ($result2) {
  
  echo '<h1 id="mainhead">Thank you!</h1>
  <p>Your projector model and location went through.</p><p>
  <br /></p>';
  
  $body = "A new projector '$model' been added to room ID $room_id in $school.\n\n" ;
	mail ('user@localhost', 'Change in projectors database', $body, 'From: add_projector.php');
  }


 else {
  echo '<h1 id="mainhead">System Error</h1>
  <p class="error">Your projector data could not be entered due to a system error. No data was sent.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  echo '</body></html>';

  exit();
}



} else  { 						//  Close If empty errors
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

  require_once ('../../mysql_connect_inventory.php');

$query = "SELECT CONCAT(mf, ' ',model) AS model,model_id FROM manufacturers,projector_models WHERE projector_models.mf_id=manufacturers.mf_id AND projector_models.model_id != 20" ;

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

echo "<h4>All rooms in $school</h4>";
//echo "School ID is $school_id <br /><br />";	
	
$query = "SELECT room_names.room_name_id,room_names.room_name FROM room_names,locations WHERE locations.school_id=$school_id AND locations.room_name_id=room_names.room_name_id ORDER BY room_name" ;

$result = @mysql_query($query);

if ($result) {
 
  echo '<select name="room0">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['room_name_id'] . '">' . ' ' . $row['room_name'] . '</option>\\n';

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
