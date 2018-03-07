<?php # district_view_computers2.php
//2018a

$page_title = 'Edit Computers by School';

include ('../../includes/header_district_computers2.php');

  if ( isset($_POST['schools']) ) {

    		$school_id = $_POST['schools'] ;
  			}
	elseif ( isset( $_GET['schools'] ) ) {
			$school_id = $_GET['schools'] ;
			}
	else {

  	echo "<p>You neglected to specify a school.</p>" ;	
  	exit();
	}

require_once ('../../../mysql_connect_inventory.php');

$query = "SELECT school FROM schools WHERE school_id = $school_id";


$result = @mysql_query($query);

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC) ) {
$school = $row['school'];

}

//mysql_free_result ($result);

echo "<h1 id=\"mainhead\" align=\"center\">$school Computer Inventory</h1>";
echo "<h3 align=\"center\">Edit computers assigned to rooms</h3>";


$display = 200 ;

if (isset($_GET['np']) ) {
	$num_pages = $_GET['np'];
	
	} else {
	
$query = "SELECT COUNT(*) FROM computers WHERE teacher_id = 0 AND school_id=$school_id ORDER BY service_tag ASC";
	$result = mysql_query($query);
	

	$row = mysql_fetch_array($result,MYSQL_NUM);
	$num_records = $row[0];

	echo "<h3 align=\"center\">Total computers = $num_records</h3>";

	if ($num_records > $display) {
		$num_pages = ceil($num_records/$display);
		//echo '<p>num_pages = ' . $num_pages . '</p>';
	} else {
		$num_pages = 1 ;
		echo '<p>num_pages = ' . $num_pages . '</p>';
	}
}

if (isset($_GET['s']) ) {
	$start = $_GET['s'];
	} else {
	$start = 0 ;
	}

//START defining column headers that sort in ascending and descending order

$link1 = "{$_SERVER['PHP_SELF']}?sort=rooma&schools=$school_id";
$link2 = "{$_SERVER['PHP_SELF']}?sort=modela&schools=$school_id";
$link3 = "{$_SERVER['PHP_SELF']}?sort=namea&schools=$school_id";
$link4 = "{$_SERVER['PHP_SELF']}?sort=staga&schools=$school_id";

if (isset($_GET['sort']) ) {
	switch ($_GET['sort']) {
		case 'rooma':
		$order_by = 'room_name ASC';
		$link1="{$_SERVER['PHP_SELF']}?sort=roomd&schools=$school_id";
		break;

		case 'roomd':
		$order_by = 'room_name DESC';
		$link1="{$_SERVER['PHP_SELF']}?sort=rooma&schools=$school_id";
		break;

		case 'modela':
		$order_by = 'model ASC';
		$link2="{$_SERVER['PHP_SELF']}?sort=modeld&schools=$school_id";
		break;

		case 'modeld':
		$order_by = 'model DESC';
		$link2="{$_SERVER['PHP_SELF']}?sort=modela&schools=$school_id";
		break;

		case 'namea':
		$order_by = 'computer_name ASC';
		$link3="{$_SERVER['PHP_SELF']}?sort=named&schools=$school_id";
		break;

		case 'named':
		$order_by = 'computer_name DESC';
		$link3="{$_SERVER['PHP_SELF']}?sort=namea&schools=$school_id";
		break;


		case 'staga':
		$order_by = 'service_tag ASC';
		$link4="{$_SERVER['PHP_SELF']}?sort=stagd&schools=$school_id";
		break;

		case 'stagd':
		$order_by = 'service_tag DESC';
		$link4="{$_SERVER['PHP_SELF']}?sort=staga&schools=$school_id";
		break;


		default :
		$order_by = 'room_name ASC';
		break;

		}

	$sort = $_GET['sort'];
} else {
	$order_by = 'service_tag DESC';
	$sort = "stagd&schools=$school_id";
}

$query = "SELECT room_name, computer_id, CONCAT(model, ' ',computer_type) AS model, computer_name, service_tag FROM room_names,locations, computer_models, computer_types, computers WHERE computers.location_id=locations.location_id AND locations.school_id=$school_id AND room_names.room_name_id=locations.room_name_id AND computers.model_id=computer_models.model_id AND computer_models.ct_id=computer_types.ct_id AND computers.teacher_id = 0 ORDER BY $order_by LIMIT $start,$display";

$result = @mysql_query($query);

//Column headers will be links.  The values for the $link variable have already been set

if ($result) {
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>

  <td align="left"><b>Edit</b></td>
  <td align="left"><b>Delete</b></td>
  <td align="left"><b><a href="' . $link1 . '">Room</a></b></td>
  <td align="left"><b><a href="' . $link2 . '">Model</a></b></td>
  <td align="left"><b><a href="' . $link3 . '">Name</a></b></td>

  <td align="left"><b><a href="' . $link4 . '">Service Tag</a></b></td>
    
  <!--  
  <td align="left"><b>Asset Tag</b></td>
	-->

</tr>';

$bg = '#eeeeee';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

    $bg = ($bg=='#eeeeee') ? '#ffffff' : '#eeeeee';
  echo '<tr bgcolor="' . $bg . '">



  <td align="left"><a href="district_edit_computers2.php?id=' . $row['computer_id'] . '&schools=' . $school_id . ' ">Edit</a></td>

  <td align="left"><a href="district_delete_computers.php?id=' . $row['computer_id'] . '&schools=' . $school_id . ' ">Delete</a></td>



<!--  

  <td align="left"><a href="district_edit_computers2.php?id=' . $row['computer_id'] . '&schools=' . $row['school_id'] . ' ">Edit</a></td>

  <td align="left"><a href="district_delete_computers.php?id=' . $row['computer_id'] . '&schools=' . $row['school_id'] . ' ">Delete</a></td>

-->


  <td align="left">' . $row['room_name'] . '</td>
  <td align="left" style="white-space: nowrap">' . $row['model'] . '</td>	
  <td align="left">' . $row['computer_name'] . '</td>
  <td align="left">' . $row['service_tag'] . '</td>
  
<!--  
  <td align="left">' . $row['asset_tag'] . '</td>
-->
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

if ($num_pages > 1) {
	echo '<br /><p>';
	$current_page = ($start/$display) + 1;
	
	echo "Sort is $sort and School is $school_id ID";
	
	if ($current_page != 1) {
		echo '<a href="district_view_computers2.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort . '&schools=' . $school_id . '">Previous </a>';
	}

	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="district_view_computers2.php?s=' .( ($display * ($i - 1) ) ) . '&np=' . $num_pages . '&sort=' . $sort . '&schools=' . $school_id . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
		if ($current_page != $num_pages) {
			echo '<a href="district_view_computers2.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort . '&schools=' . $school_id . '">Next</a>';
		}
		echo '</p>';
}



include ('../../includes/footer.html');

exit();

?>


