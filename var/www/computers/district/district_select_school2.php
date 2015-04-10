
<?php
$page_title = 'Select a School';


include ('../../includes/header_district_computers.html');

?>
<h2>Select a building to view and manage inventory</h2>

<form action="district_view_computers2.php" method="post">
<fieldset><legend>Select a School</legend>


<?php

require_once ('../../../mysql_connect_computers.php');


$query = "select school_id,school from schools where school_id != 23 order by school" ;

$result = @mysql_query($query);


if ($result) {
 
  echo '<select name="schools">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['school_id'] . '">' . ' ' . $row['school'] .  '</option>\\n';
}
 echo '</select>'; 
	
mysql_free_result ($result);

} else {
  echo '<p class="error">The schools could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

?>

<!-- 
<p>Sort by: <input type="radio" name="sort" value="model" />Model 
<input type="radio" name="sort" value="rooms.room_id" />Room
 -->
</fieldset>
<div align="center"><input type="submit" name="submit" value="Submit 
School Selection" /></div>
</form>

</body>
</html>


