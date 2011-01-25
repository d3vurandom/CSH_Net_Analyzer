Some generic readme file
Topics include , dependencies ,database stuff and scripts.

Dependencies:
	MYSQL backend, PHP v5, cron, Nmap v 5.00 or 5.21
	And on each server you want to track
	smartctl, lsscsi, ifconfig, uptime, curl
	


Database Stuff:
	The blank database structure tables are in the install directory.
	You just might want to empty the “general” table.
Scripts:
	Scripts include, checkin.sh, updateNetworkDB.sh, chackStatus.php.
	All the scripts are located in the install directory (except the php file) and all need to be modified 	to your system.
	checkin.sh
		This script needs to run every minute and you need to modify the “serverKey” variable 			and the “checkinServer” variable, examples are in the file.
		The “serverKey” needs to be added into the “server” table. This key is the unique 			identifier for that server, it is also the only field you NEED to populate so that the server 			can check-in.
		I run this script every minute by adding this into cron
	updateNetworkDB.sh
		This script can run whenever you want to update the scan, and it needs to be heavily 			modified for your network.
		Examples are in the file.
		I run this script once a day by adding this into cron.
	checkStatus.php
		This script checks the database for servers it has not seen in a given amount of time 			and sends e-mails accordingly.
		I run this script every minute by adding this into cron.
	
And finally you need to modify the include/config.php 
