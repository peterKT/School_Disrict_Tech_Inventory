<?php #  - delete_projector.php

//accessed through ecit_screens.php and receiving smartboards.board_id as GET_id

$page_title = 'Delete a Screen';
include ('../includes/header_projectors.html');

if (  (isset($_GET['id']))  && (is_numeric($_GET['id'])) )  {		//CHECK FOR CORRECT INPUT
	$id=$_GET['id'] ; 
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
	$id=$_POST['id'] ;
} else {
	echo '<h1 id="mainhead">
	Page Error</h1>
	<p class="error">This here page has been accessed in error.</p><p><br /><br /></p>';
include ('../includes/footer.html');
exit();
}

  require_once ('../../mysql_connect_projectors.php');

if (isset($_POST['submitted'])) {					//OPEN SUBMITTED
	if ($_POST['sure'] == 'Yes' ) {					//START CONDITION 1
		$screen = $_POST['screen'];
		$school = $_POST['school'];
		$room = $_POST['room'];
		$query = "DELETE FROM smartboards where board_id=$id";
		$result = @mysql_query($query);
			if (mysql_affected_rows() == 1) {		//START CONDITION 2
				echo '<h1 id="mainhead">Delete a Screen</h1>
				<p>The screen has been deleted.</p>
				<p><br /><br /></p>';

	$body = "Screen with ID '$id' has been deleted.  It was a '$screen' type screen, in school '$school', room '$room'. \n\n" ;
	mail ('ptitus@localhost', 'Change in smartboards database', $body, 'From: delete_screen.php');


mysql_close();
			} else  {

				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The computer could not be deleted due to a system error.</p>';
				echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
				
				mysql_close();

				}
									//CLOSE CONDITION 2

	} else {							// IF CONDITION 1 NOT MET
		echo '<h1 id="mainhead">Delete a Screen</h1>
		<p>The screen has NOT been deleted.</p>
		<p><br /><br /></p>';
		
		mysql_close();
		}							//CLOSE CONDITION 1

} else { 								//CLOSE SUBMITTED  

//OPEN RECEIVE board_id as $id FROM view_screens.php

	$query = "SELECT board_id, screen,school,room,mount FROM smartboards,screens,schools,rooms,mounts WHERE board_id=$id AND screens.screen_id=smartboards.screen_id AND schools.school_id=smartboards.school_id AND rooms.room_id=smartboards.room_id AND mounts.mount_id=smartboards.mount_id;";

	$result = @mysql_query($query);

	if (mysql_num_rows($result) == 1) {				//OPEN CONDITION 3
		$row = mysql_fetch_array($result, MYSQL_NUM);
		echo '<h2>Delete a Screen</h2>
		<form action="delete_screen.php" method="post">
		
		<h3>Screen Type: ' . $row[1] . '</h3>
		<h4>School: ' . $row[2] . '</h4>
		<h4>Room: ' . $row[3] . '</h4>
		<p>Are you sure you want to delete this screen?<br />

		<input type="radio" name="sure" value="Yes" />Yes

		<input type="radio" name="sure" value="No" checked="checked" />No	
		
		</p>

		<input type="submit" name="submit" value="Submit" /></p>
		<input type="hidden" name="submitted" value="TRUE" />

		<input type="hidden" name="id" value="' . $id . '" />
		<input type="hidden" name="screen" value="' . $row[1] . '" />
		<input type="hidden" name="school" value="' . $row[2] . '" />
		<input type="hidden" name="room" value="' . $row[3] . '" />
		</form>';

mysql_free_result($result) ;

	} else {							//CLOSE CONDITION 3 

		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error '. $id . ' .</p>
		<p><br /><br /></p>';

		}

}										//CLOSE RECEIVE board_id

include ('../includes/footer.html');

?>	
				

			


