
<?php
$page_title = 'View District Computer Inventory';
//from VIEW ENTIRE COMPUTER INVENTORY option on header. Select radio buttons by model or school
include ('../../includes/header_district_computers.html');
#echo '<h1 id="mainhead">Printers</h1>';
?>



<?php

if (isset($_POST['submit'])) {					//OPEN SUBMIT

  require_once ('../../../mysql_connect_computers.php');




if ( !isset($_POST['search'])  ){
	
	echo "<p>You forgot to specify a search term.</p>" ;
	exit();
	}

$search = $_POST['search'] ;

//SORT BY COMPUTER MODEL

if ($search=='M'   ) {

$query = "SELECT model, computer_type, service_tag, school FROM
computer_models,computer_types,computers,schools WHERE computers.model_id=computer_models.model_id AND computer_types.ct_id = computer_models.ct_id AND computers.school_id=schools.school_id ORDER BY model" ;

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">District Computer Inventory</h1>';
  echo "<h3 align=\"center\">Total computers = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Model</b></td>
  <td align="left"><b>Type</b></td>
  <td align="left"><b>Service Tag</b></td>
  <td align="left"><b>School</b></td>
</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['model'] . '</td>

  <td align="left">' . $row['computer_type'] . '</td>

  <td align="left">' . $row['service_tag'] . '</td>

  <td align="left">' . $row['school'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The computers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();
 }

//SORT BY COMPUTER TYPE

elseif ($search=='T'   ) {

$query = "SELECT model, computer_type, service_tag, school FROM
computer_models,computer_types,computers,schools WHERE computers.model_id=computer_models.model_id AND computer_types.ct_id = computer_models.ct_id AND computers.school_id=schools.school_id ORDER BY computer_type" ;

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">District Computer Inventory</h1>';
  echo "<h3 align=\"center\">Total computers = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Model</b></td>
  <td align="left"><b>Type</b></td>
  <td align="left"><b>Service Tag</b></td>
  <td align="left"><b>School</b></td>
</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['model'] . '</td>

  <td align="left">' . $row['computer_type'] . '</td>

  <td align="left">' . $row['service_tag'] . '</td>

  <td align="left">' . $row['school'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The computers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();

 }

//SORT BY SCHOOL

else if ($search=='S'   ) {


$query = "SELECT model, computer_type, service_tag, school FROM
computer_models,computer_types,computers,schools WHERE computers.model_id=computer_models.model_id AND computer_types.ct_id = computer_models.ct_id AND computers.school_id=schools.school_id ORDER BY school,model" ;

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">District Computer Inventory</h1>';
  echo "<h3 align=\"center\">Total computers = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Model</b></td>
  <td align="left"><b>Type</b></td>
  <td align="left"><b>Service Tag</b></td>
  <td align="left"><b>School</b></td>
</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['model'] . '</td>

  <td align="left">' . $row['computer_type'] . '</td>

  <td align="left">' . $row['service_tag'] . '</td>

  <td align="left">' . $row['school'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The computers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();


 }

//SORT BY SERVICE TAG OR SERIAL NUMBER

else if ($search=='N'   ) {


$query = "SELECT model, computer_type, service_tag, school FROM
computer_models,computer_types,computers,schools WHERE computers.model_id=computer_models.model_id AND computer_types.ct_id = computer_models.ct_id AND computers.school_id=schools.school_id ORDER BY service_tag" ;

$result = @mysql_query($query);
$num = mysql_num_rows($result);

if ($result) {
  echo '<h1 align="center">District Computer Inventory</h1>';
  echo "<h3 align=\"center\">Total computers = $num</h3>";
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>
  <td align="left"><b>Model</b></td>
  <td align="left"><b>Type</b></td>
  <td align="left"><b>Service Tag</b></td>
  <td align="left"><b>School</b></td>
</tr>';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  echo '<tr><td align="left">' . $row['model'] . '</td>

  <td align="left">' . $row['computer_type'] . '</td>

  <td align="left">' . $row['service_tag'] . '</td>

  <td align="left">' . $row['school'] . '</td>

</tr>';
}
  echo '</table>';

mysql_free_result ($result);
} else {
  echo '<p class="error">The computers could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
}

mysql_close();  
exit();


 }










}  //CLOSE THE SUBMIT
include ('../../includes/footer.html');
?>

<center>
<form action="index_district_computers.php" method="post">
&nbsp;
<h1 id="mainhead">District Computer Inventory</h1>
<h3>Reporting Schools: Bardonia, Birchwood, Chestnut Grove, Laurel Plains, Strawtown, West Nyack</h3>
<fieldset style="width:500px"><legend>View computer inventory for Clarkstown School District</legend>

<p>Sort by: <input type="radio" name="search" value="M" />Computer Model 

<input type="radio" name="search" value="T" />Computer Type

<input type="radio" name="search" value="S" />School

<input type="radio" name="search" value="N" />Serial Number
</p>




</fieldset>
<br />
<div align="center"><input type="submit" name="submit" value="Submit 
Info Request" /></div>
</form>
</center>
</body>
</html>


