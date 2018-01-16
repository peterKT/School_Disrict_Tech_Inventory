<?php # Script 7.4 - view_computers2.php
//2018a
$page_title = 'View and Edit Notebooks';

include ('../includes/header_computers2.html');
echo '<h1 id="mainhead" align="center">South H.S. Netbook/Laptop Computers Assigned to People</h1>';




require_once ('../../mysql_connect_computers.php');
$display = 200 ;

if (isset($_GET['np']) ) {
	$num_pages = $_GET['np'];
	
	} else {
	
	$query = "SELECT COUNT(*) FROM computers WHERE teacher_id != 143 ORDER BY service_tag ASC";
	$result = mysql_query($query);
	

	$row = mysql_fetch_array($result,MYSQL_NUM);
	$num_records = $row[0];

	echo "<h3 align=\"center\">Total mobile computers assigned to people = $num_records</h3>";

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

$link1 = "{$_SERVER['PHP_SELF']}?sort=teachera";
$link2 = "{$_SERVER['PHP_SELF']}?sort=modela";
$link3 = "{$_SERVER['PHP_SELF']}?sort=namea";
$link4 = "{$_SERVER['PHP_SELF']}?sort=staga";

if (isset($_GET['sort']) ) {
	switch ($_GET['sort']) {
		case 'teachera':
		$order_by = 'teacher ASC';
		$link1="{$_SERVER['PHP_SELF']}?sort=teacherd";
		break;

		case 'teacherd':
		$order_by = 'teacher DESC';
		$link1="{$_SERVER['PHP_SELF']}?sort=teachera";
		break;

		case 'modela':
		$order_by = 'model ASC';
		$link2="{$_SERVER['PHP_SELF']}?sort=roomd";
		break;

		case 'modeld':
		$order_by = 'model DESC';
		$link2="{$_SERVER['PHP_SELF']}?sort=rooma";
		break;

		case 'namea':
		$order_by = 'computer_name ASC';
		$link3="{$_SERVER['PHP_SELF']}?sort=roomd";
		break;

		case 'named':
		$order_by = 'computer_name DESC';
		$link3="{$_SERVER['PHP_SELF']}?sort=rooma";
		break;


		case 'staga':
		$order_by = 'service_tag ASC';
		$link4="{$_SERVER['PHP_SELF']}?sort=roomd";
		break;

		case 'stagd':
		$order_by = 'service_tag DESC';
		$link4="{$_SERVER['PHP_SELF']}?sort=rooma";
		break;


		default :
		$order_by = 'teacher ASC';
		break;

		}

	$sort = $_GET['sort'];
} else {
	$order_by = 'service_tag DESC';
	$sort = 'stagd';
}
	


//echo '<p>start = ' . $start . '</p>';

$query = "SELECT CONCAT(first_name, ' ',last_name) AS teacher, computer_id, CONCAT(model, ' ',computer_type) AS model, computer_name, service_tag FROM teachers, computers, computer_models, computer_types WHERE computers.teacher_id != 143 AND teachers.teacher_id=computers.teacher_id AND computers.model_id=computer_models.model_id AND computer_models.ct_id=computer_types.ct_id ORDER BY $order_by LIMIT $start,$display";


$result = @mysql_query($query);

//Column headers will be links.  The values for the $link variable have already been set

if ($result) {
  echo '<table align="center" cellspacing="0" cellpadding="5"><tr>

  <td align="left"><b>Edit</b></td>
  <td align="left"><b>Delete</b></td>
  <td align="left"><b><a href="' . $link1 . '">Teacher</a></b></td>
  <td align="left"><b><a href="' . $link2 . '">Model</a></b></td>
  <td align="left"><b><a href="' . $link3 . '">Name</a></b></td>
  <td align="left"><b><a href="' . $link4 . '">Service Tag</a></b></td>

</tr>';

$bg = '#eeeeee';

  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

    $bg = ($bg=='#eeeeee') ? '#ffffff' : '#eeeeee';
  echo '<tr bgcolor="' . $bg . '">

  <td align="left"><a href="edit_netbooks2.php?id='  . $row['computer_id'] . '">Edit</a></td>

  <td align="left"><a href="delete_computers.php?id=' . $row['computer_id'] . ' ">Delete</a></td>

  <td align="left">' . $row['teacher'] . '</td>
  <td align="left">' . $row['model'] . '</td>	

  <td align="left">' . $row['computer_name'] . '</td>
  <td align="left">' . $row['service_tag'] . '</td>

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
	
	if ($current_page != 1) {
		echo '<a href="edit_netooks.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort . '">Previous</a>';
	}

	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="edit_netbooks.php?s=' .( ($display * ($i - 1) ) ) . '&np=' . $num_pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	}
		if ($current_page != $num_pages) {
			echo '<a href="edit_netbooks.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort . '">Next</a>';
		}
		echo '</p>';
}



include ('../includes/footer.html');
?>


