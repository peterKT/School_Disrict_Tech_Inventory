<?php # district_add_locations.php FROM add_rooms.php
//2018a


$page_title = 'Add Location';

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


$query = "SELECT school FROM schools WHERE school_id=$school_id" ;
$result = mysql_query($query); 
	if ($result) {
	$row = mysql_fetch_array($result, MYSQL_ASSOC) ;
        $school = $row['school'];
        echo "<p>OK, got the school name: $school</p>";
        mysql_free_result ($result);
	} else {
  	echo '<h1 id="mainhead">System Error</h1>
  	<p class="error">Your school data could not be found.</p>';

  	echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  	echo '</body></html>';

  	exit();
	}




if (isset($_POST['submitted2']) ) {				// START SUBMIT, MUST CLOSE
  $errors = array();

	if (empty($_POST['new_room_name'])) {					// START ERROR COLLECTION
  	$errors[] = 'You must enter a room.';
	} else {
  	$new_room_name = $_POST['new_room_name'];	
	}


  if (empty($errors)) {						// START IF EMPTY ERRORS, MUST CLOSE


    //CHECK FOR DUPLICATE ROOM NAME

    $query00 = "SELECT room_name FROM room_names WHERE room_name='$new_room_name' ";
    $result00 = mysql_query($query00);
    if (mysql_num_rows($result00)==0) {					// START NO DUPS, MUST CLOSE
	
      echo "<p>This room '$new_room_name' is not a duplicate</p>";

      echo "<h1 id=\"mainhead\" align=\"center\">$school Rooms</h1>";
      echo "<h3 align=\"center\">Add room</h3>";

//MAKE THE QUERY IF OK

      $query0 = "INSERT INTO room_names(room_name) VALUES('$new_room_name')";

      $result0 = mysql_query($query0); 
      if ($result0) {
// do nothing
      } else {
      echo '<h1 id="mainhead">System Error</h1>
      <p class="error">Your room NAME data could not be entered due to a system error. No data was sent.</p>';

      echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

      echo '</body></html>';

      exit();
      }

// NOW CREATE A NEW LOCATION

      $query1 = "SELECT room_name_id from room_names WHERE room_name='$new_room_name' ";

      $result1 = mysql_query($query1); 
      if ($result1) {
	$row = mysql_fetch_array($result1, MYSQL_ASSOC) ;
        $room_name_id = $row['room_name_id'] ;
	} else {
        echo '<h1 id="mainhead">System Error</h1>
        <p class="error">Your room data could not be entered due to a system error. No data was sent.</p>';

        echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query1 . '</p>';

        echo '</body></html>';

        exit();
        }




      $query2 = "INSERT INTO locations (school_id,room_name_id) VALUES('$school_id','$room_name_id')";

      $result2 = mysql_query($query2); 
      if ($result2) {
  
        echo '<h1 id="mainhead">Thank you!</h1>
        <p>Your room and associated location info went through--no duplicates.</p><p>
  	You entered room ID' . $room_name_id . '	
        <br /></p>';
        echo "The room  was $new_room_name <br />";
  
        $body = "A new room with ID $room_name_id and called $new_room_name has been added to $school .\n\n" ;
	mail ('ptitus@localhost', 'Change in COMPUTER database', $body, 'From: district_add_locations.php');
        } else {
        echo '<h1 id="mainhead">System Error</h1>
        <p class="error">Your model data could not be entered due to a system error. No data was sent.</p>';

        echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query2 . '</p>';

        echo '</body></html>';

        exit();
        }

      }		// END CHECK FOR ROOM NAME

// ALLOW FOR EXISTING ROOM NAME BUT NOT IN THIS BUILDING


    } elseif (mysql_num_rows($result)!=0) {
      echo "<p>This room '$new_room_name' is a duplicate so all we need to do is create the location.</p>";

      echo "<h1 id=\"mainhead\" align=\"center\">$school Rooms</h1>";
      echo "<h3 align=\"center\">Add room</h3>";


// CREATE A NEW LOCATION USING PRE-EXISTING ROOM

      $query3 = "SELECT room_name_id from room_names WHERE room_name='$new_room_name' ";

      $result3 = mysql_query($query3); 
      if ($result3) {
	$row = mysql_fetch_array($result3, MYSQL_ASSOC) ;
        $room_name_id = $row['room_name_id'] ;
	} else {
        echo '<h1 id="mainhead">System Error</h1>
        <p class="error">Your room data could not be found due to a system error. No data was sent.</p>';

        echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query3 . '</p>';

        echo '</body></html>';

        exit();
        }

// CHECK FOR DUPLICATE LOCATION

      $query4 = "SELECT location_id from locations where school_id='$school_id' AND room_name_id='$room_name_id'";


      $result4 = mysql_query($query4); 
      if (mysql_num_rows($result4)==0) {


      	$query5 = "INSERT INTO locations (school_id,room_name_id) VALUES('$school_id','$room_name_id')";

      	$result5 = mysql_query($query4); 
      	  if ($result5) {
  
          echo '<h1 id="mainhead">Thank you!</h1>
          <p>Your room and associated location info went through--no duplicates.</p><p>
  	  You entered room ID' . $room_name_id . '	
          <br /></p>';
          echo "The room  was $new_room_name <br />";
  
          $body = "A new room with ID $room_name_id and called $new_room_name has been added to $school .\n\n" ;
	  mail ('ptitus@localhost', 'Change in locations table', $body, 'From: district_add_locations.php');
          } else {
          echo '<h1 id="mainhead">System Error</h1>
          <p class="error">Your location could not be entered due to a system error. No data was sent.</p>';

          echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query5 . '</p>';

          echo '</body></html>';

          exit();
          }


    	
       } else {   							//CLOSE CHECK FOR DUPLICATE
      echo "<p>Sorry, that location is already in the database.</p>";
      mysql_close();  
      exit();
      }

  } else { 						//  Close If empty errors
  	echo '<h1 id="mainhead">Error!</h1>
  	<p class="error">The following error(s) occurred:<br />';
  	foreach ($errors as $msg) {
  		echo " -$msg<br />\n";
  		}
  echo '</p><p>Please try again.</p><p><br /></p>' ;
  mysql_close();  
  exit();
  }
//CLOSE SUBMIT
}


?>

<?php

echo "<h3>Add Another Room</h3>

School ID = $school_id" ;

?>

<form action="district_add_locations.php" method="post">
<fieldset><legend>Add Room</legend>

<!--Begin Specify Room Name and Check for Duplicates  -->


<p>Room Name or Number: <input type="text" name="new_room_name" size="20" maxlength="24" value="" /></p>


</fieldset>

<div align="center">  
<input type="submit" name="submit" value="Submit"/></div>
<input type="hidden" name="submitted2" value="TRUE"/>

<?php
echo 	'<input type="hidden" name="schools" value="' . $school_id . '" />';

?>

</form>




<?php
include ('../../includes/footer.html');
?>
