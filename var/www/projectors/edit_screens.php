
<?php #  - edit_screens.php

//accessed through view_screens.php, receiving board_id as $_GET['id']
// $id will be the board_id and $model will be the board's screen_id

$page_title = 'Edit Screen Info';
include ('../includes/header_projectors.html');


if (  (isset($_GET['id']))  && (is_numeric($_GET['id'])) )  {		//CHECK FOR CORRECT INPUT
	$id=$_GET['id'] ; 
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
	$id=$_POST['id'] ;
	$sid=$_POST['sid'] ;
	$mid=$_POST['mid'] ;
} else {
	echo '<h1 id="mainhead">
	Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
include ('../includes/footer.html');
exit();
}

  require_once ('../../mysql_connect_projectors.php');

if (isset($_POST['submitted'])) {					//OPEN SUBMITTED

	  $errors = array();

		if (empty($_POST['change_screen'])) {
  			$errors[] = 'You forgot to select yes or no regarding a model change.';
		} else { $cs = $_POST['change_screen'];	}

		if ($_POST['change_screen'] == 'yes' && $_POST['screen_change'] == $sid) {
			$errors[] = 'You selected yes to change screen but submitted the same screen type.' ;

		echo '<p>Posted value is ' . $_POST['change_screen'] . ' and SID value is ' . $sid . '</p>';
		}	

		elseif ($_POST['change_sn'] == "yes" && empty($_POST['serial']) ) {
			$errors[] = 'You did not submit a serial number.' ;
		}


//FINISH CHECKING FOR ERRORS

if (empty($errors)) {						//OPEN IF NO ERRORS	
//START UPDATING ROOM AND MODEL INFO

if ( $cs == "no" ) {
	echo '<p>The screen type information has not been edited.</p>';
	}

elseif ( $cs == "yes" ) {

echo '<p>Change screen value is now ' . $cs . '</p>';
//$model will be the value of the screen_id for the designated new screen
$model = $_POST['screen_change'] ;

$query3 = "UPDATE smartboards SET screen_id=$model WHERE board_id=$id";
	$result3 = @mysql_query($query3);
		if (mysql_affected_rows() == 1) {
			echo '<p>The screen type information has been edited.</p>
				<p><br /><br /></p>';

	$body = "Screen type information for projector with ID '$id' has been edited.  The new screen ID is '$model' \n\n" ;
	mail ('ptitus@localhost', 'Change in projectorss database', $body, 'From: edit_screems.php');
			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The screens could not be edited due to a system error. This error can occur if your selected screen matches the currently listed model.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query4 . '</p>';
			include ('../includes/footer.html');
			exit();
			}
}


//UPDATE MOUNT INFO

$change_mount = $_POST['change_mount'] ;
if ( $change_mount == "no" ) {
	echo '<p>The mount information has not been edited.</p>';
	}

elseif ( $change_mount == "yes" ) {

//echo '<p>Change mount value is now ' . $change_mount . '</p>';

$mount = $_POST['mount_change'] ;

$query3 = "UPDATE smartboards SET screen_id=$mount WHERE board_id=$id";
	$result3 = @mysql_query($query3);
		if (mysql_affected_rows() == 1) {
			echo '<p>The mount information has been edited.</p>
				<p><br /><br /></p>';

	$body = "Mount information for screen with ID '$id' has been edited.  The new mount ID is '$mount' \n\n" ;
	mail ('ptitus@localhost', 'Change in projectorss database', $body, 'From: edit_screens.php');
			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The mount could not be edited due to a system error. </p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query4 . '</p>';
						}
				}

//CLOSE UPDATE MOUNT INFO

//OPEN UPDATE SERIAL NUMBER
$change_sn = $_POST['change_sn'] ;

if ( $change_sn == "no" ) {
	echo '<p>The serial number information has not been edited.</p>';
		}

if ( $change_sn == "yes" ) {

echo '<p>Change serial number value is now ' . $change_sn . '</p>';
//$model will be the value of the screen_id for the designated new screen
$serial = $_POST['serial'] ;
echo '<p>Serial value is ' . $_POST['serial'] . '</p>' ;

$query4 = "UPDATE smartboards SET serial_no='$serial' WHERE board_id=$id";
	$result4 = @mysql_query($query4);
		if (mysql_affected_rows() == 1) {
			echo '<p>The serial number information has been edited.</p>
				<p><br /><br /></p>';

	$body = "Serial number information for projector with ID '$id' has been edited.  The new serial number is '$serial' \n\n" ;
	mail ('ptitus@localhost', 'Change in projectors database', $body, 'From: edit_screems.php');
				} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The serial number could not be edited due to a system error.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query4 . '</p>';
			include ('../includes/footer.html');
			exit();
			}
}
//CLOSE UPDATE SERIAL NUMBER	
		

if ( $change_sn == "no" && $change_mount == 'no' && $cs == 'no') {
	echo '<p>No changes submitted; no changes made</p>';
	

	}
include ('../includes/footer.html');
exit();	
		} else {				//OPEN IF ERRORS
			echo '<h1 id="mainhead">Error!</h1>
				<p class="error">The following error(s) occurred:<br />';
				foreach ($errors as $msg) {
					echo " - $msg<br />\n";
				}
			echo '</p><p>Please try again.</p><p><br /></p>';

			mysql_close();
			include ('../includes/footer.html');
			exit();
			}				//CLOSE IF ERRORS

							
}
							//CLOSE SUBMITTED

//OPEN UPDATE SCREEN TYPE

$query = "SELECT board_id,smartboards.screen_id,screen,school,room,mount_id FROM smartboards,screens,schools,rooms WHERE board_id=$id AND smartboards.screen_id=screens.screen_id AND smartboards.school_id=schools.school_id AND smartboards.room_id=rooms.room_id" ;

	$result = mysql_query($query);
	if (mysql_num_rows($result) == 1 ) {

		$row = mysql_fetch_array($result,MYSQL_NUM);
//Set screen and mount IDs so they can be checked against current values

$sid = $row[1] ;
$mid = $row[5] ;

		echo '<h2>Edit Screen ' . $sid . '</h2>
		<form action="edit_screens.php" method="post">

		<p>Currently Listed in Room: ' . $row[4] . '</p>';


echo '<p>Change the screen type information? Current type is:  ' . $row[2] . '</p>

<p> <input type="radio" name="change_screen" value="no" checked>  No </p>
<p> <input type="radio" name="change_screen" value="yes">  Yes (select from following list) </p>' ;


$query2 = "SELECT screen_id,screen FROM screens" ;

$result2 = @mysql_query($query2);


if ($result2) {
 
  echo '<select name="screen_change">';
  while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
  	echo '<option value="' . $row2['screen_id'] . '">' . ' ' . $row2['screen'] . '</option>\\n';

  	}  echo '</select>'; 
	mysql_free_result ($result2);

} else {
  echo '<p class="error">The screen types could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query5 . '</p>';

mysql_close();  
exit();
 }
//END UPDATE SCREEN TYPE

//OPEN UPDATE MOUNT TYPE SUBMIT


$query3 = "SELECT board_id,smartboards.mount_id,mount FROM smartboards,mounts WHERE board_id=$id AND smartboards.mount_id=mounts.mount_id" ;

	$result3 = mysql_query($query3);
	if (mysql_num_rows($result3) == 1 ) {

		$row = mysql_fetch_array($result3,MYSQL_NUM);

		

echo '<p>Change the mount type? Current listed as:  ' . $row[2] . '</p>

<p> <input type="radio" name="change_mount" value="no" checked>  No </p>
<p> <input type="radio" name="change_mount" value="yes">  Yes (select from following list) </p>' ;


$query4 = "SELECT mount_id,mount FROM mounts" ;

$result4 = @mysql_query($query4);


if ($result4) {
 
  echo '<select name="mount_change">';
  while ($row = mysql_fetch_array($result4, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['mount_id'] . '">' . ' ' . $row['mount'] . '</option>\\n';

  	}  echo '</select>'; 
	mysql_free_result ($result4);

} else {
  echo '<p class="error">The mount types could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query5 . '</p>';

mysql_close();  
exit();
 }

} else {
  echo '<p class="error">The mount type for this screen could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query5 . '</p>';

mysql_close();  
exit();
 }
//END UPDATE MOUNT TYPE SUBMIT



//UPDATE SERIAL NUMBER INFORMATION

$query5 = "SELECT board_id,serial_no FROM smartboards WHERE board_id=$id" ;
$result5 = mysql_query($query5);
	if (mysql_num_rows($result5) == 1 ) {

		$row = mysql_fetch_array($result5,MYSQL_NUM);

echo '<p>Update the serial number?  It is currently set at ' . $row[1] . '</p>';
echo '<p> <input type="radio" name="change_sn" value="no" checked>  No </p>
<p> <input type="radio" name="change_sn" value="yes">  Yes (Enter below) </p>


<p>Serial Number: <input type="text" name="serial" size="16" maxlength="16" value="" /></p>';
} else {
  echo '<p class="error">The serial number could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query5 . '</p>';

mysql_close();  
exit();
 }










//END UPDATE SERIAL NUMBER SUBMIT


echo '	<p><input type="submit" name="submit" value="Submit"
		/></p>
	<input type="hidden" name="submitted" value="TRUE"
		/>
	<input type="hidden" name="id" value="' . $id . '" />
	<input type="hidden" name="sid" value="' . $sid . '" />
	<input type="hidden" name="mid" value="' . $mid . '" />
	</form>' ;

		} else {
			echo '<h1 id="mainhead">Page Error</h1>
			<p class="error">This page has been accessed in error.</p>
			<p><br /><br /></p>';
		}



mysql_close();
include ('../includes/footer.html');

?>