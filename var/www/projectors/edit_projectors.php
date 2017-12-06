
<?php #  - edit_projectors.php

//accessed through view_projectors2.php, receiving projecter_id as $_GET['id']

$page_title = 'Edit Projector Info';
include ('../includes/header_projectors.html');


if (  (isset($_GET['id']))  && (is_numeric($_GET['id'])) )  {		//CHECK FOR CORRECT INPUT
	$id=$_GET['id'] ; 
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
	$id=$_POST['id'] ;
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


		if (empty($_POST['change_model'])) {
  			$errors[] = 'You forgot to select yes or no regarding a model change.';
		} else { $cm = $_POST['change_model'];	}	


//FINISH CHECKING FOR ERRORS

if (empty($errors)) {						//OPEN IF NO ERRORS	
//START UPDATING ROOM AND MODEL INFO

if ( $cm === "no" ) {
	echo '<p>The computer model information has not been edited.</p>';
	include ('../includes/footer.html');
	exit();	

	}

if ( $cm === "yes" ) {

echo '<p>Change model value is now ' . $cm . '</p>';

$model = $_POST['model_change'] ;

$query4 = "UPDATE projectors SET model_id=$model WHERE projector_id=$id";
	$result4 = @mysql_query($query4);
		if (mysql_affected_rows() == 1) {
			echo '<p>The projector model information has been edited.</p>
				<p><br /><br /></p>';

	$body = "Model information for projector with ID '$id' has been edited.  The new model ID is '$model' \n\n" ;
	mail ('ptitus@localhost', 'Change in projectorss database', $body, 'From: edit_projectors.php');
	include ('../includes/footer.html');
	exit();

			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The projector model could not be edited due to a system error. This error can occur if your selected model matches the currently listed model.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query4 . '</p>';
			include ('../includes/footer.html');
			exit();
			}
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


$query = "SELECT CONCAT(mf, ' ',model) AS model,room,projector_id from manufacturers,models,rooms,projectors WHERE projector_id=$id and projectors.model_id=models.model_id and manufacturers.mf_id=models.mf_id and rooms.room_id=projectors.room_id" ;

	$result = mysql_query($query);
	if (mysql_num_rows($result) == 1 ) {

		$row = mysql_fetch_array($result,MYSQL_NUM);

		echo '<h2>Edit Projector ' . $id . '</h2>
		<form action="edit_projectors.php" method="post">

		<p>Currently Listed in Room: ' . $row[1] . '</p>';




echo '<p>Model: ' . $row[0] . '</p>

<p>Change the model information?  Currently you it listed as a ' . $row[0] . '</p>

<p> <input type="radio" name="change_model" value="no">  No </p>
<p> <input type="radio" name="change_model" value="yes">  Yes (select from following list) </p>' ;

$query5 = "SELECT CONCAT(mf, ' ',model) AS model,model_id from manufacturers,models WHERE manufacturers.mf_id=models.mf_id" ;

$result5 = @mysql_query($query5);


if ($result5) {
 
  echo '<select name="model_change">';
  while ($row5 = mysql_fetch_array($result5, MYSQL_ASSOC)) {
  	echo '<option value="' . $row5['model_id'] . '">' . ' ' . $row5['model'] . '</option>\\n';

//	echo '<option value="' . $row['printer_id'] . '">' . '  ' . $row['type'] .  $row['model'] .   '</option>\\n';

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


	<p><input type="submit" name="submit" value="Submit"
		/></p>
	<input type="hidden" name="submitted" value="TRUE"
		/>
	<input type="hidden" name="id" value="' . $id . '" />
	</form>' ;

		} else {
			echo '<h1 id="mainhead">Page Error</h1>
			<p class="error">This page has been accessed in error.</p>
			<p><br /><br /></p>';
		}



mysql_close();
include ('../includes/footer.html');

?>