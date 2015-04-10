<?php # - mysql_connect.php

DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', 'admin');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'sitename');

$dbc = @mysql_connect (DB_HOST, DB_USER, DB_PASSWORD)
OR die ('Could not connect to MySQL:' . mysql_error() );

mysql_select_db (DB_NAME) 
OR die ('Could not select the database: ' . mysql_error() );

function escape_data($data) {
	if (ini_get('magic_quotes_gpc') ) {
		$data = stripslashes($data);
	}

	if (function_exits('mysql_real_escape_string') ) {
		global $dbc;
		$data = mysql_real_escape_string(trim($data), $dbc) ;
	
	} else {
		$data = mysql_real_escape_string($data);
	}

	return $data;
	} 

?>
