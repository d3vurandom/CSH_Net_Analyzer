#!/bin/bash
#you need to update these vars to your system settings
#Static Vars
primaryNic=eth0 # primary interface
serverKey='_o_' # some unique string
checkinServer='http://csh.rit.edu/~devurandom/NetworkStats/checkin.php' # path to your checkin.php file

hostname=`hostname`
uptime=`cat /proc/uptime | sed 's/ .*//g'`
primaryNicMac=`ifconfig | grep $primaryNic | awk '{print $5}'`

#generate SMART info for all local sata drives
smartRequest=$(lsscsi | grep disk | grep ATA | sed 's/.*\/dev\///' | \
while read drive
do
        result=`smartctl -A /dev/$drive -s on | grep -E '.*[0-9] [a-zA-Z_]+[ ]+[0-9]x' | awk '{printf "'smart_$drive'_%s=%s&", $2, $10}'`

        result=$result"smartStatus_$drive="$?"&"

        printf $result
done)
