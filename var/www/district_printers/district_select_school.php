<?php
$page_title = 'Select a School';


//This page is to view printer or cartridge inventory by school


include ('../includes/header_district_printers.html');

?>

<br /><br />


<h2>Select a building to view printer or toner cartridge inventory</h2>

<form action="district_view_printers&ink_by_school.php" method="post">
<fieldset><legend>Select a School</legend>


<?php

require_once ('../../mysql_connect_district_printers.php');


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


echo '<br><br /><br /><p>In this building I want to view : 
 
<br /><input type="radio" name="view" value="printers" />Printers 
<br /><input type="radio" name="view" value="cart" />Cartridges
</p>' ;


//echo "<input type=\"hidden\" name=\"school_id\" value= \"$row[school_id]\" >" ;



?>




</fieldset>
<div align="center"><input type="submit" name="submit" value="Submit 
School Selection" /></div>
</form>


<?php
include ('../includes/footer_district.html');

/*
</div>
</div>
</body>
</html>
*/

?>
