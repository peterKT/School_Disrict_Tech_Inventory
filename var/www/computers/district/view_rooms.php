
<?php
//2018a

$page_title = 'View Rooms';
include ('../../includes/header_computers2.html');
?>

<form action="view_rooms.php" method="post">
<fieldset><legend>View room info for South H.S.</legend>

<p>Include: <input type="radio" name="first" value="1" />First Floor 
<input type="radio" name="second" value="2" />Second Floor 
<input type="radio" name="third" value="3" />Third Floor
<input type="radio" name="all" value="all" />All Floors
</fieldset>
<div align="center"><input type="submit" name="submit" value="Submit 
Info Request" /></div>
</form>


<?php

if (isset($_POST['submit']))  {  			//OPEN SUBMIT

  require_once ('../../../mysql_connect_inventory.php');

if (isset($_POST['all']) ) {
	$floor0=$_POST['all'];
	}

if (isset($_POST['first']) ) {
	$floor1=$_POST['first'];
	}

if (isset($_POST['second']) ) {
	$floor2=$_POST['second'];
	}

if (isset($_POST['third']) ) {
	$floor3=$_POST['third'];
	}

if ( ($floor1 && $floor2 && $floor3) || ($floor1 && $foor2) || ($floor1 && $floor3) || ($floor2 && $floor3) ||( $floor0 && ($floor1 || $floor2 || $floor3) ) ) {
	echo '<p>Sorry, only one selection allowed.</p>';
	exit();
	}



if ( !isset($floor0) AND !isset($floor1) AND !isset($floor2) AND !isset($floor3) ) {
	echo '<p>You forgot to make a choice.</p>'; 
	exit();
	}



if ( $floor1=='1'  ) {

$query = "SELECT * FROM rooms WHERE room LIKE '1%' ORDER BY room";

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">South H.S. Locations</h1>';
  echo "<h3 align=\"center\">Total locations on first floor = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Room Number or Location</b></td>



</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['room'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} 


else {
  	echo '<p class="error">The roooms could not be retrieved. 
	We apologize for any inconvenience.</p>';

  	echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
	}

}

elseif ($floor2=='2') {


$query = "SELECT * FROM rooms WHERE room LIKE '2%'ORDER BY room";

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">South H.S. Locations</h1>';
  echo "<h3 align=\"center\">Total locations on second floor = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Room Number or Location</b></td>


</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['room'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} 
else {
  	echo '<p class="error">The roooms could not be retrieved. 
	We apologize for any inconvenience.</p>';

  	echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
	}

}




elseif ($floor3=='3') {


$query = "SELECT * FROM rooms WHERE room LIKE '3%'ORDER BY room";

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">South H.S. Locations</h1>';
  echo "<h3 align=\"center\">Total locations on third floor = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Room Number or Location</b></td>


</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['room'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} 

else {
  	echo '<p class="error">The roooms could not be retrieved. 
	We apologize for any inconvenience.</p>';

  	echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
	}
}





elseif ($floor0=='all') {


$query = "SELECT * FROM rooms ORDER BY room";

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">South H.S. Locations</h1>';
  echo "<h3 align=\"center\">Total locations on all floors = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Room Number or Location</b></td></tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['room'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);

} else {
  	echo '<p class="error">The rooms could not be retrieved. 
	We apologize for any inconvenience.</p>';

  	echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
	}


}


}	
		//CLOSE THE SUBMIT
include ('../../includes/footer.html');

?>



