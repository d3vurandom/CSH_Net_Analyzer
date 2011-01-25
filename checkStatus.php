<?php
require ('include/database.php');

$result=dbQuery("SELECT `serverID` FROM `server` WHERE TIMESTAMPDIFF(MINUTE, `lastSeen`, NOW()) > 5 AND `isActive`=1");

$troublesomeServers = array();
while($dbArray = mysql_fetch_assoc($result)) {
	$troublesomeServers[] = $dbArray['serverID'];
}

foreach($troublesomeServers as $value){
	$result=dbQuery("SELECT * FROM `server` WHERE serverID = $value");
	$dbArray = mysql_fetch_assoc($result);	
	
	$lastSeen = $dbArray['lastSeen'];
	$hostname = $dbArray['hostname'];
	$remoteIP = $dbArray['remoteIP'];
	$alertCount = $dbArray['alertCount'];
	$serverOwnerEmail = $dbArray['serverOwnerEmail'];
	$wirelessTxtEmail = $dbArray['wirelessTxtEmail'];
	$serverID=$value;
	
	//mail settings
	$Name = "Broken Server Reporter";
	$email = "SomethingIsWrong@csh.rit.edu"; 
	$mail_body = "We have detected that your server with a hostname of $hostname and the IP of $remoteIP has not been seen in over 5 minutes"; //default
	$subject = "Server $hostname failed a scheduled checkin";
	$header = "From: ". $Name . " <" . $email . ">\r\n";
	
	if($alertCount < 1){
		//send email
		if(!($serverOwnerEmail == "")){
			$recipient = "$serverOwnerEmail";
			mail($recipient, $subject, $mail_body, $header);
		}
		
		//send email to txt
		if(!($wirelessTxtEmail == "")){
			$recipient = "$wirelessTxtEmail";
			mail($recipient, $subject, $mail_body, $header);
		}		
		
		//update alert count
		dbQuery("UPDATE `server` SET `lastAlert` = NOW() WHERE serverID=$serverID");
		dbQuery("UPDATE `server` SET `alertCount` = '1' WHERE `serverID` = '$serverID'");
		
	}
	if($alertCount == 1){
		$result=dbQuery("SELECT * FROM `server` WHERE TIMESTAMPDIFF(MINUTE, `lastAlert`, NOW()) > 5 AND `serverID` = '$serverID'");
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0 ){
		
			$mail_body = "We have detected that your server with a hostname of $hostname and the IP of $remoteIP has not been seen in over 10 minutes";
				
			if(!($serverOwnerEmail == "")){
				$recipient = "$serverOwnerEmail";
				mail($recipient, $subject, $mail_body, $header);
			}
			
			//send email to txt
			if(!($wirelessTxtEmail == "")){
				$recipient = "$wirelessTxtEmail";
				mail($recipient, $subject, $mail_body, $header);
			}	
			
			//update alert count
			dbQuery("UPDATE `server` SET `lastAlert` = NOW() WHERE serverID=$serverID");
			dbQuery("UPDATE `server` SET `alertCount` = '2' WHERE `serverID` = '$serverID'");
		}
	}
	if($alertCount == 2){
		$result=dbQuery("SELECT * FROM `server` WHERE TIMESTAMPDIFF(MINUTE, `lastAlert`, NOW()) > 5 AND `serverID` = '$serverID'");
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0 ){
		
			$mail_body = "We have detected that your server with a hostname of $hostname and the IP of $remoteIP has not been seen in over 15 minutes";
				
			if(!($serverOwnerEmail == "")){
				$recipient = "$serverOwnerEmail";
				mail($recipient, $subject, $mail_body, $header);
			}
			
			//send email to txt
			if(!($wirelessTxtEmail == "")){
				$recipient = "$wirelessTxtEmail";
				mail($recipient, $subject, $mail_body, $header);
			}	
			
			//update alert count
			dbQuery("UPDATE `server` SET `lastAlert` = NOW() WHERE serverID=$serverID");
			dbQuery("UPDATE `server` SET `alertCount` = '3' WHERE `serverID` = '$serverID'");
		}
	}
	if($alertCount == 3){
		$result=dbQuery("SELECT * FROM `server` WHERE TIMESTAMPDIFF(MINUTE, `lastAlert`, NOW()) > 15 AND `serverID` = '$serverID'");
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0 ){
		
			$mail_body = "We have detected that your server with a hostname of $hostname and the IP of $remoteIP has not been seen in over 30 minutes";
				
			if(!($serverOwnerEmail == "")){
				$recipient = "$serverOwnerEmail";
				mail($recipient, $subject, $mail_body, $header);
			}
			
			//send email to txt
			if(!($wirelessTxtEmail == "")){
				$recipient = "$wirelessTxtEmail";
				mail($recipient, $subject, $mail_body, $header);
			}	
			
			//update alert count
			dbQuery("UPDATE `server` SET `lastAlert` = NOW() WHERE serverID=$serverID");
			dbQuery("UPDATE `server` SET `alertCount` = '4' WHERE `serverID` = '$serverID'");
		}
	}
	if($alertCount == 4){
		$result=dbQuery("SELECT * FROM `server` WHERE TIMESTAMPDIFF(MINUTE, `lastAlert`, NOW()) > 30 AND `serverID` = '$serverID'");
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0 ){
		
			$mail_body = "We have detected that your server with a hostname of $hostname and the IP of $remoteIP has not been seen in over 60 minutes";
				
			if(!($serverOwnerEmail == "")){
				$recipient = "$serverOwnerEmail";
				mail($recipient, $subject, $mail_body, $header);
			}
			
			//send email to txt
			if(!($wirelessTxtEmail == "")){
				$recipient = "$wirelessTxtEmail";
				mail($recipient, $subject, $mail_body, $header);
			}	
			
			//update alert count
			dbQuery("UPDATE `server` SET `lastAlert` = NOW() WHERE serverID=$serverID");
			dbQuery("UPDATE `server` SET `alertCount` = 5 WHERE `serverID` = '$serverID'");
		}
	}
		if($alertCount == 5 || $alertCount > 5){
		$result=dbQuery("SELECT * FROM `server` WHERE TIMESTAMPDIFF(MINUTE, `lastAlert`, NOW()) > 1380 AND `serverID` = '$serverID'");
		$num_rows = mysql_num_rows($result);
		if($num_rows > 0 ){
		
			$mail_body = "We have detected that your server with a hostname of $hostname and the IP of $remoteIP has not been seen in over 24 hours";
				
			if(!($serverOwnerEmail == "")){
				$recipient = "$serverOwnerEmail";
				mail($recipient, $subject, $mail_body, $header);
			}
			
			//send email to txt
			if(!($wirelessTxtEmail == "")){
				$recipient = "$wirelessTxtEmail";
				mail($recipient, $subject, $mail_body, $header);
			}	
			
			//update alert count
			$alertCount = $alertCount++;
			dbQuery("UPDATE `server` SET `lastAlert` = NOW() WHERE serverID=$serverID");
			dbQuery("UPDATE `server` SET `alertCount` = '$alertCount' WHERE `serverID` = '$serverID'");
		}
	}
}
echo "Done";
?>