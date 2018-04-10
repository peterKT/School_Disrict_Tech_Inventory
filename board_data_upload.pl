#!/usr/bin/perl

# Go through /tmp/screens.csv line by line and use the five elements on
# each line to create a file for populating the smartboards table in the inventory database. 

# If any field is empty, stop and request a correction.

# Request database credentials from user and populate the computers table.

# If the expected values don't have an entry in the database, stop and
# request a correction.

# Verify validity of each field

# Check for duplicate

# use DBI;
use DBI qw(:sql_types);

print "To upload your screen data, I need to know an MySQL user with permissions on the database named: inventory\n";
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

open SCREENS, "<", "/tmp/screens.csv";
open UPLOAD, ">>", "/tmp/screens_upload.csv";

# Set up hash to determine room ID from room name

%room_matrix = ();

$query1 = "SELECT room_name_id,room_name FROM room_names";
$sth = $dbh->prepare($query1);
$sth->execute();

while (my $row = $sth->fetchrow_arrayref) {
#	@models = $row;
#	print "Got line @$row[0],@$row[1]\n";
	$room_matrix{@$row[1]} = @$row[0];
	$x = @$row[0];
	$y = @$row[1];
#	print "The value for hash with key $y is $room_matrix{$y} which should be $x\n"; 
	}

$sth->finish();

$line_counter = 1;
$errors = 0;

foreach (<SCREENS>) {

# Processing text file will stop if any errors are encountered

if ($errors == 0) {

chomp;

# Capture line of text from the file 

	@array = split /\,/, $_; 

	foreach $elem(@array) {

#		print "Now assigning values to variables.\n";	
		$model = shift(@array);
		if ($model =~ /[^\d+]/) {
			print "ERROR. $model on line $line_counter should be a screen ID number and it is not a number.\n";
			$errors = 1;
			last;
			} elsif ($model eq '') {
			print "ERROR. $model on line $line_counter should be a screen ID number and it is empty.\n";
			$errors = 1;
			last;
			}
		$serial = shift(@array);
		if (!defined($serial)) {
			print "ERROR. $serial on line $line_counter should be a serial number or Unknown and it is not.\n";
			$errors = 1;
			last;
			} elsif ($serial eq '') {
			print "ERROR. $serial on line $line_counter should be a serial number or Unknown and it is empty.\n";
			$errors = 1;
			last;
			}
		$mount = shift(@array);
		if ($mount =~ /[^\d+]/) {
			print "ERROR. $mount on line $line_counter should be a mount ID number and it is not a number.\n";
			$errors = 1;
			last;
			} elsif ($mount eq '') {
			print "ERROR. $mount on line $line_counter should be a mount ID number and it is empty.\n";
			$errors = 1;
			last;
			}
		$building = shift(@array);
		if ($building =~ /[^\d+]/) {
			print "ERROR. $building on line $line_counter should be a building ID number and it is not a number.\n";
			$errors = 1;
			last;
			} elsif ($building eq '') {
			print "ERROR. $building on line $line_counter should be a building number and it is empty.\n";
			$errors = 1;
			last;
			}
		$room = shift(@array);
		if (!defined($room)) {
			print "ERROR. Room name on line $line_counter should be a room name and it is $room.\n";
			$errors = 1;
			last;
			} elsif ($room eq '') {
			print "ERROR. Room name on line $line_counter should be a room name or it is $room.\n";
			$errors = 1;
			last;
			}

# Finally, make sure the location exists by checking the room name against the matrix

		$room_id = $room_matrix{"$room"};

		if ($room_id eq '') {
			print "Error. Your room ($room) is not found in your building ID $building.\n";
			print "Please fix, delete the incomplete file /tmp/screens_upload.csv, and re-run\n";
			$errors = 1;
			last;
			} else {
#			print "And the room ID for $room on line $line_counter is $room_id\n";

			$line_counter++;
		}	# CLOSE THE LAST OF THE ERROR CHECKS

	}	# CLOSE FOREACH ELEM ARRAY

print UPLOAD "$model,$serial,$mount,$building,$room_id\n";

} else {
print "Could not create the upload file because you need to do some error fixing.\n";
last;
}		# CLOSE IF ERRORS ARE ZERO

# Go to next line of computer_list.csv and repeat the above until the end of file

	
}		# CLOSE FOREACH LINE <COMPUTERS>


close SCREENS;
close UPLOAD;

# We now have all values ready to create an upload file and dump everthing into the smartboards table



if ($errors == 0) {


open UPLOAD, "<", "/tmp/screens_upload.csv";

	$query2 = "INSERT INTO smartboards(screen_id,serial_no,mount_id,location_id) SELECT ?,?,?,location_id FROM locations,room_names WHERE locations.school_id = ? AND room_names.room_name_id = ? AND locations.room_name_id = room_names.room_name_id";
	$sth = $dbh->prepare($query2) or die "Unable to prepare $query2";

foreach (<UPLOAD>) {

	chomp;

# Capture line of text from the file 

	@array = split /\,/, $_; 

	foreach $elem(@array) {
	
	$model = shift(@array);
	$serial = shift(@array);
	$mount = shift(@array);
	$building = shift(@array);
	$room = shift(@array);
	}

#	print "The values on this NEW line are $model, $serial, $mount, $building and room ID: $room.\n";

	$sth->bind_param(1,$model);
	$sth->bind_param(2,$serial, SQL_VARCHAR );
	$sth->bind_param(3,$mount);
	$sth->bind_param(4,$building);
	$sth->bind_param(5,$room);

	$sth->execute();
}
  	$sth->finish(); 

close UPLOAD;

$dbh->disconnect;

print "\n\nGreat... All your board display devices have been entered in the smartboards table.\n\n";

} else {

	print "\nSorry, you have some errors that need fixing.\n";
	print "You need to delete /tmp/screens_upload.csv and try again.\n";
	$dbh->disconnect;
}

