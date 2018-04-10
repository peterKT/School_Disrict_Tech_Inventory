#!/usr/bin/perl

# Go through /tmp/printers.csv line by line and use the elements on
# each line to create a file for populating the printers table in the inventory database. 
# Convert printer info to printer type numerical value and printer model numerical value.
# Take the last two elements--building ID and room name--and create the
# single element location ID. Now we have three elements: 1) printer type ID), 
# 2) printer model ID and 3) location_id. 

# Append to /tmp/printer_upload.csv. Go to next line, repeat.

# If any field is empty, stop and request a correction.

# Request database credentials from user and populate the computers table.

# If the model ID or location ID don't have an entry in the database, stop and
# request a correction.

# Verify validity of each field

# Check for duplicate

# use DBI;
use DBI qw(:sql_types);

print "To upload your computer data, I need to know an MySQL user with permissions on the database named: inventory\n";
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

# Set up hash to determine printer type ID from type

%type_matrix = ();

$query2 = "SELECT pt_id,type FROM printer_types";
$sth = $dbh->prepare($query2);
$sth->execute();

while (my $row = $sth->fetchrow_arrayref) {
	$type_matrix{@$row[1]} = @$row[0];
	}

$sth->finish();

# Set up hash to determine printer model ID from model number and type ID

%model_matrix = ();

$query3 = "SELECT printer_model_id,printer_no,pt_id FROM printer_models";
$sth = $dbh->prepare($query3);
$sth->execute();

while (my $row = $sth->fetchrow_arrayref) {
	$x = @$row[2];
	$y = @$row[1];
	$z = @$row[0];
	$type = "$x" . " " . "$y";
	
	$model_matrix{$type} = $z;

	print "The value for hash with key $type is $model_matrix{$type} which should be $z\n"; 
	}

$sth->finish();

open PRINTERS, "<", "/tmp/printers.csv";
open UPLOAD, ">>", "/tmp/printer_upload.csv";


$line_counter = 1;
$errors = 0;

foreach (<PRINTERS>) {

# Processing text file will stop if any errors are encountered

if ($errors == 0) {

chomp;

# Capture line of text from the file 

	@array = split /\,/, $_; 

	foreach $elem(@array) {

		print "Now assigning values to variables.\n";	
		$model = shift(@array);
		if ($model eq '') {
			print "ERROR. $model on line $line_counter should be a model number and it is empty.\n";
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
			print "Please fix, delete the incomplete file /tmp/computer_upload.csv, and re-run\n";
			$errors = 1;
			last;
			} else {
#			print "And the room ID for $room on line $line_counter is $room_id\n";


		}	# CLOSE THE LAST OF THE ERROR CHECKS

# Convert model into a model ID number by finding the number for the printer_type ID and printer_no ID

@printer = split /\s/, $model;

# Debugging stuff

# print "\n";

# print "What's left in the printer array after getting rid of location info is: \n";

# foreach $elem(@printer) {
#	print "$elem\n";
#	}

# print "\n";

$model_no = pop(@printer);
 

print "Which I need to put back together: \n";

$type = join ' ', @printer;
print "$type\n";

$type_id = $type_matrix{$type};

$combine_values = "$type_id" . " " . "$model_no";


$model_id = $model_matrix{$combine_values};
		if ($model_id =~ /[^\d+]/) {
			print "ERROR. $model_id on line $line_counter should be a model ID number and it is not a number.\n";
			$errors = 1;
			last;
			} elsif ($model_id eq '') {
			print "ERROR. $model_id on line $line_counter should be a model number and it is empty.\n";
			$errors = 1;
			last;
			}




	}	# CLOSE FOREACH ELEM ARRAY

print "For line number $line_counter we got these values: \n";
print "The building is $building and the room is $room with ID $room_id which will be used to determine the location ID.\n";
print "The model number is $model_no and the type is $type.\n";
print "The type ID number is $type_id and the model_id is found using the above: $model_id.\n";

# UNCOMMENT THIS LINE IF STUFF WORKS

print UPLOAD "$model_id,$building,$room_id\n";

} else {
print "Could not create the upload file because you need to do some error fixing.\n";
last;
}		# CLOSE IF ERRORS ARE ZERO

# Go to next line of printers.csv and repeat the above until the end of file

$line_counter++;	

}		# CLOSE FOREACH LINE <COMPUTERS>


close PRINTERS;
close UPLOAD;

# We now have all values ready to create an upload file and dump everthing into the computers table

if ($errors == 0) {


open UPLOAD, "<", "/tmp/printer_upload.csv";

	$query2 = "INSERT INTO printers(printer_model_id,location_id) SELECT ?, location_id FROM locations,room_names WHERE locations.school_id = ? AND room_names.room_name_id = ? AND locations.room_name_id = room_names.room_name_id";
	$sth = $dbh->prepare($query2) or die "Unable to prepare $query2";

foreach (<UPLOAD>) {

	chomp;

# Capture line of text from the file 

	@array = split /\,/, $_; 

	foreach $elem(@array) {
	
	$model = shift(@array);
	$building = shift(@array);
	$room = shift(@array);
	}

#	print "The values on this NEW line are $model, $name, $asset, $serial, $building and room ID: $room.\n";

	$sth->bind_param(1,$model);
	$sth->bind_param(2,$building);
	$sth->bind_param(3,$room);

	$sth->execute();
}
  	$sth->finish(); 

close UPLOAD;
close GET_LOCATION;

$dbh->disconnect;

print "\n\nGreat... All your printers have been entered in the printers table.\n\n";

} else {

	print "\nSorry, you have some errors that need fixing.\n";
	$dbh->disconnect;
}
