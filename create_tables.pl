#!/usr/bin/perl -w

use DBI;
print "To create the 21 tables, I need to know an MySQL user with permissions on the database named: inventory\n";
print "Please enter the user name here: \n";  
$admin_name = <STDIN>;
chomp ($admin_name);
print "\nNow please provide the password: \n";
$admin_pw = <STDIN>;
chomp ($admin_pw);

print "Great. Give me a sec, I will try using $admin_name and $admin_pw.\n";

$server = 'localhost';
$db = 'inventory';
$username = $admin_name;
$password = $admin_pw;


$dbh = DBI->connect("dbi:mysql:$db:$server", $username, $password);

$query1 = "CREATE TABLE computer_models (model_id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT, ct_id TINYINT(3) UNSIGNED NOT NULL, model VARCHAR(20) NOT NULL, mf_id SMALLINT(4) UNSIGNED NOT NULL DEFAULT '9', PRIMARY KEY (model_id) )";

$sth = $dbh->prepare($query1);

$sth->execute();

$query2 = "CREATE TABLE computer_types (ct_id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT, 
computer_type VARCHAR(18) NOT NULL, PRIMARY KEY (ct_id), UNIQUE KEY (computer_type))";

$sth = $dbh->prepare($query2);

$sth->execute();

$query3 = "CREATE TABLE computers (computer_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, model_id TINYINT(3) UNSIGNED NOT NULL, computer_name VARCHAR(24) NOT NULL DEFAULT 'Unknown', asset_tag VARCHAR(24) NOT NULL DEFAULT 'None', service_tag VARCHAR(16) NOT NULL DEFAULT 'Unknown', teacher_id SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0', location_id SMALLINT(4) UNSIGNED NOT NULL, date_changed TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (computer_id) )";


$sth = $dbh->prepare($query3);

$sth->execute();


$query4 = "CREATE TABLE room_names (room_name_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, room_name VARCHAR(20) NOT NULL, PRIMARY KEY (room_name_id) )";

$sth = $dbh->prepare($query4);

$sth->execute();

$query5 = "CREATE TABLE schools (school_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, 
school VARCHAR(20) NOT NULL, PRIMARY KEY (school_id) )";

$sth = $dbh->prepare($query5);

$sth->execute();

$query6 = "CREATE TABLE teachers (teacher_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, last_name VARCHAR(20), first_name VARCHAR(20), school_id SMALLINT(4) UNSIGNED NOT NULL DEFAULT '24', PRIMARY KEY (teacher_id) )";


$sth = $dbh->prepare($query6);
$sth->execute();

$query7 = "CREATE TABLE locations (location_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, room_name_id SMALLINT(4) UNSIGNED NOT NULL, school_id SMALLINT(4) UNSIGNED NOT NULL, PRIMARY KEY (location_id) )";

$sth = $dbh->prepare($query7);

$sth->execute();

$query8 = "CREATE TABLE manufacturers (mf_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, 
mf VARCHAR(12) NOT NULL, PRIMARY KEY (mf_id) )";

$sth = $dbh->prepare($query8);

$sth->execute();

$query9 = "CREATE TABLE projector_models (model_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, model VARCHAR(12) NOT NULL, mf_id SMALLINT(4) UNSIGNED NOT NULL, PRIMARY KEY (model_id) )";

$sth = $dbh->prepare($query9);
$sth->execute();

$query10 = "CREATE TABLE mounts (mount_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, mount VARCHAR(12) NOT NULL, PRIMARY KEY (mount_id) )";

$sth = $dbh->prepare($query10);

$sth->execute();


$query11 = "CREATE TABLE projectors (projector_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, model_id SMALLINT(4) UNSIGNED NOT NULL, location_id SMALLINT(4) UNSIGNED NOT NULL, PRIMARY KEY (projector_id) )";

$sth = $dbh->prepare($query11);

$sth->execute();

$query12 = "CREATE TABLE lamps (lamp_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, 
lamp_no VARCHAR(12) NOT NULL, model_id SMALLINT(4) UNSIGNED NOT NULL, stock_qty VARCHAR(7) NOT NULL DEFAULT '0', school_id SMALLINT(4) UNSIGNED NOT NULL, PRIMARY KEY (lamp_id) )";

$sth = $dbh->prepare($query12);

$sth->execute();

$query13 = "CREATE TABLE smartboards (board_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, screen_id SMALLINT(4) UNSIGNED NOT NULL, serial_no VARCHAR(16) NOT NULL DEFAULT 'Unknown', mount_id SMALLINT(4) UNSIGNED NOT NULL, location_id SMALLINT(4) UNSIGNED NOT NULL, PRIMARY KEY (board_id) )";

$sth = $dbh->prepare($query13);
$sth->execute();


$query14 = "CREATE TABLE screens (screen_id SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT, screen VARCHAR(20) NOT NULL, PRIMARY KEY (screen_id) )";

$sth = $dbh->prepare($query14);

$sth->execute();

$query15 = "CREATE TABLE printer_models (printer_model_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, printer_no VARCHAR(12) NOT NULL, pt_id SMALLINT(4) UNSIGNED NOT NULL, PRIMARY KEY (printer_model_id) )";

$sth = $dbh->prepare($query15);

$sth->execute();

$query16 = "CREATE TABLE printer_types (pt_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, type VARCHAR(25) NOT NULL, PRIMARY KEY (pt_id) )";

$sth = $dbh->prepare($query16);

$sth->execute();

$query17 = "CREATE TABLE printers (printer_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, printer_model_id SMALLINT(4) UNSIGNED NOT NULL, location_id SMALLINT(4) UNSIGNED NOT NULL, date_changed TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (printer_id) )";

$sth = $dbh->prepare($query17);

$sth->execute();

$query18 = "CREATE TABLE printer_toner_matrix (mid SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, printer_model_id SMALLINT(4) UNSIGNED NOT NULL, toner_id SMALLINT(4) UNSIGNED NOT NULL, PRIMARY KEY (mid) )";

$sth = $dbh->prepare($query18);

$sth->execute();

$query19 = "CREATE TABLE toner (toner_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, tpmer_no VARCHAR(12) NOT NULL, toner_color_id SMALLINT(4) UNSIGNED NOT NULL, toner_alias VARCHAR(8) NOT NULL DEFAULT 'None', PRIMARY KEY (toner_id) )";

$sth = $dbh->prepare($query19);

$sth->execute();

$query20 = "CREATE TABLE toner_color (toner_color_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, toner_color VARCHAR(7) NOT NULL, PRIMARY KEY (toner_color_id) )";

$sth = $dbh->prepare($query20);

$sth->execute();

$query21 = "CREATE TABLE toner_inventory (ti_id SMALLINT(4) UNSIGNED NOT NULL AUTO_INCREMENT, toner_id SMALLINT(4) UNSIGNED NOT NULL, location_id SMALLINT(4) UNSIGNED NOT NULL, quantity SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0', date_changed TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (ti_id) )";

$sth = $dbh->prepare($query21);

$sth->execute();


$query22 = "INSERT INTO manufacturers(mf) VALUES('Epson'),('Hitachi'),('NEC'),('Smart'),('Brand X'),('Optoma'),('InFocus'),('TEQ'),('Unknown'),('Dell'),('HP'),('IBM'),('Samsung'),('Apple')";

$sth = $dbh->prepare($query22);

$sth->execute();


$query23 = "INSERT INTO projector_models(model_id,model,mf_id) VALUES(20,'Unknown',9)";

$sth = $dbh->prepare($query23);

$sth->execute();


$query24 = "INSERT INTO schools(school_id,school) VALUES(24,'Unknown')";

$sth = $dbh->prepare($query24);

$sth->execute();

$query25 = "INSERT INTO computer_types(computer_type) VALUES('Tower'),('Desktop'),('Laptop'),('Server'),('Netbook'),('Tablet'),('Chromebook'),('iMac'),('All-in-One')";

$sth = $dbh->prepare($query25);

$sth->execute();


$dbh->disconnect;
