 <?php # district_add_ink.php
//From district_add_printer_info_nodups3.php

$page_title = 'Add Ink';
include ('../includes/header_district_printers.html');

if ( isset($_POST['submitted']) ) { 				// START SUBMIT, MUST CLOSE
require_once('../../mysql_connect_inventory.php');

	$mn = trim($_POST['model']);
	echo '<p>The $mn $black values are ' . $mn . ' '. $_POST['black'] . '. </p>' ;

	$errors = array();

	$query = "SELECT printer_model_id FROM printer_models WHERE printer_no='$mn'";	
	$result = mysql_query($query);
	$row = mysql_fetch_array($result,MYSQL_ASSOC) ;
	$pid = $row['printer_model_id'];
	$query = "SELECT mid from  printer_toner_matrix where printer_model_id = $pid" ;
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	
	if ($num != 0 ) {
		$errors[] = 'This printer already has at least one ink cartridge associated with it. Please edit.'  ;
		  
	}


	if (empty($_POST['black'])) {					// START ERROR COLLECTION
 		 $errors[] = 'You did not enter black ink cartridge information.';
	} else {
		$black = trim($_POST['black']) ;
		$color_black = 1;
			if ( !empty($_POST['alias1']) ) {
				$alias_black = $_POST['alias1'] ;
				}
		}

	if ( !empty($_POST['multi']) && ( !empty($_POST['cyan']) || !empty($_POST['magenta']) || !empty($_POST['yellow']) ) ){			
  		$errors[] = 'You entered information for a multicolor cartridge and at least one single-color cartridge.';
	} else {				
		$multi = trim($_POST['multi']) ;
		$color_multi = 5 ;
			if ( !empty($_POST['alias2']) ) {
				$alias_multi = $_POST['alias2'] ;
				}
		} 

	if ( (!empty($_POST['cyan']) || !empty($_POST['magenta']) || !empty($_POST['yellow']) ) AND
		( empty($_POST['magenta']) || empty($_POST['cyan']) || empty($_POST['yellow'])   )  ) {

	  	$errors[] = 'You didn\'t enter all colors.';
	} else { 
		$cyan = trim($_POST['cyan']) ;
		$color_cyan = 2 ;
		
		$magenta = trim($_POST['magenta']) ;
		$color_magenta = 3 ;
	
		$yellow = trim($_POST['yellow']) ;
		$color_yellow = 4 ;


echo '<p>The values for the three colors are ' . $cyan . ' ' . $magenta . ' ' . $yellow . ' </p>' ;
		}
		
		if (empty($errors) ) {			// IF EMPTY ERRORS
							//Enter the printer ink cartridge number(s) and its color

							// ENTER BLACK INK

			if ( !empty($black) ) {

				

				$query = "SELECT toner_id FROM toner WHERE toner_no='$black'" ;
				$result = mysql_query($query);
				$num = mysql_num_rows($result);
				echo "<p>The value of \$num is $num</p>";
				echo "<p>The value of \$black is $black</p>";
				echo "<p>The value of \$color_black is $color_black</p>";
				echo "<p>The value of \$alias_black is $alias_black</p>";
				
				
				/*
				If toner is new to database, update toner then associate new printer with new toner in printer_toner_matrix
				*/
	
					if ($num == 0) {		

				if ( !empty($alias_black) ) {	

  					$query = "INSERT INTO toner (toner_no,toner_color_id,toner_alias) VALUES ('$black','$color_black','$alias_black')";					
  					$result = mysql_query($query);
					} else {
					$query = "INSERT INTO toner (toner_no,toner_color_id) VALUES ('$black','$color_black')";					
  					$result = mysql_query($query);
					}
					
					if ( mysql_affected_rows($dbc) == 1 ) {

							
						$query = "SELECT printer_model_id FROM printer_models WHERE printer_no='$mn'";			///UPDATE printer_toner_matrix	
						$result = mysql_query($query);
						$row = mysql_fetch_array($result,MYSQL_ASSOC) ;
						$pid = $row['printer_model_id'];

						$query = "SELECT toner_id FROM toner WHERE toner_no='$black' ";
						$result = mysql_query($query);
						$row = mysql_fetch_array($result,MYSQL_ASSOC) ;
						$inkid = $row['toner_id'] ;

						$query = "INSERT INTO printer_toner_matrix (printer_model_id,toner_id) VALUES ($pid,$inkid)";
						$result = mysql_query($query);

				$body = "The values in toner and printer_toner_matrix have been updated, adding BLACK ink number '$black' and color number '$color_black' ('$pid','$inkid') .\n\n" ;
				mail ('ptitus@localhost', 'Change in printers database', $body, 'From: district_add_ink.php') ;

 
					} else {

  						echo '<h1 id="mainhead">System Error</h1>
  				<p class="error">Your black ink data could not be entered due to a system error. We apologize for any inconvenience.</p>';

  						echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  						echo '</body></html>';
						}

						
					}					//CLOSE IF NUM=0
/*
If not new to database, simply associate new printer with existing toner in printer_toner-matrix
*/

					elseif ($num == 1) {		

		$query = "SELECT printer_model_id FROM printer_models WHERE printer_no='$mn'";			
		$result = mysql_query($query);
		$row = mysql_fetch_array($result,MYSQL_ASSOC) ;
//		$pid = $row['printer_id']; ERROR
		$pid = $row['printer_model_id'];

		$query = "SELECT toner_id FROM toner WHERE toner_no='$black' ";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result,MYSQL_ASSOC) ;
		$inkid = $row['toner_id'] ;
		$query = "INSERT INTO printer_toner_matrix (printer_model_id,toner_id) VALUES ($pid,$inkid)";
		$result = mysql_query($query);

		$body = "The values in printer_toner_matrix have been updated, adding PID and INKID as follows: ('$pid','$inkid') $black was already entered.\n\n" ;
		mail ('ptitus@localhost', 'Change in printers database', $body, 'From: district_add_ink.php') ;
								}


			else {

  		echo '<h1 id="mainhead">System Error</h1>
  		<p class="error">Your black ink data could not be entered due to a system error. We apologize for any inconvenience.</p>';

  		echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  		echo '</body></html>';
			}

	}
								//CLOSED UPDATE BLACK INK

								// OPEN UPDATE MULTI



if ( !empty($multi)) {
	$query = "SELECT toner_id FROM toner WHERE toner_no=('$multi')" ;
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	
				echo "<p>The value of \$num is $num</p>";
				echo "<p>The value of \$multi is $multi</p>";
				echo "<p>The value of \$color_multi is $color_multi</p>";
				echo "<p>The value of \$alias_multi is $alias_multi</p>";


		if ($num == 0) {				// IF NEW TO DATABASE ENTER AND UPDATE PINKS
			if ( !empty($alias_multi) ) {		// Add alias info if  necessary
  		$query = "INSERT INTO toner (toner_no,toner_color_id,toner_alias) VALUES ('$multi','$color_multi','$alias_multi')";
		$result = mysql_query($query);
					} else {
			$query = "INSERT INTO toner (toner_no,toner_color_id) VALUES ('$multi','$color_multi')";
  					$result = mysql_query($query);
					}

  								
//  			$result = mysql_query($query);
			if (mysql_affected_rows($dbc) == 1) {			//IF ENTER GOES THRU UPDATE PINKS

							
				$query = "SELECT printer_model_id FROM printer_models WHERE printer_no='$mn'";			
				$result = mysql_query($query);
				$row = mysql_fetch_array($result,MYSQL_ASSOC) ;
				$pid = $row['printer_model_id'];

				$query = "SELECT toner_id FROM toner WHERE toner_no='$multi' ";
				$result = mysql_query($query);
				$row = mysql_fetch_array($result,MYSQL_ASSOC) ;
				$inkid = $row['toner_id'] ;
				$query = "INSERT INTO printer_toner_matrix (printer_model_id,toner_id) VALUES ($pid,$inkid)";
				$result = mysql_query($query);

				$body = "The values in toner and printer_toner_matrix have been updated, adding MULTI ink number '$multi' and color number '$color_multi'. PID and INKID: ('$pid','$inkid') .\n\n" ;
				mail ('ptitus@localhost', 'Change in printers database', $body, 'From: district_add_ink.php') ;

 
			}						//END IF ENTER GOES THRU

	else {

  		echo '<h1 id="mainhead">System Error</h1>
  		<p class="error">Your multi-color ink data could not be entered due to a system error. We apologize for any inconvenience.</p>';

  		echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  		echo '</body></html>';
		}

	}							//END IF NEW TO DATABASE 

	elseif ($num == 1) {					//IF NOT NEW TO DATABASE, SIMPLY UPDATE printer_toner_matrix

		$query = "SELECT printer_model_id FROM printer_models WHERE printer_no='$mn'";			
		$result = mysql_query($query);
		$row = mysql_fetch_array($result,MYSQL_ASSOC) ;
		$pid = $row['printer_model_id'];

		$query = "SELECT toner_id FROM toner WHERE toner_no='$multi' ";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result,MYSQL_ASSOC) ;
		$inkid = $row['toner_id'] ;
			$query = "INSERT INTO printer_toner_matrix (printer_model_id,toner_id) VALUES ( $pid, $inkid )";
		$result = mysql_query($query);

		$body = "The values in printer_toner_matrix have been updated, adding MULTI PID and INKID as follows: ('$pid','$inkid') $multi was already entered.\n\n" ;
		mail ('ptitus@localhost', 'Change in printers database', $body, 'From: district_add_ink.php') ;
		}


	else {							//OTHERWISE ISSUE ERROR

  		echo '<h1 id="mainhead">System Error</h1>
  		<p class="error">Your black ink data could not be entered due to a system error. We apologize for any inconvenience.</p>';

  		echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

 		 echo '</body></html>';
		}

		}
								//CLOSE UPDATE MULTI





if ( !empty($cyan)) {						//OPEN UPDATE COLORS

	$query = "SELECT toner_id FROM toner WHERE toner_no=('$cyan')" ;
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	
		if ($num == 0) {					// IF NEW TO DATABASE ENTER AND UPDATE PINKS 

 			$query = "INSERT INTO toner (toner_no,toner_color_id) VALUES
  			('$cyan','$color_cyan'), ('$magenta','$color_magenta'),('$yellow','$color_yellow')";
			$result = mysql_query($query);

				if ( mysql_affected_rows($dbc)==3 ) {		//IF ENTER GOES THRU UPDATE PINKS


					$query = "SELECT printer_model_id FROM printer_models WHERE printer_no='$mn'";						
					$result = mysql_query($query);
					$row = mysql_fetch_array($result) ;
					$pid = $row['printer_model_id'];

					echo "<p>The value of \$pid is $pid</p>" ;
		
					$query = "SELECT toner_id FROM toner WHERE toner_no='$cyan' || toner_no='$magenta' || toner_no='$yellow' ";


					$result2 = mysql_query($query);
						while ($row = mysql_fetch_array($result2,MYSQL_ASSOC) ) {
						$inkid = $row['toner_id'] ;
						echo "<p>The value of \$inkid is $inkid</p>" ;
						$query = "INSERT INTO printer_toner_matrix (printer_model_id,toner_id) VALUES ( $pid,$inkid )";
						$result3 = mysql_query($query);
						}

						$body = "The values in toner and printer_toner_matrix have been updated, adding ink number '$cyan', '$magenta', '$yellow' with color number '$color_cyan', '$color_magenta', '$color_yellow) and in pinks for three entries, i.e. ('$pid','$inkid') .\n\n" ;
						mail ('ptitus@localhost', 'Change in printers database', $body, 'From: district_add_ink.php') ;
						
					}				// CLOSE UPDATE PINKS
			
  			
					} // CLOSE IF NUM=0

		elseif ($num == 1) {						//IF NOT NEW TO DATABASE, SIMPLY UPDATE PINKS
			$query = "SELECT printer_model_id FROM printer_models WHERE printer_no='$mn'";						
			$result = mysql_query($query);
			$row = mysql_fetch_array($result) ;
			$pid = $row['printer_model_id'];
		
			$query = "SELECT toner_id FROM toner WHERE toner_no='$cyan' || toner_no='$magenta' || toner_no='$yellow' ";


			$result = mysql_query($query);
				while ($row = mysql_fetch_array($result) ) {
					$inkid = $row['toner_id'] ;
					$query = "INSERT INTO printer_toner_matrix (printer_model_id,toner_id) VALUES ( $pid, $inkid )";
					$result2 = mysql_query($query);


					$body = "The values in PINKS have been updated for PID and INKID: ('$pid','$inkid') .\n\n" ;
					mail ('ptitus@localhost', 'Change in printers database', $body, 'From: district_add_ink.php') ;

					}
		}						// CLOSE IF NUM=1

	else {							//OTHERWISE ISSUE ERROR

 		 echo '<h1 id="mainhead">System Error</h1>
  		<p class="error">Your colors ink data could not be entered due to a system error. We apologize for any inconvenience.</p>';

  		echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  		echo '</body></html>';
		}

	}
								//CLOSED IF !EMPTY CYAN



 } else {

							// CLOSE IF EMPTY ERRORS, OPEN IF ERRORS

echo '<h1 id="mainhead">Error!</h1>
  	<p class="error">The following error(s) occurred:<br />';
  		foreach ($errors as $msg) {
  			echo " -$msg<br />\n";
			}
  	 		echo '</p><p>Please try again.</p><p><br /></p>';
			exit();
	}							//CLOSE IF ERRORS
   							 
								//CLOSE SUBMIT
}
?>
	


