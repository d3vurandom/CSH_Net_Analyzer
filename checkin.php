<?php
require ('include/database.php');
//require ('include/checks.php');
require ('include/functions.php');

$serverKey = $_GET['serverKey'];
$hostname = $_GET['hostname'];
$uptime = $_GET['uptime'];
$localIP = $_GET['localIP']; // not implimented yet 
$remoteIP = $_SERVER['REMOTE_ADDR'];
$primaryNicMac = $_GET['primaryNicMac'];
$smartRequest = $_GET['smartRequest'];

if(!$serverID = passwordToServerID($serverKey)){
	exit;
}
dbQuery("UPDATE server SET `hostname` = '$hostname', `uptime` = '$uptime', `localIP` = '$localIP', `remoteIP` = '$remoteIP' , `alertCount` = '0' , `primaryNicMac` ='$primaryNicMac' WHERE serverID=$serverID");
dbQuery("UPDATE `server` SET `lastSeen` = NOW() WHERE serverID=$serverID");


$smartProperties = array(); //this array is referenced to as a global from functions below, it is populated by the `smartProperties` table
$smartPropertiesSet = false; //used to determine whether or not to populate $smartProperties

hardDriveCheck($serverID);
echo "done\n";
?>