
<?php
$page_title = 'Select a School';
//from VIEW AND EDIT SCREEN INVENTORY BY SCHOOL header option. When school is selected, goes to VIEW_SCREENS.PHP
include ('../includes/header_projectors.html');

?>

<form action="view_screens.php" method="post">
<fieldset><legend>Select a School</legend>


<?php

  require_once ('../../mysql_connect_inventory.php');
$query = "select school_id,school from schools order by school" ;

$result = @mysql_query($query);


if ($result) {
 
  echo '<select name="schools">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['school_id'] . '">' . ' ' . $row['school'] .  '</option>\\n';


  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The models could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }


?>
<p>Sort by: <input type="radio" name="sort" value="screen" />Model 
<input type="radio" name="sort" value="room_name" />Room

</fieldset>
<div align="center"><input type="submit" name="submit" value="Submit 
School Selection" /></div>
</form>




</body>
</html>


