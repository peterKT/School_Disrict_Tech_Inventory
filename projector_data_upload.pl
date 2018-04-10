#!/usr/bin/perl

# Go through /tmp/projectors.csv line by line and use the elements on
# each line to create a file for populating the printers table in the inventory database. 
# Convert projector model info to projector model numerical value.
# Take the last two elements--building ID and room name--and create the
# single element location ID. Now we have two elements: 1) projector model ID), 
# 2) location_id. 

# Append to /tmp/projector_upload.csv. Go to next line, repeat.

# If any field is empty, stop and request a correction.

# Request database credentials from user and populate the computers table.

# If the model ID or location ID don't have an entry in the database, stop and
# request a correction.

# Verify validity of each field

# Check for duplicate

# use DBI;
use DBI qw(:sql_types);

print "To upload your projector data, I need to know an MySQL user with permissions on the database named: inventory\n";
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



# Set up hash to determine room ID from room name

%room_matrix = ();

$query1 = "SELECT room_name_id,room_name FROM room_names";
$sth = $dbh->prepare($query1);
$sth->execute();

while (my $row = $sth->fetchrow_arrayref) {
	$room_matrix{@$row[1]} = @$row[0];
	$a = @$row[0];
	$b = @$row[1];
#	print "The value for hash with key $b is $room_matrix{$b} which should be $a\n"; 
	}

$sth->finish();


open PROJECTORS, "<", "/tmp/projectors.csv";
open UPLOAD, ">>", "/tmp/projectors_upload.csv";


$line_counter = 1;
$errors = 0;

foreach (<PROJECTORS>) {

# Processing text file will stop if any errors are encountered

if ($errors == 0) {

chomp;

# Capture line of text from the file 

	@array = split /\,/, $_; 

	foreach $elem(@array) {

		print "Now assigning values to variables.\n";	
		$model_id = shift(@array);
		if ($building =~ /[^\d+]/) {
			print "ERROR. $model_id on line $line_counter should be a building ID number and it is not a number.\n";
			$errors = 1;
			last;
			} elsif ($model_id eq '') {
			print "ERROR. $model_id on line $line_counter should be a building number and it is empty.\n";
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
#		print "The errors are $errors and values on this line are $model, $name, $asset, $serial, $building, $room\n";

# Finally, make sure the location exists by checking the room name against the matrix

		$room_id = $room_matrix{"$room"};

		if ($room_id eq '') {
			print "Error. Your room ($room) is not found in your building ID $building.\n";
			print "Please fix, delete the incomplete file /tmp/projector_upload.csv, and re-run\n";
			$errors = 1;
			last;
			} else {
#			print "And the room ID for $room on line $line_counter is $room_id\n";


		}	# CLOSE THE LAST OF THE ERROR CHECKS


	}	# CLOSE FOREACH ELEM ARRAY

print "For line number $line_counter we got these values: \n";
print "The building is $building and the room is $room with ID $room_id which will be used to determine the location ID.\n";
print "The projector model ID number is $model_id.\n";

# UNCOMMENT THIS LINE IF STUFF WORKS

print UPLOAD "$model_id,$building,$room_id\n";

} else {
print "Could not create the upload file because you need to do some error fixing.\n";
last;
}		# CLOSE IF ERRORS ARE ZERO

# Go to next line of projectors.csv and repeat the above until the end of file

$line_counter++;	

}		# CLOSE FOREACH LINE <PROJECTORS>


close PROJECTORS;
close UPLOAD;

# We now have all values ready to create an upload file and dump values into the projectors table

if ($errors == 0) {


open UPLOAD, "<", "/tmp/projectors_upload.csv";

	$query2 = "INSERT INTO projectors(model_id,location_id) SELECT ?, location_id FROM locations,room_names WHERE locations.school_id = ? AND room_names.room_name_id = ? AND locations.room_name_id = room_names.room_name_id";
	$sth = $dbh->prepare($query2) or die "Unable to prepare $query2";

foreach (<UPLOAD>) {

	chomp;

# Capture line of text from the file 

	@array = split /\,/, $_; 

	foreach $elem(@array) {
	$model = shift(@array);
	$building = shift(@array);
	$room = shift(@array);
	print "Uploading two values: $model and the location ID created by $building and $room\n";
	}

	$sth->bind_param(1,$model);
	$sth->bind_param(2,$building);
	$sth->bind_param(3,$room);

	$sth->execute();
}
  	$sth->finish(); 

close UPLOAD;

$dbh->disconnect;

print "\n\nGreat... All your projectors have been entered in the printers table.\n\n";

} else {

	print "\nSorry, you have some errors that need fixing.\n";
	print "Delete /tmp/projector_upload.csv and try again.\n";
	$dbh->disconnect;
}
