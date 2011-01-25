<?php
	require ('include/database.php');
	require('include/functions.php');
?>
<html>
<head>
<title>Computer Science House Network and Server Status</title>
 <link href="include/style.css" rel="stylesheet" type="text/css">
 <script language="javascript" src="include/ajax.php"></script>
 <script language="javascript" src="include/prototype.js"></script>
 <script language="javascript" src="include/scriptaculous.js"></script>
 <script language="javascript" src="include/effects.js"></script>

</head>
<body onLoad="javascript:loadPage();">
<div id="header">
	<div id="headerContainer">
		<h1>Computer Science House Network and Server Status</h1>
	</div>
</div>
<div id="bar">
</div>
<br />
<center>
<div id="container">
	<div id="networkStatus">
		<?php 
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
			else{
				echo "Something went wrong with the scan";
			}
		?>
	</div>
	<br>
	<div id="serverStatus">
		<?php
		$result=dbQuery("SELECT * FROM `server` WHERE `isActive`='1'");
		$num_rows = mysql_num_rows($result);
		
		if($num_rows > 0){
			echo "<h3><center>CSH Server Status</center></h3>";
			echo "<table border=\"0\">";
			echo "<tr>";
			echo "<td><center><h2>Hostname</h2></center></td>";
			echo "<td><center><h2>IP</h2></center></td>";
			echo "<td><center><h2>Description</h2></center></td>";
			echo "<td><center><h2>Uptime</h2></center></td>";
			echo "<td><center><h2>lastSeen</h2></center></td>";
			echo "<td><center><h2>Status</h2></center></td>";
			echo "</tr>";
			while($row = mysql_fetch_array($result)){
				$status = "";
				$id = $row['serverID'];
				if($row['alertCount'] > 0){
					$status = "<div id=\"offline\"><a href=\"status.php?serverID=$id\">Offline</a></div>";
				}
				else{
					$status = "<div id=\"online\"><a href=\"status.php?serverID=$id\">Online</a></div>";
					
					if(isFaulty($row['serverID'])){
						$status = "<div id=\"faulty\"><a href=\"status.php?serverID=$id\">Faulty</a></div>";
					}
				}
				echo "<div id=\"server" . $row['serverID'] . "\">";
				$lastSeenSeconds = (strtotime("now") - strtotime($row['lastSeen']));
				echo "<tr>";
				echo "<td>". $row['hostname'] . "</td>";
				echo "<td>" . $row['remoteIP'] . "</td>";
				echo "<td>" . $row['comment'] . "</td>";
				echo "<td>" . sec_to_time($row['uptime']) . "</td>";
				echo "<td>" . "<div id=\"lastSeen" . $row['serverID'] ."\"></div>". "</td>";
				//echo "<td>" . "<div id=\"lastSeen\">" .sec_to_time($lastSeenSeconds) . " ago </div></td>";
				echo "<td>" . $status . "</td>";
				echo "</tr>";
				echo "</div>\n";
			}
			echo "</table>";
			echo "<br>";
		}
		?>
	</div>
</div>
</center>
</body>
</html>