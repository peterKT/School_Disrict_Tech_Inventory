# inventory
2014
The plan for this repository is to present the HTML/PHP pages used to fashion a simple inventory system for a school district's technology equipment. Inventory management involves different sorts of items (computers, printers, projectors), different attributes of those items (model numbers, item types, i.e. laserjet printer, inkjet printer, etc.), locations (buildings, rooms inside buildings), and other features. Of course, that means bringing in a backend database (or two) to hold all this information. 

So, while the Web pages to be included here create a site that works well and has proven very useful, it is immediately apparent that the code is completely meaningless in the absence of the MySQL databases that hold the information referenced or manipulated by the many PHP queries. 

Understanding that this type of project is very common, my goal is to find out how others communicate their database structures so a model for them can be presented along with the pages. That way potential users and contributors will be able to understand the utility of the queries and the tasks the pages perform.

On the other hand, possibly this sort of project is inapproriate for the GitHub environment.

So stay tuned while I find out.

2017, November
OK, it's three years later and I figured it out. What I will be doing is posting an updated version of the HTML that will include not only a computer (and computer-like devices, i.e. chromebook) inventory managment system, but also similar systems for projectors and smartboards, and printers: three inventory management tools altogether. The directions for using them will include guidelines for the MySQL backend that needs to be setup, possibly automated so all you need to do is run a simple perl program to create the databases with their tables. The tables, dear reader, need to be populated by you. This can be done "en mass" via CSV files you can prepare in advance, but they need to formatted just right for each table. It's well worth the effort though. Once populated with your data, the system makes it very easy to update, add, delete, and generally manage lots of different devices in many different locations. 

So once again, stay tuned.

2017, December
Getting closer and close. Upon review I decided the code herein needed considerable work so I am taking care of that. This is largely due to the nature of the project. It began as a simple exercise to put into practice lessons learned from Larry Ullman's helpful guidebook "PHP and MySQL For Dynamic Web Sites." In the beginning, the inventory system was meant for one type of item: computer hardware, located in one large building. It grew to encompass different types of hardware located in many different buildings. Along the way, three different databases were created to hold different types of info, and each database implemented different ways of doing the same sorts of things, so it got a little confusing. What I am doing now is consolidating into one database (which means re-writing code), simplifying and standardizing as best I can.

It's going pretty fast. I am also posting Wiki pages defining the database structure with all the needed tables. Once populated with data, they should be easy to maintain using this handy system. Details on how to populate them will also be included.
