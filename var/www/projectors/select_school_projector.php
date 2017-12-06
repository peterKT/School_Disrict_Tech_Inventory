
<?php
$page_title = 'Select a School';
//from Add a Projector option on header. When school is selected, school_id goes to add_projector.php
include ('../includes/header_projectors.html');

?>

<form action="add_projector.php" method="post">
<fieldset><legend>Select a School</legend>


<?php

require_once ('../../mysql_connect_projectors.php');
$query = "select school_id,school from schools where school_id != 4 order by school" ;

$result = @mysql_query($query);


if ($result) {
 
  echo '<select name="schools">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['school_id'] . '">' . ' ' . $row['school'] .  '</option>\\n';
}
 echo '</select>'; 
	
mysql_free_result ($result);

} else {
  echo '<p class="error">The models could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }







?>

</fieldset>
<div align="center"><input type="submit" name="submit" value="Submit School Selection" /></div>
</form>




</body>
</html>


