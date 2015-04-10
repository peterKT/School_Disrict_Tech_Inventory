<?php #  - district_delete_computers.php

//accessed through district_view_computer2.php

$page_title = 'Delete Computer';
include ('../../includes/header_district_computers2.html');

if (  (isset($_GET['id']))  && (is_numeric($_GET['id'])) )  {		//CHECK FOR CORRECT INPUT
	$id=$_GET['id'] ; 
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
	$id=$_POST['id'] ;
} else {
	echo '<h1 id="mainhead">
	Page Error</h1>
	<p class="error">This here page has been accessed in error.</p><p><br /><br /></p>';
include ('../../includes/footer.html');
exit();
}

/*

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


*/

require_once ('../../../mysql_connect_computers.php');

if (isset($_POST['submitted'])) {					//OPEN SUBMITTED
	if ($_POST['sure'] == 'Yes' ) {					//START CONDITION 1
		$tag = $_POST['tag'];
		$cname = $_POST['cname'];
		$query = "DELETE FROM computers where computer_id=$id";
		$result = @mysql_query($query);
			if (mysql_affected_rows() == 1) {		//START CONDITION 2
				echo '<h1 id="mainhead">Delete a Computer</h1>
				<p>The computer has been deleted.</p>
				<p><br /><br /></p>';

	$body = "Computer with ID '$id' has been deleted.  Service tag '$tag' and name '$cname'. \n\n" ;
	mail ('ptitus@localhost', 'Change in computers database', $body, 'From: district_delete_computers.php');



			} else  {

				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The computer could not be deleted due to a system error.</p>';
				echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			}
									//CLOSE CONDITION 2

	} else {							// IF CONDITION 1 NOT MET
		echo '<h1 id="mainhead">Delete a Computer</h1>
		<p>The computer has NOT been deleted.</p>
		<p><br /><br /></p>';
		}							//CLOSE CONDITION 1

} else { 								//CLOSE SUBMITTED  //OPEN NOT SUBMITTED

	$query = "SELECT service_tag,computer_name FROM computers WHERE computer_id=$id";

	$result = @mysql_query($query);

	if (mysql_num_rows($result) == 1) {				//OPEN CONDITION 3
		$row = mysql_fetch_array($result, MYSQL_NUM);
		echo '<h2>Delete a Computer</h2>
		<form action="district_delete_computers.php" method="post">
		
		<h3>Computer Name: ' . $row[1] . '</h3>
		<h4>Service Tag: ' . $row[0] . '</h4>
		<p>Are you sure you want to delete this computer?<br />

		<input type="radio" name="sure" value="Yes" />Yes

		<input type="radio" name="sure" value="No" checked="checked" />No	
		
		</p>

		<input type="submit" name="submit" value="Submit" /></p>
		<input type="hidden" name="submitted" value="TRUE" />

		<input type="hidden" name="id" value="' . $id . '" />
		<input type="hidden" name="tag" value="' . $row[0] . '" />
		<input type="hidden" name="cname" value="' . $row[1] . '" />
		</form>';



	} else {							//CLOSE CONDITION 3 

		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p>
		<p><br /><br /></p>';

		}

}										//CLOSE NOT SUBMITTED
mysql_close();
include ('../../includes/footer.html');

?>	
				

			


