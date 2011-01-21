<?php
//Connect to the mysql server and run a query
//This is nessarcy because it limmits the number of open connections to the database.

require ('config.php');

function dbQuery($query) {
	global $dbusername, $dbpassword, $database, $dbserver;
	mysql_connect($dbserver,$dbusername,$dbpassword);
	@mysql_select_db($database) or die( "Unable to select database");
	$result = mysql_query($query);
	return $result;
	mysql_close();
}

function querySingleValue($table,$field,$match_query='') {
	if($match_query!='')
		$query="SELECT $field FROM $table WHERE $match_query LIMIT 1;";
	else
		$query="SELECT $field FROM $table LIMIT 1;";

	$result=dbQuery($query);

	$value="";

	if($result) {
		$lines=mysql_fetch_array($result,MYSQL_ASSOC);	

		if(count($lines)>0) {
			$value=$lines[$field];
		}
	}
	return($value);
}
function passwordToServerID ($serverKey){
	if(!$result = dbQuery("SELECT `serverID` FROM `server` WHERE `serverKey` = '$serverKey' LIMIT 1")) {
		return(false);
	}
	
	if(!$dbArray = mysql_fetch_assoc($result))
		return(false);
		
	$serverID = $dbArray['serverID'];
	
	return($serverID);
}