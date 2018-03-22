<?php #  - district_edit_computers2.php
//2018a
//accessed through district_view_computers2.php

$page_title = 'Edit Computer Info';

include ('../../includes/header_district_computers2.php');


if (  (isset($_GET['id']))  && (is_numeric($_GET['id'])) )  {		//CHECK FOR CORRECT INPUT
	$id=$_GET['id'] ; 
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
	$id=$_POST['id'] ;
} else {
	echo '<h1 id="mainhead">
	Page Error</h1>
	<p class="error">This edit-inventory page has been accessed in error because the id is wrong.</p><p><br /><br /></p>';
include ('../../includes/footer.html');
exit();
}

if (  (isset($_GET['schools']))  && (is_numeric($_GET['schools'])) )  {		//CHECK FOR CORRECT INPUT
	$school_id=$_GET['schools'] ; 
} elseif ( (isset($_POST['schools'])) && (is_numeric($_POST['schools'])) ) {
	$school_id=$_POST['schools'] ;
} else {
	echo '<h1 id="mainhead">
	Page Error</h1>
	<p class="error">This edit-inventory page has been accessed in error because the schools value is wrong.</p><p><br /><br /></p>';
include ('../../includes/footer.html');
exit();
}

  require_once ('../../../mysql_connect_inventory.php');

if (isset($_POST['submitted'])) {					//OPEN SUBMITTED

	  $errors = array();


		if (empty($_POST['name'])) {				//CHECK FOR ERRORS
  			$errors[] = 'You forgot to enter the computer name.';
		} else {
  			$computer_name = $_POST['name'];
		}

		if (empty($_POST['stag'])) {
  			$errors[] = 'You forgot to enter service tag.';
		} else {
  			$stag = $_POST['stag'];
		}
/*
		if (empty($_POST['atag'])) {
  			$errors[] = 'You forgot to enter asset tag.';
		} else {
  			$atag = $_POST['atag'];
		
		}						
*/
		if (empty($_POST['change_model'])) {
  			$errors[] = 'You forgot to select yes or no regarding a model change.';
		} else {

  			$cm = $_POST['change_model'];
		}	

		if (empty($_POST['change_room'])) {
  			$errors[] = 'You forgot to select yes or no regarding a room change.';
		} else {
			
  			$cr = $_POST['change_room'];
		}	


								//FINISH CHECKING FOR ERRORS


if (empty($errors)) {						//OPEN IF NO ERRORS	

  $query = "SELECT computer_id FROM computers WHERE service_tag='$stag' AND computer_id != $id";
  $result = mysql_query($query);
  if    (  (mysql_num_rows($result)==0) || $stag=='unknown' )        {				
  
//IF COMPUTER ID IS UNIQUE
			
	$query2 = "SELECT computer_name, service_tag FROM computers WHERE computer_id = $id";
	$result2 = mysql_query($query2);
	$row = mysql_fetch_array($result2,MYSQL_NUM);
		if ( $computer_name != $row[0] || $stag != $row[1] ) {

	$query3 = "UPDATE computers SET computer_name='$computer_name', service_tag='$stag' WHERE computer_id=$id";
	$result3 = @mysql_query($query3);
		if (mysql_affected_rows() == 1) {
			echo '<h1 id="mainhead">Edit a Computer</h1>
				<p>The computer information has been edited.</p>
				<p><br /><br /></p>';

	$body = "Information for computer with ID '$id' has been edited.  One or more of the following values were updated: name ($computer_name), service tag ($stag). \n\n" ;
	mail ('user@localhost', 'Change in computers database', $body, 'From: district_edit_computers2.php');


			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The computer data could not be edited due to a system error. We apologize for any inconvenience.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query3 . '</p>';
			include ('../../includes/footer.html');
			exit();
			}

	} else {
		echo '<p>No changes made to name or service tag.</p>';
	}

//START UPDATING ROOM AND MODEL INFO


if ( $cr === "no" ) {
	echo '<p>The room information has not been edited.</p>';
	

	}

if ( $cr === "yes" ) {



$room = $_POST['room_change'] ;

$query7 = "UPDATE computers,locations,room_names SET computers.location_id=locations.location_id WHERE computer_id=$id AND locations.school_id=$school_id AND locations.room_name_id=$room";
	$result7 = @mysql_query($query7);
		if (mysql_affected_rows() == 1) {
			echo '<p>The room information has been edited.</p>';


	$body = "Room information for computer with ID '$id' has been edited.  The new room ID is '$room' \n\n" ;
	mail ('user@localhost', 'Change in computers database', $body, 'From: district_edit_computers2.php');


			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The computer room could not be edited due to a system error.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query7 . '</p>';
			include ('../../includes/footer.html');
			exit();
			}
}



/*


  			$cm = $_POST['change_model'];
echo '<p>Post change model is '. $_POST['change_model'] . '</p>';


Note that safe type checking (using === and !== instead of == and !=) is in general somewhat faster. When you're using non-safe type checking and a conversion is really needed for checking, safe type checking is considerably faster.





*/

if ( $cm === "no" ) {
	echo '<p>The computer model information has not been edited.</p>';
	include ('../../includes/footer.html');
	exit();	

	}

if ( $cm === "yes" ) {

echo '<p>Change model value is now ' . $cm . '</p>';

$model = $_POST['model_change'] ;

$query4 = "UPDATE computers SET model_id=$model WHERE computer_id=$id";
	$result4 = @mysql_query($query4);
		if (mysql_affected_rows() == 1) {
			echo '<p>The computer model information has been edited.</p>
				<p><br /><br /></p>';

	$body = "Model information for computer with ID '$id' has been edited.  The new model ID is '$model' \n\n" ;
	mail ('user@localhost', 'Change in computers database', $body, 'From: district_edit_computers2.php');
	include ('../../includes/footer.html');
	exit();

			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The computer model could not be edited due to a system error. This error can occur if your selected model matches the currently listed model.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query4 . '</p>';
			include ('../../includes/footer.html');
			exit();
			}
}




	} else {				//CLOSE IF DUPLICATE EXISTS

	echo '<h1 id="mainhead">Error!</h1>
	<p class="error">There is a duplicate service tag.  Please correct database.</p>';
		}	
		
		} else {				//CLOSE IF NO ERRORS AND START IF ERRORS
			echo '<h1 id="mainhead">Error!</h1>
				<p class="error">The following error(s) occurred:<br />';
				foreach ($errors as $msg) {
					echo " - $msg<br />\n";
				}
			echo '</p><p>Please try again.</p><p><br /></p>';
			}							//CLOSE IF ERRORS
}
							//CLOSE SUBMITTED

//From district_view_computers2, with $id value equal to the computer_id in computers table.


$query = "SELECT school, room_name, CONCAT(model, ' ',computer_type) AS model, computer_name, service_tag FROM schools, room_names, computer_models, computer_types, computers, locations WHERE computers.location_id=locations.location_id and locations.school_id=schools.school_id AND room_names.room_name_id=locations.room_name_id AND computers.model_id=computer_models.model_id AND computer_models.ct_id=computer_types.ct_id AND computers.computer_id=$id";

/*

$query = "SELECT school, room, CONCAT(model, ' ',computer_type) AS model, computer_name, service_tag FROM schools, rooms, computer_models, computer_types, computers WHERE schools.school_id=computers.school_id AND rooms.room_id=computers.room_id AND computers.model_id=computer_models.model_id AND computer_models.ct_id=computer_types.ct_id AND computers.computer_id=$id";
*/

	$result = mysql_query($query);
	if (mysql_num_rows($result) == 1 ) {

		$row = mysql_fetch_array($result,MYSQL_NUM);
		$school_name = $row[0];
		
		echo '<h2>Edit Computer ' . $id . ' located at ' . $school_name . '</h2>
		<h4>To move to another building, delete then select the new building and add it</h4> 
		<form action="district_edit_computers2.php" method="post">

		<p>Currently Listed in Room: ' . $row[1] . '</p>

<p>Change the room information?  </p>

<p> <input type="radio" name="change_room" value="no">  No </p>
<p> <input type="radio" name="change_room" value="yes">  Yes (select from following list) </p>' ;



	
$query7 = "SELECT room_names.room_name_id,room_name FROM room_names,locations WHERE locations.school_id = $school_id AND locations.room_name_id=room_names.room_name_id order by room_name" ;

$result7 = @mysql_query($query7);


if ($result7) {
 
  echo '<select name="room_change">';
  while ($row7 = mysql_fetch_array($result7, MYSQL_ASSOC)) {
  	echo '<option value="' . $row7['room_name_id'] . '">' . ' ' . $row7['room_name'] . '</option>\\n';

  	}  echo '</select>'; 
	mysql_free_result ($result7);

} else {
  echo '<p class="error">The rooms could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query7 . '</p>';

mysql_close();  
exit();
 }
	
//	}



echo '<p>Model: ' . $row[2] . '</p>

<p>Change the model information?</p>

<p> <input type="radio" name="change_model" value="no">  No </p>
<p> <input type="radio" name="change_model" value="yes">  Yes (select from following list) </p>' ;

$query5 = "SELECT model,model_id,computer_type FROM computer_models,computer_types WHERE computer_models.ct_id=computer_types.ct_id ORDER BY computer_models.model" ;

$result5 = @mysql_query($query5);


if ($result5) {
 
  echo '<select name="model_change">';
  while ($row5 = mysql_fetch_array($result5, MYSQL_ASSOC)) {
  	echo '<option value="' . $row5['model_id'] . '">' . ' ' . $row5['model'] . ' ' . $row5['computer_type'] . '</option>\\n';

  	}  echo '</select>'; 
	mysql_free_result ($result5);

} else {
  echo '<p class="error">The models could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query5 . '</p>';

mysql_close();  
exit();
 }

echo '

	<p>Computer Name: <input type="text" name="name" size="15" maxlength="24" value="' . $row[3] . '" /></p>

	<p>Service Tag: <input type="text" name="stag" size="10" maxlength="10" value="' . $row[4] . '" /></p>

	<p><input type="submit" name="submit" value="Submit"
		/></p>
	<input type="hidden" name="submitted" value="TRUE"
		/>
	<input type="hidden" name="id" value="' . $id . '" />
	<input type="hidden" name="schools" value="' . $school_id . '" />
	</form>' ;

		} else {
			echo '<h1 id="mainhead">Page Error</h1>
			<p class="error">This page has been accessed in error.</p>
			<p><br /><br /></p>';
		}



mysql_close();
include ('../../includes/footer.html');

?>
