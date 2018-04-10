#!/usr/bin/perl

# Go through /tmp/computer_list.csv line by line and use the six elements on
# each line to create a file for populating the computers table in the inventory database. 

# First, take the last two elements--building ID and room name--and create the
# single element location ID. Now we have five elements: 1) model_id 2) computer_name 3)
# asset_tag 4) service_tag 5) location_id. Append these to the new text file
# /tmp/computer_upload.csv. Go to next line, repeat.

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

open COMPUTERS, "<", "/tmp/computer_list.csv";
open UPLOAD, ">>", "/tmp/computer_upload.csv";

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

foreach (<COMPUTERS>) {

# Processing text file will stop if any errors are encountered

if ($errors == 0) {

chomp;

# Capture line of text from the file 

	@array = split /\,/, $_; 

	foreach $elem(@array) {

#		print "Now assigning values to variables.\n";	
		$model = shift(@array);
		if ($model =~ /[^\d+]/) {
			print "ERROR. $model on line $line_counter should be a model number and it is not a number.\n";
			$errors = 1;
			last;
			} elsif ($model eq '') {
			print "ERROR. $model on line $line_counter should be a model number and it is empty.\n";
			$errors = 1;
			last;
			}
		$name = shift(@array);
		if (!defined($name)) {
			print "ERROR. $name on line $line_counter should be a computer name or None and it is not.\n";
			$errors = 1;
			last;
			} elsif ($name eq '') {
			print "ERROR. $name on line $line_counter should be a computer name or None and it is empty.\n";
			$errors = 1;
			last;
			}
		$asset = shift(@array);
		if (!defined($asset)) {
			print "ERROR. Asset $asset on line $line_counter should not be empty and it is. You can use 'None'.\n";
			$errors = 1;
			last;
			} elsif ($asset eq '') {
			print "ERROR. Asset $asset on line $line_counter should not be empty and it is. You can use 'None'\n";
			$errors = 1;
			last;
			}
		$serial = shift(@array);
		if (!defined($serial)) {
			print "ERROR. Serial number $serial on line $line_counter should not be empty and it is.\n";
			$errors = 1;
			last;
			} elsif ($serial eq '') {
			print "ERROR. Serial number $serial on line $line_counter should not be empty and it is.\n";
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

			$line_counter++;
		}	# CLOSE THE LAST OF THE ERROR CHECKS

	}	# CLOSE FOREACH ELEM ARRAY

print UPLOAD "$model,$name,$asset,$serial,$building,$room_id\n";

} else {
print "Could not create the upload file because you need to do some error fixing.\n";
last;
}		# CLOSE IF ERRORS ARE ZERO

# Go to next line of computer_list.csv and repeat the above until the end of file

	
}		# CLOSE FOREACH LINE <COMPUTERS>


close COMPUTERS;
close UPLOAD;

# We now have all values ready to create an upload file and dump everthing into the computers table



if ($errors == 0) {


open UPLOAD, "<", "/tmp/computer_upload.csv";

	$query2 = "INSERT INTO computers(model_id,computer_name,asset_tag,service_tag,location_id) SELECT ?,?,?,?,location_id FROM locations,room_names WHERE locations.school_id = ? AND room_names.room_name_id = ? AND locations.room_name_id = room_names.room_name_id";
	$sth = $dbh->prepare($query2) or die "Unable to prepare $query2";

foreach (<UPLOAD>) {

	chomp;

# Capture line of text from the file 

	@array = split /\,/, $_; 

	foreach $elem(@array) {
	
	$model = shift(@array);
	$name = shift(@array);
	$asset = shift(@array);
	$serial = shift(@array);
	$building = shift(@array);
	$room = shift(@array);
	}

#	print "The values on this NEW line are $model, $name, $asset, $serial, $building and room ID: $room.\n";

	$sth->bind_param(1,$model);
	$sth->bind_param(2,$name, SQL_VARCHAR );
	$sth->bind_param(3,$asset, SQL_VARCHAR );
	$sth->bind_param(4,$serial, SQL_VARCHAR );
	$sth->bind_param(5,$building);
	$sth->bind_param(6,$room);

	$sth->execute();
}
  	$sth->finish(); 

close UPLOAD;
close GET_LOCATION;

$dbh->disconnect;

print "\n\nGreat... All your computer-like devices have been entered in the computers table.\n\n";

} else {

	print "\nSorry, you have some errors that need fixing.\n";
	$dbh->disconnect;
}

