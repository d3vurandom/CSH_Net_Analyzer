<?php 
function isalphanumeric($data,$strict=false) { //strict makes '-' and '_' not count as being 'alphanumeric'
	for($a=0; $a<strlen($data); $a++) {
		$b=ord(substr($data,$a,1));

		if($strict==false) {
			if(!((($b>=48)&&($b<=57))||(($b>=65)&&($b<=90))||(($b>=97)&&($b<=122))||($b==95)||($b==45))) {
				return(false);
			}
		}
		else {
			if(!((($b>=48)&&($b<=57))||(($b>=65)&&($b<=90))||(($b>=97)&&($b<=122)))) {
				return(false);
			}
		}
	}

	return(true);
}
function isIPValid($ip,$blankvalid=false) {
	if(($ip=="")&&($blankvalid==false))
		return(false);
	elseif(($ip=="")&&($blankvalid==true))
		return(true);

	$octet=explode(".",$ip);

	if(count($octet)!=4)
		return(false);

	for($a=0;$a<4;$a++) {
		if(is_numeric($octet[$a])==false)
			return(false);

		if(($octet[$a]<0)||($octet[$a]>255))
			return(false);
	}

	return(true);
}
function macAddressValid($mac) {
	if(strlen($mac)!=17)
		return(false);

	if(!$tmp=explode(':',$mac))
		return(false);

	if(count($tmp)!=6)
		return(false);

	foreach($tmp as $macSection) {
		if(isalphanumeric($macSection)==false)
			return(false);
	}

	return(true);
}
function sec_to_time($seconds) {

	$days = floor($seconds / 86400);
	$hours = floor($seconds % 86400 / 3600);
	$minutes = floor($seconds % 3600 / 60);
	$seconds = $seconds % 60;
	
	if($days >=1){
		$minutes = 0;
		$seconds = 0;
	}
	if($hours >=1){
		$seconds = 0;
	}
	
	$second_n = $seconds == 1 ? "Second" : "Seconds";
	$minute_n = $minutes == 1 ? "Minute" : "Minutes";
	$hour_n = $hours == 1 ? "Hour" : "hours";
	$day_n = $days == 1 ? "Day" : "Days";
	$time = "";
	if($seconds > 0){
		$time = " " . $seconds . " " . $second_n;
	}
	if($seconds >=0 && $hours < 1 && $minutes < 1){
		$time = " " . $seconds . " " . $second_n;
	}
	if($minutes > 0){
		$time =  " " . $minutes . " " . $minute_n  . $time; 
	}
	if($hours > 0){
		$time =  " " .  $hours . " " . $hour_n  . $time; 
	}
	if($days > 0){
		$time =  " " .  $days . " " . $day_n  . $time; 
	}
	return $time;
	//return sprintf("%:%02d:%02d:%02d", $days, $hours, $minutes, $seconds);
}

function hardDriveCheck($serverID) { //this exists as a function to allow return to cancel out of the hardware test without stopping the whole checkin
	global $smartProperties, $smartPropertiesSet, $deviceData, $deviceID;

	if($smartPropertiesSet == false) {
		$smartProperties = getPermittedSmartElements();
	}

	$driveList = array(); // array used for removing extraneous drives from the table, not sure if it is used lol

	foreach($_GET as $key => $value) { //iterate through $_GET array looking for elements starting with smartStatus_ and mdStatus_
		if(strpos($key, 'smartStatus_') === 0) { //element name starts with smartStatus_, update SMART value
			$updateFor = 'smartStatus';
		}
		elseif(strpos($key, 'mdStatus_') === 0) { //element name starts with mdStatus_, update md value
			$updateFor = 'mdStatus';
		}
		else {
			$updateFor = '';
		}

		if($updateFor != '') { //if updateFor is set, update the appropriate table in the database for either smart or md
			$tmp = explode('_', $key); //splits mdStatus_/smartStatus_ from the appropriate device/array name
	
			if(count($tmp) == 2) { //make sure the explode was successful
				$deviceName = $tmp[1];

				if(isalphanumeric($deviceName) == false)
					return(false);

				if($updateFor == 'smartStatus') {
					if($smartArray = createSmartArray($deviceName)) { //fills an array with smart values found from $_GET
						foreach($smartArray as $key2 => $value2) { //remove all properties not in the smartProperties table, named as key2 and value2 bc this is already in a for loop using both variables
							if(in_array($key2, $smartProperties) == false) {
								unset($smartArray[$key2]);
							}
							elseif(preg_match('/^[a-zA-Z0-9_]{0,}$/', $key2) == false) { //if a non-alphanumeric character was found, bail out of the function
								return(false);
							}
							elseif(preg_match('/^[a-zA-Z0-9_]{0,}$/', $value2) == false) { //if a non-alphanumeric character was found, bail out of the function
								return(false);
							}
						}
					}

					if(isset($smartArray['Spin_Up_Time']) == false || is_numeric($smartArray['Spin_Up_Time']) == false) { // skip drives that don't have a spin up time value
						continue;
					}

					$driveList[] = $deviceName;
					
					updateDriveStatus($serverID, $deviceName, $value, $smartArray);
				}
			}
		}
	}
}

function createSmartArray($deviceName) { //takes deviceName (sda, sdb, etc) and creates array from $_GET variables in format smart_sda_property=value
	$smartArray = array();

	foreach($_GET as $key => $value) {
		$tmp = explode('_', $key, 3); //this will divide the $_GET element's name into pieces

		if( ($tmp[0] == 'smart') && ($tmp[1] == $deviceName) ) { //look for smart status fields for the specified device
			$smartArray[$tmp[2]] = $value; //set the device's smart properties in the array
		}
	}

	if(count($smartArray) == 0) {
		return(false); //something isn't right if no smart status info was found
	}

	return($smartArray);
}


function getPermittedSmartElements() { //returns an array of smart elements by looking at the table structure of the `smartStatus` table, any fields that have underscores in them are considered settable smart fields

	if(!$result = dbQuery("SHOW COLUMNS FROM `smartStatus`"))
		return(false);

	$smartProperties = array();

	while($dbArray = mysql_fetch_array($result)) {
		$field = $dbArray[0];

		if(strpos($field, '_') !== false) {
			$smartProperties[] = $field;	
		}
	}

	if(count($smartProperties) == 0)
		return(false);

	return($smartProperties);
}

function updateDriveStatus($serverID, $deviceName, $status, $smartArray) { //updates drive status in the database by the drive's device name (sda, sdb, etc) status, and smart properties from smartctl
	global $smartProperties;
	
	dbQuery("INSERT INTO `smartStatus` SET `serverID` = '$serverID', `deviceName` = '$deviceName', `status` = '$status',`timestamp`=NOW()");

	if(!$driveID = mysql_insert_id()) {
		return(false);
	}

	if(!is_array($smartArray)) {
		return(false);
	}
	$queryString="";
	foreach($smartArray as $key => $value) {
		if($queryString != '')
			$queryString .= ', ';

		$queryString.= "`$key` = '$value'";
	}

	if($queryString != '') {
		dbQuery("UPDATE `smartStatus` SET $queryString WHERE `driveID` = '$driveID' LIMIT 1");
	}
}
function isFaulty($serverID){
	$result = dbQuery("SELECT timestamp FROM `smartStatus` WHERE serverID = $serverID ORDER by timestamp DESC LIMIT 1");
	$row = mysql_fetch_array($result);
	$timestamp = $row['timestamp'];
	
	$result = dbQuery("SELECT * FROM `smartStatus` WHERE `timestamp` = '$timestamp' ORDER by `deviceName` ASC");
	
	$healthy = 0;
	while($row = mysql_fetch_array($result)){
		//echo $row['serverID']. "=".$row['deviceName'] ."=". $row['Multi_Zone_Error_Rate']. "<br>";
		
			if($row['Raw_Read_Error_Rate'] !=0
				||$row['Reallocated_Sector_Ct'] !=0
				||$row['Seek_Error_Rate'] !=0
				||$row['Reallocated_Event_Count'] !=0
				||$row['Current_Pending_Sector'] !=0
				||$row['Multi_Zone_Error_Rate'] !=0
				||$row['Offline_Uncorrectable'] !=0
				||$row['UDMA_CRC_Error_Count'] !=0){
				$healthy++;
			}
	}
	if($healthy==0){
		return false;
	}
	else{
		return true;
	}
}
?>
