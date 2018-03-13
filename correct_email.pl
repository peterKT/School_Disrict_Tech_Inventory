#!/usr/bin/perl -w

# Run this after Web page files have been placed in /var/www as per directions.

# Update email addresses occurrences replacing user\@localhost with real user name

# If you make a mistake, change the values on 51 and 52 and run again.
# That is replace the string "user" with the erroneous string you might have entered.

print "Changes to the database are logged with email messages. You can of course ignore them.\n";
print "However, to make sure they take place, we need to enter your correct local address in\n";
print "in several places. Please enter your email username on the localhost WITHOUT the \@localhost part.\n";
print "For example, if your local account is joe\@localhost, just enter joe.\n";

$address = <STDIN>;

chomp($address);

print "Great. This just takes a second.\n";

$counter = 1;
$corrections = 0;
$total_corrections = 0;

@array = 
('/var/www/computers/district/assign_netbooks.php',
'/var/www/computers/district/district_add_computers.php',
'/var/www/computers/district/district_delete_computers.php',
'/var/www/computers/district/district_add_locations.php',
'/var/www/computers/district/district_edit_computers2.php',
'/var/www/computers/district/edit_netbooks.php',
'/var/www/district_printers/district_add_ink.php',
'/var/www/district_printers/district_add_new.php',
'/var/www/district_printers/district_add_printer.php',
'/var/www/district_printers/district_edit_locations.php',
'/var/www/district_printers/district_toner_correction.php',
'/var/www/district_printers/district_update_inventory.php',
'/var/www/projectors/add_projector.php',
'/var/www/projectors/add_screen.php',
'/var/www/projectors/delete_projector.php',
'/var/www/projectors/delete_screen.php',
'/var/www/projectors/edit_projectors.php',
'/var/www/projectors/edit_screens.php');


foreach $elem(@array) {

	print "The filename path is $elem.\n";
	@ARGV = glob "$elem" or die "Could not open file $elem\n";
	$^I = ".bak";
	$corrections = 0;
	while (<>) 
	  {
		if (/user\@localhost/) {
			s/user\@localhost/$address\@localhost/;
			$corrections++;
		}
			print;
	  } 		
$total_corrections += $corrections;
print "Made $corrections correction(s) in file $elem\n";
$counter++;

}	# CLOSE while 

print "Made total of $total_corrections corrections in $counter files.\n";

