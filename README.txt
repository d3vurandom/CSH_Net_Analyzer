#depencies... perhaps
#
##need to be able to run "php <some php file>"
##screen
##Nmap 5.21 
#
#
#
#STEP 1: Setting up the network scanner.
##
##Must add "updateNetworkDB.sh" to your crontab.
##"updateNetworkDB.sh" needs to run in screen once a day or when ever you want, here is an example...
##
##"1 20 * * * screen -d -m -S csh_network_scan /var/www/htdocs/NetworkStats/updateNetworkDB.sh"
##
##You need to modify line number "8" and "10" of "updateNetworkDB.sh" to point to the same directory as your "updateNetworkDB.sh" script and preferably in the same directory as "index.php".
##Once the script runs it will create a "scan.txt" file which will be parsed by the "updateNetworkScanDB.php" script.
##
#
#STEP 2: setting up the database
##
##Must modify include/config.php must change file accordingly (not hard)
##Since you need to make the mysql tables, I have included a table structure of the required tables "server" , "smartStatus" and "stats" so you can easily create the database strusture. they are locaterd in the "install" directory.
##
##
##
##

