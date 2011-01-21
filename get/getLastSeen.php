<?php
	require ('../include/database.php');
	require('../include/functions.php');
	//sleep(1);
	$serverID = $_GET['serverID'];
	$result=dbQuery("SELECT lastSeen FROM `server` WHERE serverID = $serverID");
	$num_rows = mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	
	if($num_rows > 1){
		echo "There is a problem there should not be more than one entry here";
	}
	else{
		$lastSeenSeconds = (strtotime("now") - strtotime($row['lastSeen']));
		echo sec_to_time($lastSeenSeconds);
	}
?>