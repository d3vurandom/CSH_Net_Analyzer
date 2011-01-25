#!/bin/sh
#this script needs to be changed for your network
#need to change output location of the scan.txt file
#need to change the path to the updateNetworkScanDB.php script

echo "This script could take as long as 30 min to complete"
echo "Starting IP = 129.21.49.1"
echo "Ending IP = 129.21.50.255"
echo "Starting script in 5 sec"
sleep 5
echo "Scanning..... Please Wait"
nmap -O 129.21.49-50.1-255 > /users/u17/devurandom/public_html/NetworkStats/scan.txt
echo "Scan Complete"
php /users/u17/devurandom/public_html/NetworkStats/include/updateNetworkScanDB.php
echo "DONE!"
