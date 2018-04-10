#!/usr/bin/perl

# Your room_names table must be ready. It should contain unique names for all room names
# in use, no matter which building. No duplicates please.

# Also, you should have ready in your /tmp file, a copy of your master list already created
# in comma-separated-value format with building ID number in column one and room names in column two.
# The copy should be called master_list.csv

use DBI;

print "To create an upload file we will get the room_name ID out of the room_names table so\nI need to access the inventory database\n";
print "Please enter the MySQL user name here: \n";  
$admin_name = <STDIN>;
chomp ($admin_name);
print "\nNow please provide the password: \n";
$admin_pw = <STDIN>;
chomp ($admin_pw);

print "Great. Give me a sec, I will try using $admin_name and $admin_pw.\n";

my $server = 'localhost';
my $db = 'inventory';
my $username = $admin_name;
my $password = $admin_pw;

my $dbh = DBI->connect("dbi:mysql:$db:$server", $username, $password);

# Create a file consisting of room_name_id and room_name to make a hash with these
# two elements. The hash will be used to substitute room_name_id for room_name
# from the user's text file, creating a new file for uploading.

#open WRITE_VALUES, ">", "/tmp/room_names2.txt";	# Open for writing

my $query1 = "SELECT room_name_id,room_name from room_names INTO OUTFILE '/tmp/room_names.txt'";

my $sth1 = $dbh->prepare($query1);

$sth1->execute();

#close WRITE_VALUES;

$counter = 0;

open WRITE_VALUES, "<", "/tmp/room_names.txt";	# Open for reading


foreach (<WRITE_VALUES>) # Build an hash @names with room name as value, room ID as key 
{
	chomp;

	@array = split /\t/, $_ ; # Capture one line of text from the file
	foreach $elem(@array) {

 # Printing of info just for testing and debugging

	print "Array for line $counter has this element: $elem\n";
	}
	$room_name_id = shift(@array);	# Get the ID number
	$room_name = shift(@array);	#Get the name
	$name_id{$room_name} = $room_name_id;

	print "So I created a hash with key of $room_name and value of $room_name_id\n";
	print "Thus the value can now be expressed as hash value: $name_id{$room_name}\n";
	print "\n";
	$counter++;
}

close WRITE_VALUES;

# Now, with hash @name_id we can manipulate the text in the complete master_list.csv file


@ARGV = glob "/tmp/master_list.csv" or die "Could not open file master_list.csv";
$^I = ".bak";

$errors = 0;

while (<>) {
	chomp;
	@array1 = ();
	@array1 = split /,/, $_; # Capture line of text from the file 
	$room = $array1[1];	#first element is the building number
#	$room = pop(@array1); # Get the room name as a text string

# Check to make sure there is such a room name defined in the room_names table.
# If not, stop. The user needs to correct this.

if ($name_id{$room} eq "") {
		print STDOUT "You have an error. $room as a defined room name does not seem to exist in the room_names table.\n";
		print STDOUT "Please correct the problem by adding this room name to the table.\n";
		print STDOUT "Then delete master_list.csv in /tmp and rename master_list.csv.bak to master_list.csv\n";
		print STDOUT "and re-run me.\n";
		$errors = 1;
	} else {
#	print STDOUT "Switching $room and the value $name_id{$room}\n";
	s/$room/$name_id{$room}/;
	print;
	print "\n";
} 

}	# CLOSE WHILE LOOP	

if ($errors == 0) {		# Just checking
	my $query2 = "LOAD DATA INFILE '/tmp/master_list.csv' INTO TABLE locations FIELDS TERMINATED BY ',' (school_id,room_name_id)";

	my $sth = $dbh->prepare($query2);

	$sth->execute();

# Finally, add locations for use when assigning to people

	my $query3 = "INSERT INTO locations(room_name_id,school_id) SELECT room_name_id,school_id FROM room_names,schools WHERE room_name_id=1";

	my $sth = $dbh->prepare($query3);

	$sth->execute();



	print "All done. Locations table populated with your data.\n";
} else {

	print "You have to fix at least one error.\n";
}

$dbh->disconnect;

	
