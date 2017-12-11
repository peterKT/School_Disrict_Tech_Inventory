<?php #Script 7.2 - mysql_connect.php

DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', 'admin');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'COMPUTERS');

$dbc = @mysql_connect (DB_HOST, DB_USER, DB_PASSWORD)
OR die ('Could not connect to MySQL:' . mysql_error() );

mysql_select_db (DB_NAME) 
OR die ('Could not select the database: ' . mysql_error() );

?>
