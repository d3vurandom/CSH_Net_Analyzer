<?php
	require ('include/database.php');
	require('include/functions.php');

	$aliveHosts = "0";
	
	$result=dbQuery("SELECT * FROM `general` ORDER BY scanID DESC");
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0){
		$row = mysql_fetch_array($result);
			$aliveHosts = $row['hosts'];
	}
	$result = dbQuery("SELECT `scanID` FROM `general`");
	$lastScan = mysql_num_rows($result);
	$lastScanTime = (strtotime("now") - strtotime($row['scanTime']));
	$result=dbQuery("SELECT * FROM `stats` WHERE `scanID` = $lastScan ORDER BY ip");
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0){
	//echo $num_rows;
	$os = array();
	$osName = array();
	$aliveHosts = $row['hosts'];

	$array = array();

		while($row = mysql_fetch_array($result)){
			$row['os'] = trim(preg_replace('/[\,\(].*/', '', $row['os']));
			$row['os'] = strtr($row['os'], array('|' => '/'));
			if($row['os'] != "") {
				$row['os'] = preg_replace('/\s+/', "+", $row['os']);
				$array[] = $row['os'];
			}
		}

		$osCountArray = array();

		foreach($array as $osVal) {
			if(isset($osCountArray[$osVal]) == false) {
				$osCountArray[$osVal] = 1;
			}
			else {
				$osCountArray[$osVal]++;
			}
		}

		//asort($osCountArray, SORT_NUMERIC); // might be useful later

		$osCountArray = array_reverse($osCountArray);
		$osString;
		$osNumString;
		foreach($osCountArray as $os => $osCount) {
			$osString .=($os ." = " . $osCount . "|");
			$osNumString .=($osCount . ",");
		}
		$osString = rtrim ($osString , "," );
		$osNumString = rtrim ($osNumString , "," );
		$lastScanTime = "Last Scan was " . sec_to_time($lastScanTime) . " ago";
		echo "<img src=\"http://chart.apis.google.com/chart?chts=000000,24.5&chf=bg,s,67676700&chs=850x350&chd=t:$osNumString&cht=p3&chl=$osString&chtt=CSH+Network+Devices+($aliveHosts)+$lastScanTime&chco=338800|66EE00|FF0000|334455|FFBB00|FFFF00|3300BB|66EEBB|0000EE|CC0000|CCCCCC|990055|005500|009900|AAaaaaC1&\">";
	}
?>