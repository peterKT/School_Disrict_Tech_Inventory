<?php #  - edit_netbooks2.php
//2018a
//accessed through edit_netbooks2.php

$page_title = 'Edit Netbook Info';
include ('../includes/header_computers2.html');


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

  require_once ('../../mysql_connect_computers.php');

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

			

		if (empty($_POST['change_model'])) {
  			$errors[] = 'You forgot to select yes or no regarding a model change.';
		} else {
//			$cm = 'nothing';

  			$cm = $_POST['change_model'];

		
		}	

		if (empty($_POST['change_teacher'])) {
  			$errors[] = 'You forgot to select yes or no regarding a teacher change.';
		} else {
			
			
  			$ct = $_POST['change_teacher'];

		
		}	


//FINISH CHECKING FOR ERRORS


if (empty($errors)) {						//OPEN IF NO ERRORS	

  $query = "SELECT computer_id FROM computers WHERE service_tag='$stag' AND computer_id != $id";
  $result = mysql_query($query);
  if    (  (mysql_num_rows($result)==0) || $stag=='unknown' )        {				//IF COMPUTER ID IS UNIQUE
			
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
	mail ('ptitus@localhost', 'Change in computers database', $body, 'From: edit_netbooks2.php');


			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The computer data could not be edited due to a system error. We aplogize for any inconvenience.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query3 . '</p>';
			include ('../includes/footer.html');
			exit();
			}

	} else {
		echo '<p>No changes made to name or service tag.</p>';
	}

//START UPDATING TEACHER ASSIGNMENT AND MODEL INFO


if ( $ct === "no" ) {
	echo '<p>The teacher information has not been edited.</p>';
	

	}

if ( $ct === "yes" ) {



$teacher = $_POST['teacher_change'] ;

$query7 = "UPDATE computers SET teacher_id=$teacher WHERE computer_id=$id";
	$result7 = @mysql_query($query7);
		if (mysql_affected_rows() == 1) {
			echo '<p>The teacher information has been edited.</p>';


	$body = "Teacher assignment information for computer with ID '$id' has been edited.  The new teacher is '$teacher' \n\n" ;
	mail ('ptitus@localhost', 'Change in computers database', $body, 'From: edit_netbooks2.php');


			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The computer assignment could not be edited due to a system error. </p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query7 . '</p>';
			include ('../includes/footer.html');
			exit();
			}
}


if ( $cm === "no" ) {
	echo '<p>The computer model information has not been edited.</p>';
	include ('../includes/footer.html');
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
	mail ('ptitus@localhost', 'Change in computers database', $body, 'From: edit_netbooks2.php');
	include ('../includes/footer.html');
	exit();

			} else {
			
			echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The computer model could not be edited due to a system error. This error can occur if your selected model matches the currently listed model.</p>';
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query4 . '</p>';
			include ('../includes/footer.html');
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


$query = "SELECT CONCAT(first_name, ' ',last_name) as teacher, CONCAT(model, ' ',computer_type) AS model, computer_name, service_tag FROM teachers, computer_models, computer_types, computers WHERE computers.teacher_id=teachers.teacher_id AND computers.model_id=computer_models.model_id AND computer_models.ct_id=computer_types.ct_id AND computers.computer_id=$id";

	$result = mysql_query($query);
	if (mysql_num_rows($result) == 1 ) {

		$row = mysql_fetch_array($result,MYSQL_NUM);

		echo '<h2>Edit Computer ' . $row[2] . '</h2>
		<form action="edit_netbooks2.php" method="post">

		<p>Currently Assigned To: ' . $row[0] . '</p>

<p>Change the asignee information?  </p>

<p> <input type="radio" name="change_teacher" value="no">  No </p>
<p> <input type="radio" name="change_teacher" value="yes">  Yes (select from following list) </p>' ;

$query6 = "SELECT teacher_id, CONCAT(last_name, ' ',first_name) AS teacher FROM teachers order by last_name" ;

$result6 = @mysql_query($query6);


if ($result6) {
 
  echo '<select name="teacher_change">';
  while ($row6 = mysql_fetch_array($result6, MYSQL_ASSOC)) {
  	echo '<option value="' . $row6['teacher_id'] . '">' . ' ' . $row6['teacher'] . '</option>\\n';

  	}  echo '</select>'; 
	mysql_free_result ($result6);

} else {
  echo '<p class="error">The teacher names could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query6 . '</p>';

mysql_close();  
exit();
 }


echo '<p>Model: ' . $row[1] . '</p>

<p>Change the model information?  Currently you have selecte cm equals ' . $cm . '</p>

<p> <input type="radio" name="change_model" value="no">  No </p>
<p> <input type="radio" name="change_model" value="yes">  Yes (select from following list) </p>' ;

$query5 = "SELECT model,model_id,computer_type FROM computer_models,computer_types WHERE computer_models.ct_id=computer_types.ct_id ORDER BY computer_models.model" ;

$result5 = @mysql_query($query5);


if ($result5) {
 
  echo '<select name="model_change">';
  while ($row5 = mysql_fetch_array($result5, MYSQL_ASSOC)) {
  	echo '<option value="' . $row5['model_id'] . '">' . ' ' . $row5['model'] . ' ' . $row5['computer_type'] . '</option>\\n';

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

	<p>Computer Name: <input type="text" name="name" size="15" maxlength="24" value="' . $row[2] . '" /></p>

	<p>Service Tag: <input type="text" name="stag" size="10" maxlength="10" value="' . $row[3] . '" /></p>

  

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
